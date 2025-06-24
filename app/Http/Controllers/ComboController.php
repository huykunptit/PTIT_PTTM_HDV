<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Combo;
use App\Models\ComboItem;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class ComboController extends Controller
{
    /**
     * Get all combos
     */
    public function index(): JsonResponse
    {
        try {
            $combos = Combo::with(['items.product'])->get();
            return response()->json($combos);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve combos'], 500);
        }
    }

    /**
     * Create a new combo
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'is_active' => 'boolean',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|integer|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 400);
        }

        try {
            $combo = Combo::create($request->only([
                'name', 'description', 'price', 'discount_percentage', 'is_active'
            ]));

            foreach ($request->items as $item) {
                ComboItem::create([
                    'combo_id' => $combo->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity']
                ]);
            }

            return response()->json($combo->load('items.product'), 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create combo'], 500);
        }
    }

    /**
     * Get combo by ID
     */
    public function show($id): JsonResponse
    {
        try {
            $combo = Combo::with(['items.product'])->findOrFail($id);
            return response()->json($combo);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Combo not found'], 404);
        }
    }

    /**
     * Update combo
     */
    public function update(Request $request, $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'string|max:255',
            'description' => 'nullable|string',
            'price' => 'numeric|min:0',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'is_active' => 'boolean',
            'items' => 'array',
            'items.*.product_id' => 'integer|exists:products,id',
            'items.*.quantity' => 'integer|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 400);
        }

        try {
            $combo = Combo::findOrFail($id);
            $combo->update($request->only([
                'name', 'description', 'price', 'discount_percentage', 'is_active'
            ]));

            if ($request->has('items')) {
                // Delete existing items
                ComboItem::where('combo_id', $combo->id)->delete();

                // Add new items
                foreach ($request->items as $item) {
                    ComboItem::create([
                        'combo_id' => $combo->id,
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity']
                    ]);
                }
            }

            return response()->json($combo->load('items.product'));
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update combo'], 500);
        }
    }

    /**
     * Delete combo
     */
    public function destroy($id): JsonResponse
    {
        try {
            $combo = Combo::findOrFail($id);
            
            // Delete related items first
            ComboItem::where('combo_id', $combo->id)->delete();
            
            $combo->delete();
            
            return response()->json(['message' => 'Combo deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete combo'], 500);
        }
    }

    /**
     * Get active combos
     */
    public function getActiveCombos(): JsonResponse
    {
        try {
            $combos = Combo::with(['items.product'])
                ->where('is_active', true)
                ->get();
            return response()->json($combos);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve active combos'], 500);
        }
    }
}