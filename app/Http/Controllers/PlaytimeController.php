<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\GameSession;
use App\Models\Machine;
use App\Models\Area;
use Carbon\Carbon;

class PlaytimeController extends Controller
{
    /**
     * Get current playtime status for authenticated user
     */
    public function getPlaytimeStatus(): JsonResponse
    {
        try {
            $user = Auth::user();
            
            // Find active game session
            $activeSession = GameSession::where('user_id', $user->id)
                ->where('status', 'active')
                ->with(['machine.area'])
                ->first();

            if (!$activeSession) {
                return response()->json([
                    'playtime' => [
                        'remaining_minutes' => 0,
                        'is_active' => false,
                        'formatted_time' => '00:00:00',
                        'total_minutes' => 0,
                        'elapsed_minutes' => 0,
                        'notification_level' => 'none',
                        'machine' => null
                    ]
                ]);
            }

            $startTime = Carbon::parse($activeSession->start_time);
            $now = Carbon::now();
            $elapsedMinutes = $startTime->diffInMinutes($now);
            
            // Calculate remaining time based on purchased time or area pricing
            $pricePerHour = $activeSession->machine->area->price_per_hour;
            $totalMinutes = $activeSession->duration_hours * 60;
            $remainingMinutes = max(0, $totalMinutes - $elapsedMinutes);

            // Determine notification level
            $notificationLevel = 'none';
            if ($remainingMinutes <= 5) {
                $notificationLevel = 'critical';
            } elseif ($remainingMinutes <= 15) {
                $notificationLevel = 'warning';
            } elseif ($remainingMinutes <= 30) {
                $notificationLevel = 'info';
            }

            return response()->json([
                'playtime' => [
                    'remaining_minutes' => $remainingMinutes,
                    'is_active' => true,
                    'formatted_time' => $this->formatMinutes($remainingMinutes),
                    'total_minutes' => $totalMinutes,
                    'elapsed_minutes' => $elapsedMinutes,
                    'notification_level' => $notificationLevel,
                    'machine' => [
                        'id' => $activeSession->machine->id,
                        'name' => $activeSession->machine->code,
                        'price_per_hour' => $pricePerHour
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to get playtime status'
            ], 500);
        }
    }

    /**
     * Extend current playing time
     */
    public function extendPlaytime(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'hours' => 'required|numeric|min:0.5|max:12',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Invalid input data',
                'details' => $validator->errors()
            ], 400);
        }

        try {
            $user = Auth::user();
            $hoursToAdd = $request->hours;

            // Find active session
            $activeSession = GameSession::where('user_id', $user->id)
                ->where('status', 'active')
                ->with(['machine.area'])
                ->first();

            if (!$activeSession) {
                return response()->json([
                    'error' => 'No active gaming session found'
                ], 404);
            }

            // Calculate cost
            $pricePerHour = $activeSession->machine->area->price_per_hour;
            $additionalCost = $hoursToAdd * $pricePerHour;

            // Update session
            $activeSession->duration_hours += $hoursToAdd;
            $activeSession->cost += $additionalCost;
            $activeSession->save();

            return response()->json([
                'message' => 'Playtime extended successfully',
                'session' => $activeSession,
                'additional_cost' => $additionalCost,
                'new_total_hours' => $activeSession->duration_hours
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to extend playtime'
            ], 500);
        }
    }

    /**
     * End current gaming session
     */
    public function endSession(): JsonResponse
    {
        try {
            $user = Auth::user();

            $activeSession = GameSession::where('user_id', $user->id)
                ->where('status', 'active')
                ->first();

            if (!$activeSession) {
                return response()->json([
                    'error' => 'No active gaming session found'
                ], 404);
            }

            // Update session
            $activeSession->end_time = Carbon::now();
            $activeSession->status = 'ended';
            
            // Calculate actual duration and cost
            $startTime = Carbon::parse($activeSession->start_time);
            $endTime = Carbon::parse($activeSession->end_time);
            $actualHours = $startTime->diffInHours($endTime, true);
            
            $activeSession->duration_hours = $actualHours;
            $activeSession->save();

            // Update machine status to available
            $machine = Machine::find($activeSession->machine_id);
            if ($machine) {
                $machine->status = 'available';
                $machine->save();
            }

            return response()->json([
                'message' => 'Gaming session ended successfully',
                'session' => $activeSession
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to end session'
            ], 500);
        }
    }

    /**
     * Get playtime history
     */
    public function getPlaytimeHistory(Request $request): JsonResponse
    {
        try {
            $user = Auth::user();
            $page = $request->get('page', 1);
            $perPage = $request->get('per_page', 10);

            $sessions = GameSession::where('user_id', $user->id)
                ->with(['machine.area'])
                ->orderBy('created_at', 'desc')
                ->paginate($perPage, ['*'], 'page', $page);

            return response()->json([
                'sessions' => $sessions->items(),
                'total' => $sessions->total(),
                'current_page' => $sessions->currentPage(),
                'per_page' => $sessions->perPage(),
                'last_page' => $sessions->lastPage()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to get playtime history'
            ], 500);
        }
    }

    /**
     * Purchase playtime for a machine
     */
    public function purchasePlaytime(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'machine_id' => 'required|integer|exists:machines,id',
            'hours' => 'required|numeric|min:0.5|max:12',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Invalid input data',
                'details' => $validator->errors()
            ], 400);
        }

        try {
            $user = Auth::user();
            $machineId = $request->machine_id;
            $hours = $request->hours;

            // Check if user has active session
            $existingSession = GameSession::where('user_id', $user->id)
                ->where('status', 'active')
                ->first();

            if ($existingSession) {
                return response()->json([
                    'error' => 'You already have an active gaming session'
                ], 400);
            }

            // Get machine and check availability
            $machine = Machine::with('area')->find($machineId);
            
            if ($machine->status !== 'available') {
                return response()->json([
                    'error' => 'Machine is not available'
                ], 400);
            }

            // Calculate cost
            $cost = $hours * $machine->area->price_per_hour;

            // Create new session
            $session = GameSession::create([
                'user_id' => $user->id,
                'machine_id' => $machineId,
                'start_time' => Carbon::now(),
                'status' => 'active',
                'duration_hours' => $hours,
                'cost' => $cost
            ]);

            // Update machine status
            $machine->status = 'in_use';
            $machine->save();

            return response()->json([
                'message' => 'Playtime purchased successfully',
                'session' => $session->load(['machine.area']),
                'cost' => $cost
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to purchase playtime'
            ], 500);
        }
    }

    /**
     * Check for expired sessions and handle them
     */
    public function checkExpiredSessions(Request $request): JsonResponse
    {
        try {
            $maxDurationHours = $request->get('max_duration_hours', 24);

            $expiredSessions = GameSession::where('status', 'active')
                ->where('start_time', '<=', Carbon::now()->subHours($maxDurationHours))
                ->with(['machine'])
                ->get();

            $updatedSessions = [];

            foreach ($expiredSessions as $session) {
                // End the session
                $session->end_time = Carbon::now();
                $session->status = 'ended';
                
                // Calculate actual duration
                $startTime = Carbon::parse($session->start_time);
                $endTime = Carbon::parse($session->end_time);
                $actualHours = $startTime->diffInHours($endTime, true);
                $session->duration_hours = $actualHours;
                
                $session->save();

                // Update machine status
                if ($session->machine) {
                    $session->machine->status = 'available';
                    $session->machine->save();
                }

                $updatedSessions[] = $session;
            }

            return response()->json($updatedSessions);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to check expired sessions'
            ], 500);
        }
    }

    /**
     * Format minutes to HH:MM:SS format
     */
    private function formatMinutes(int $minutes): string
    {
        $hours = intval($minutes / 60);
        $mins = $minutes % 60;
        return sprintf('%02d:%02d:00', $hours, $mins);
    }
}