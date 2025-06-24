<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class MachineController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->except(['show']);
    }

    /**
     * Get all machines
     */
    public function index(): JsonResponse
    {
        try {
            $machines = DB::table('machines as m')
                ->join('areas as a', 'm.area_id', '=', 'a.id')
                ->select('m.*', 'a.name as area_name', 'a.price_per_hour')
                ->orderBy('m.area_id')
                ->orderBy('m.code')
                ->get();

            return response()->json($machines);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve machines'], 500);
        }
    }

    /**
     * Create a new machine
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:50|unique:machines,code',
            'ip_address' => 'required|ip|unique:machines,ip_address',
            'area_id' => 'required|integer|exists:areas,id',
            'status' => 'sometimes|in:available,in_use,maintenance'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 400);
        }

        try {
            $machineId = DB::table('machines')->insertGetId([
                'code' => $request->code,
                'ip_address' => $request->ip_address,
                'area_id' => $request->area_id,
                'status' => $request->status ?? 'available',
                'created_at' => now(),
                'updated_at' => now()
            ]);

            $machine = DB::table('machines as m')
                ->join('areas as a', 'm.area_id', '=', 'a.id')
                ->select('m.*', 'a.name as area_name', 'a.price_per_hour')
                ->where('m.id', $machineId)
                ->first();

            return response()->json($machine, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create machine'], 500);
        }
    }

    /**
     * Get machine by ID
     */
    public function show($id): JsonResponse
    {
        try {
            $machine = DB::table('machines as m')
                ->join('areas as a', 'm.area_id', '=', 'a.id')
                ->select('m.*', 'a.name as area_name', 'a.price_per_hour', 'a.description as area_description')
                ->where('m.id', $id)
                ->first();

            if (!$machine) {
                return response()->json(['error' => 'Machine not found'], 404);
            }

            // Get current session if machine is in use
            $currentSession = null;
            if ($machine->status === 'in_use') {
                $currentSession = DB::table('game_sessions as gs')
                    ->join('users as u', 'gs.user_id', '=', 'u.id')
                    ->select('gs.*', 'u.full_name as user_name')
                    ->where('gs.machine_id', $id)
                    ->where('gs.status', 'active')
                    ->first();
            }

            $machine->current_session = $currentSession;

            return response()->json($machine);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve machine'], 500);
        }
    }

    /**
     * Update machine
     */
    public function update(Request $request, $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'code' => 'sometimes|string|max:50|unique:machines,code,' . $id,
            'ip_address' => 'sometimes|ip|unique:machines,ip_address,' . $id,
            'area_id' => 'sometimes|integer|exists:areas,id',
            'status' => 'sometimes|in:available,in_use,maintenance'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 400);
        }

        try {
            $machine = DB::table('machines')->where('id', $id)->first();

            if (!$machine) {
                return response()->json(['error' => 'Machine not found'], 404);
            }

            // Check if trying to change status to 'available' while there's an active session
            if ($request->has('status') && $request->status === 'available') {
                $activeSession = DB::table('game_sessions')
                    ->where('machine_id', $id)
                    ->where('status', 'active')
                    ->exists();

                if ($activeSession) {
                    return response()->json(['error' => 'Cannot set machine as available while there is an active session'], 400);
                }
            }

            $updateData = array_filter([
                'code' => $request->code,
                'ip_address' => $request->ip_address,
                'area_id' => $request->area_id,
                'status' => $request->status,
                'updated_at' => now()
            ], function ($value) {
                return $value !== null;
            });

            DB::table('machines')->where('id', $id)->update($updateData);

            return $this->show($id);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update machine'], 500);
        }
    }

    /**
     * Delete machine
     */
    public function destroy($id): JsonResponse
    {
        try {
            $machine = DB::table('machines')->where('id', $id)->first();

            if (!$machine) {
                return response()->json(['error' => 'Machine not found'], 404);
            }

            // Check if machine has any active sessions
            $activeSession = DB::table('game_sessions')
                ->where('machine_id', $id)
                ->where('status', 'active')
                ->exists();

            if ($activeSession) {
                return response()->json(['error' => 'Cannot delete machine with active session'], 400);
            }

            DB::table('machines')->where('id', $id)->delete();

            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete machine'], 500);
        }
    }

    /**
     * Get available machines by area
     */
    public function getAvailableByArea($areaId): JsonResponse
    {
        try {
            $area = DB::table('areas')->where('id', $areaId)->first();

            if (!$area) {
                return response()->json(['error' => 'Area not found'], 404);
            }

            $machines = DB::table('machines')
                ->where('area_id', $areaId)
                ->where('status', 'available')
                ->orderBy('code')
                ->get();

            return response()->json([
                'area' => $area,
                'available_machines' => $machines
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve available machines'], 500);
        }
    }

    /**
     * Get machine statistics
     */
    public function getStatistics($id): JsonResponse
    {
        try {
            $machine = DB::table('machines')->where('id', $id)->first();

            if (!$machine) {
                return response()->json(['error' => 'Machine not found'], 404);
            }

            // Get usage statistics
            $stats = DB::table('game_sessions')
                ->where('machine_id', $id)
                ->where('status', '!=', 'cancelled')
                ->selectRaw('
                    COUNT(*) as total_sessions,
                    SUM(duration_hours) as total_hours,
                    SUM(cost) as total_revenue,
                    AVG(duration_hours) as avg_duration,
                    MAX(end_time) as last_used
                ')
                ->first();

            // Get daily usage for last 30 days
            $dailyUsage = DB::table('game_sessions')
                ->where('machine_id', $id)
                ->where('status', '!=', 'cancelled')
                ->where('start_time', '>=', now()->subDays(30))
                ->selectRaw('DATE(start_time) as date, COUNT(*) as sessions, SUM(cost) as revenue')
                ->groupBy('date')
                ->orderBy('date')
                ->get();

            return response()->json([
                'machine' => $machine,
                'statistics' => $stats,
                'daily_usage' => $dailyUsage
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve machine statistics'], 500);
        }
    }

    /**
     * Update machine status
     */
    public function updateStatus(Request $request, $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:available,in_use,maintenance'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 400);
        }

        try {
            $machine = DB::table('machines')->where('id', $id)->first();

            if (!$machine) {
                return response()->json(['error' => 'Machine not found'], 404);
            }

            // Additional validation for status changes
            if ($request->status === 'available') {
                $activeSession = DB::table('game_sessions')
                    ->where('machine_id', $id)
                    ->where('status', 'active')
                    ->exists();

                if ($activeSession) {
                    return response()->json(['error' => 'Cannot set machine as available while there is an active session'], 400);
                }
            }

            DB::table('machines')
                ->where('id', $id)
                ->update([
                    'status' => $request->status,
                    'updated_at' => now()
                ]);

            return response()->json(['message' => 'Machine status updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update machine status'], 500);
        }
    }
}