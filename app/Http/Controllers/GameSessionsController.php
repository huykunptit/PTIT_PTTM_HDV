<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\GameSession;
use App\Models\Machine;
use App\Models\Area;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class GameSessionController extends Controller
{
    /**
     * Get game sessions with pagination
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = GameSession::with(['user', 'machine', 'area']);

            // Apply filters
            if ($request->has('user_id')) {
                $query->where('user_id', $request->user_id);
            }

            if ($request->has('status')) {
                $query->where('status', $request->status);
            }

            $page = $request->get('page', 1);
            $perPage = $request->get('per_page', 10);

            $sessions = $query->paginate($perPage, ['*'], 'page', $page);

            return response()->json([
                'sessions' => $sessions->items(),
                'total' => $sessions->total(),
                'pages' => $sessions->lastPage(),
                'current_page' => $sessions->currentPage()
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve sessions'], 500);
        }
    }

    /**
     * Create a new game session
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer|exists:users,id',
            'machine_id' => 'required|integer|exists:machines,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 400);
        }

        try {
            // Check if machine is available
            $machine = Machine::findOrFail($request->machine_id);
            if ($machine->status !== 'available') {
                return response()->json(['error' => 'Machine is not available'], 400);
            }

            // Check if user already has an active session
            $activeSession = GameSession::where('user_id', $request->user_id)
                ->where('status', 'active')
                ->first();

            if ($activeSession) {
                return response()->json(['error' => 'User already has an active session'], 400);
            }

            // Get area information
            $area = Area::findOrFail($machine->area_id);

            $session = GameSession::create([
                'user_id' => $request->user_id,
                'machine_id' => $request->machine_id,
                'start_time' => Carbon::now(),
                'status' => 'active',
                'duration_hours' => 0,
                'cost' => 0
            ]);

            // Update machine status
            $machine->update(['status' => 'in_use']);

            return response()->json($session->load(['user', 'machine', 'area']), 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create session'], 500);
        }
    }

    /**
     * Get session by ID
     */
    public function show($id): JsonResponse
    {
        try {
            $session = GameSession::with(['user', 'machine', 'area'])->findOrFail($id);
            return response()->json($session);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Session not found'], 404);
        }
    }

    /**
     * Update game session
     */
    public function update(Request $request, $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'end_time' => 'nullable|date',
            'status' => 'in:active,ended,cancelled'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 400);
        }

        try {
            $session = GameSession::findOrFail($id);
            
            if ($request->has('end_time') && $request->end_time) {
                $endTime = Carbon::parse($request->end_time);
                $startTime = Carbon::parse($session->start_time);
                $durationHours = $endTime->diffInMinutes($startTime) / 60;
                
                // Get machine and area for cost calculation
                $machine = Machine::findOrFail($session->machine_id);
                $area = Area::findOrFail($machine->area_id);
                $cost = $durationHours * $area->price_per_hour;

                $session->update([
                    'end_time' => $endTime,
                    'duration_hours' => $durationHours,
                    'cost' => $cost,
                    'status' => $request->get('status', 'ended')
                ]);

                // Update machine status if session is ended
                if ($session->status !== 'active') {
                    $machine->update(['status' => 'available']);
                }
            } else {
                $session->update($request->only(['status']));
            }

            return response()->json($session->load(['user', 'machine', 'area']));
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update session'], 500);
        }
    }

    /**
     * Delete game session
     */
    public function destroy($id): JsonResponse
    {
        try {
            $session = GameSession::findOrFail($id);
            
            // Update machine status if session was active
            if ($session->status === 'active') {
                $machine = Machine::findOrFail($session->machine_id);
                $machine->update(['status' => 'available']);
            }
            
            $session->delete();
            
            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete session'], 500);
        }
    }

    /**
     * Check and handle expired sessions
     */
    public function checkExpired(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'max_duration_hours' => 'required|numeric|min:0'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 400);
        }

        try {
            $maxDuration = $request->max_duration_hours;
            $cutoffTime = Carbon::now()->subHours($maxDuration);

            $expiredSessions = GameSession::where('status', 'active')
                ->where('start_time', '<=', $cutoffTime)
                ->get();

            foreach ($expiredSessions as $session) {
                $endTime = Carbon::now();
                $startTime = Carbon::parse($session->start_time);
                $durationHours = $endTime->diffInMinutes($startTime) / 60;
                
                // Get machine and area for cost calculation
                $machine = Machine::findOrFail($session->machine_id);
                $area = Area::findOrFail($machine->area_id);
                $cost = $durationHours * $area->price_per_hour;

                $session->update([
                    'end_time' => $endTime,
                    'duration_hours' => $durationHours,
                    'cost' => $cost,
                    'status' => 'ended'
                ]);

                // Update machine status
                $machine->update(['status' => 'available']);
            }

            return response()->json($expiredSessions->load(['user', 'machine', 'area']));
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to check expired sessions'], 500);
        }
    }

    /**
     * Get session statistics
     */
    public function statistics(Request $request): JsonResponse
    {
        try {
            $query = GameSession::query();

            if ($request->has('start_date')) {
                $query->where('start_time', '>=', $request->start_date);
            }

            if ($request->has('end_date')) {
                $query->where('start_time', '<=', $request->end_date);
            }

            if ($request->has('machine_id')) {
                $query->where('machine_id', $request->machine_id);
            }

            if ($request->has('area_id')) {
                $query->whereHas('machine', function ($q) use ($request) {
                    $q->where('area_id', $request->area_id);
                });
            }

            $sessions = $query->get();

            $statistics = [
                'total_sessions' => $sessions->count(),
                'total_duration_hours' => $sessions->sum('duration_hours'),
                'total_revenue' => $sessions->sum('cost'),
                'average_duration_hours' => $sessions->avg('duration_hours') ?? 0,
                'peak_hours' => $this->calculatePeakHours($sessions),
                'machine_usage' => $this->calculateMachineUsage($sessions)
            ];

            return response()->json($statistics);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve statistics'], 500);
        }
    }

    /**
     * Get session analytics
     */
    public function analytics(Request $request): JsonResponse
    {
        try {
            $days = $request->get('days', 30);
            $startDate = Carbon::now()->subDays($days);

            $query = GameSession::where('start_time', '>=', $startDate);

            if ($request->has('machine_id')) {
                $query->where('machine_id', $request->machine_id);
            }

            if ($request->has('area_id')) {
                $query->whereHas('machine', function ($q) use ($request) {
                    $q->where('area_id', $request->area_id);
                });
            }

            $sessions = $query->get();

            $analytics = [
                'daily_usage' => $this->calculateDailyUsage($sessions),
                'hourly_distribution' => $this->calculateHourlyDistribution($sessions),
                'revenue_trend' => $this->calculateRevenueTrend($sessions),
                'popular_machines' => $this->calculatePopularMachines($sessions)
            ];

            return response()->json($analytics);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve analytics'], 500);
        }
    }

    private function calculatePeakHours($sessions)
    {
        $hours = [];
        foreach ($sessions as $session) {
            $hour = Carbon::parse($session->start_time)->hour;
            $hours[$hour] = ($hours[$hour] ?? 0) + 1;
        }
        return $hours;
    }

    private function calculateMachineUsage($sessions)
    {
        $usage = [];
        foreach ($sessions as $session) {
            $machineId = $session->machine_id;
            if (!isset($usage[$machineId])) {
                $usage[$machineId] = [
                    'total_sessions' => 0,
                    'total_duration' => 0,
                    'total_revenue' => 0
                ];
            }
            $usage[$machineId]['total_sessions']++;
            $usage[$machineId]['total_duration'] += $session->duration_hours;
            $usage[$machineId]['total_revenue'] += $session->cost;
        }
        return $usage;
    }

    private function calculateDailyUsage($sessions)
    {
        $daily = [];
        foreach ($sessions as $session) {
            $date = Carbon::parse($session->start_time)->format('Y-m-d');
            if (!isset($daily[$date])) {
                $daily[$date] = [
                    'sessions' => 0,
                    'duration' => 0,
                    'revenue' => 0
                ];
            }
            $daily[$date]['sessions']++;
            $daily[$date]['duration'] += $session->duration_hours;
            $daily[$date]['revenue'] += $session->cost;
        }
        return $daily;
    }

    private function calculateHourlyDistribution($sessions)
    {
        $hours = [];
        foreach ($sessions as $session) {
            $hour = Carbon::parse($session->start_time)->hour;
            $hours[$hour] = ($hours[$hour] ?? 0) + 1;
        }
        return $hours;
    }

    private function calculateRevenueTrend($sessions)
    {
        $trend = [];
        foreach ($sessions as $session) {
            $date = Carbon::parse($session->start_time)->format('Y-m-d');
            $trend[$date] = ($trend[$date] ?? 0) + $session->cost;
        }
        return $trend;
    }

    private function calculatePopularMachines($sessions)
    {
        return $this->calculateMachineUsage($sessions);
    }
}