<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $response = Http::withToken(Session::get('token'))
            ->get(env('GATEWAY_URL') . '/api/product/products');

        $data = $response->json();
        $toast = [
            'type' => $response->successful() ? 'success' : 'error',
            'message' => $response->successful()
                ? 'Product list loaded successfully.'
                : ($data['message'] ?? 'Unable to load product list. Please try again.')
        ];

        session()->flash('toast', $toast);

        return view('admin.product.index', compact('data'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        $response = Http::withToken(Session::get('token'))
            ->get(env('GATEWAY_URL') . '/api/category/categories');

        $categories = $response->json() ?? [];

        return view('admin.product.create', compact('categories'));
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'           => 'required|string',
            'description'    => 'nullable|string',
            'price'          => 'required|numeric',
            'stock'          => 'required|integer',
            'category_id'    => 'required|integer',
            'image'          => 'nullable|string',
            'duration_hours' => 'required|integer',
            'start_hour'     => 'nullable|integer',
            'end_hour'       => 'nullable|integer',
            'metadata'       => 'nullable|array',
        ]);

        $validated['start_hour'] = $validated['start_hour'] ?? 22;
        $validated['end_hour'] = $validated['end_hour'] ?? 6;

        $response = Http::withToken(Session::get('token'))
            ->post(env('GATEWAY_URL') . '/api/product/night-combos', $validated);

        $data = $response->json();

        $toast = [
            'type' => $response->successful() ? 'success' : 'error',
            'message' => $response->successful()
                ? 'Product created successfully.'
                : ($data['message'] ?? 'Unable to create product. Please try again.')
        ];

        session()->flash('toast', $toast);

        return redirect()->route('products.index');
    }

    /**
     * Display the specified product.
     */
    public function show(string $id)
    {
        $response = Http::withToken(Session::get('token'))
            ->get(env('GATEWAY_URL') . '/api/product/night-combos', ['id' => $id]);

        $data = $response->json();

        $toast = [
            'type' => $response->successful() ? 'success' : 'error',
            'message' => $response->successful()
                ? 'Night Combo loaded successfully.'
                : ($data['message'] ?? 'Unable to load Night Combo.')
        ];

        session()->flash('toast', $toast);

        return view('admin.product.show', compact('data'));
    }

    /**
     * Show the form for editing a Night Combo.
     */
    public function getCombo(Request $request, string $id)
    {
        $request->validate(['combo_id' => 'required|integer']);

        $response = Http::withToken(Session::get('token'))
            ->get(env('GATEWAY_URL') . '/api/product/night-combos', [
                'combo_id' => $request->combo_id
            ]);

        $data = $response->json();

        $toast = [
            'type' => $response->successful() ? 'success' : 'error',
            'message' => $response->successful()
                ? 'Night Combo loaded successfully.'
                : ($data['message'] ?? 'Unable to load Night Combo.')
        ];

        session()->flash('toast', $toast);

        return view('admin.product.edit', compact('data'));
    }

    /**
     * Update the specified Night Combo.
     */
    public function updateCombo(Request $request, string $id)
    {
        $validated = $request->validate([
            'name'           => 'sometimes|string',
            'description'    => 'sometimes|string|nullable',
            'price'          => 'sometimes|numeric',
            'stock'          => 'sometimes|integer',
            'category_id'    => 'sometimes|integer',
            'image'          => 'sometimes|string|nullable',
            'duration_hours' => 'sometimes|integer',
            'start_hour'     => 'sometimes|integer|nullable',
            'end_hour'       => 'sometimes|integer|nullable',
            'is_active'      => 'sometimes|boolean',
            'metadata'       => 'sometimes|array|nullable',
        ]);

        $response = Http::withToken(Session::get('token'))
            ->put(env('GATEWAY_URL') . '/api/product/night-combos/' . $id, $validated);

        $data = $response->json();

        $toast = [
            'type' => $response->successful() ? 'success' : 'error',
            'message' => $response->successful()
                ? 'Night Combo updated successfully.'
                : ($data['message'] ?? 'Unable to update Night Combo.')
        ];

        session()->flash('toast', $toast);

        return redirect()->route('products.show', ['product' => $id]);
    }

    /**
     * Remove the specified Night Combo.
     */
    public function destroyCombo(string $id)
    {
        $response = Http::withToken(Session::get('token'))
            ->delete(env('GATEWAY_URL') . '/api/product/night-combos/' . $id);

        $data = $response->json();

        $toast = [
            'type' => $response->successful() ? 'success' : 'error',
            'message' => $response->successful()
                ? 'Night Combo deleted successfully.'
                : ($data['message'] ?? 'Unable to delete Night Combo.')
        ];

        session()->flash('toast', $toast);

        return redirect()->route('products.index');
    }

    /**
     * List all Night Combos.
     */
    public function listNightCombos()
    {
        $response = Http::withToken(Session::get('token'))
            ->get(env('GATEWAY_URL') . '/api/product/night-combos');

        $data = $response->json();

        session()->flash('toast', [
            'type' => $response->successful() ? 'success' : 'error',
            'message' => $response->successful()
                ? 'Night Combos loaded successfully.'
                : ($data['message'] ?? 'Unable to load Night Combos.')
        ]);

        return view('admin.product.night_combos.index', compact('data'));
    }

    /**
     * Store a new Night Combo.
     */
    public function storeNightCombo(Request $request)
    {
        $validated = $request->validate([
            'name'           => 'required|string',
            'description'    => 'nullable|string',
            'price'          => 'required|numeric',
            'stock'          => 'required|integer',
            'category_id'    => 'required|integer',
            'image'          => 'nullable|string',
            'duration_hours' => 'required|integer',
            'start_hour'     => 'nullable|integer',
            'end_hour'       => 'nullable|integer',
            'metadata'       => 'nullable|array',
        ]);

        $validated['start_hour'] = $validated['start_hour'] ?? 22;
        $validated['end_hour'] = $validated['end_hour'] ?? 6;

        $response = Http::withToken(Session::get('token'))
            ->post(env('GATEWAY_URL') . '/api/product/night-combos', $validated);

        $data = $response->json();

        session()->flash('toast', [
            'type' => $response->successful() ? 'success' : 'error',
            'message' => $response->successful()
                ? 'Night Combo created successfully.'
                : ($data['message'] ?? 'Unable to create Night Combo.')
        ]);

        return redirect()->route('products.index');
    }

    /**
     * Get active Night Combos.
     */
    public function getActiveNightCombos()
    {
        $response = Http::withToken(Session::get('token'))
            ->get(env('GATEWAY_URL') . '/api/product/night-combos/active');

        $data = $response->json();

        session()->flash('toast', [
            'type' => $response->successful() ? 'success' : 'error',
            'message' => $response->successful()
                ? 'Active Night Combos loaded successfully.'
                : ($data['message'] ?? 'Unable to load active Night Combos.')
        ]);

        return view('admin.product.night_combos.active', compact('data'));
    }
}
