<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AreaController extends Controller
{
    /**
     * Get all areas
     */
    public function index(): JsonResponse
    {
        try {
            $areas = DB::table('areas as a')
                ->leftJoin('machines as m', 'a.id', '=', 'm.area_id')
                ->select('a.*', DB::raw('COUNT(m.id) as machine_count'))
                ->groupBy('a.id', 'a.name', 'a.description', 'a.price_per_hour', 'a.created_at', 'a.updated_at')
                ->get()
                ->map(function ($area) {
                    $machines = DB::table('machines')
                        ->where('area_id', $area->id)
                        ->get();
                    
                    $area->machines = $machines;
                    return $area;
                });

            return response()->json($areas);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve areas'], 500);
        }
    }

    /**
     * Create a new area
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:areas,name',
            'description' => 'nullable|string',
            'price_per_hour' => 'required|numeric|min:0'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 400);
        }

        try {
            $areaId = DB::table('areas')->insertGetId([
                'name' => $request->name,
                'description' => $request->description,
                'price_per_hour' => $request->price_per_hour,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            $area = DB::table('areas')->where('id', $areaId)->first();
            $area->machines = [];

            return response()->json($area, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create area'], 500);
        }
    }

    /**
     * Get area by ID
     */
    public function show($id): JsonResponse
    {
        try {
            $area = DB::table('areas')->where('id', $id)->first();

            if (!$area) {
                return response()->json(['error' => 'Area not found'], 404);
            }

            $machines = DB::table('machines')
                ->where('area_id', $id)
                ->get();

            $area->machines = $machines;

            return response()->json($area);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve area'], 500);
        }
    }

    /**
     * Update area
     */
    public function update(Request $request, $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255|unique:areas,name,' . $id,
            'description' => 'nullable|string',
            'price_per_hour' => 'sometimes|numeric|min:0'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 400);
        }

        try {
            $area = DB::table('areas')->where('id', $id)->first();

            if (!$area) {
                return response()->json(['error' => 'Area not found'], 404);
            }

            $updateData = array_filter([
                'name' => $request->name,
                'description' => $request->description,
                'price_per_hour' => $request->price_per_hour,
                'updated_at' => now()
            ], function ($value) {
                return $value !== null;
            });

            DB::table('areas')->where('id', $id)->update($updateData);

            return $this->show($id);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update area'], 500);
        }
    }

    /**
     * Delete area
     */
    public function destroy($id): JsonResponse
    {
        try {
            $area = DB::table('areas')->where('id', $id)->first();

            if (!$area) {
                return response()->json(['error' => 'Area not found'], 404);
            }

            // Check if area has machines
            $machineCount = DB::table('machines')->where('area_id', $id)->count();

            if ($machineCount > 0) {
                return response()->json(['error' => 'Cannot delete area with existing machines'], 400);
            }

            DB::table('areas')->where('id', $id)->delete();

            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete area'], 500);
        }
    }

    /**
     * Get areas with availability status
     */
    public function getAvailableAreas(): JsonResponse
    {
        try {
            $areas = DB::table('areas as a')
                ->leftJoin('machines as m', 'a.id', '=', 'm.area_id')
                ->select(
                    'a.*',
                    DB::raw('COUNT(m.id) as total_machines'),
                    DB::raw("COUNT(CASE WHEN m.status = 'available' THEN 1 END) as available_machines"),
                    DB::raw("COUNT(CASE WHEN m.status = 'in_use' THEN 1 END) as in_use_machines"),
                    DB::raw("COUNT(CASE WHEN m.status = 'maintenance' THEN 1 END) as maintenance_machines")
                )
                ->groupBy('a.id', 'a.name', 'a.description', 'a.price_per_hour', 'a.created_at', 'a.updated_at')
                ->get()
                ->map(function ($area) {
                    $area->availability_status = $area->available_machines > 0 ? 'available' : 
                        ($area->total_machines > 0 ? 'full' : 'no_machines');
                    return $area;
                });

            return response()->json($areas);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve areas with availability'], 500);
        }
    }
}