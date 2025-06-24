<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class OrderController extends Controller
{
    private $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('app.api_base_url', 'http://localhost:8000');
    }

    /**
     * Display order management page (Admin)
     */
    public function index()
    {
        if (auth()->user()->role_id !== 1) {
            abort(403);
        }

        return view('admin.orders.index');
    }

    /**
     * Get orders list
     */
    public function getOrders(Request $request)
    {
        try {
            $params = $request->only([
                'user_id', 'status', 'date_from', 'date_to',
                'page', 'per_page', 'sort_by', 'sort_order'
            ]);

            $response = Http::withToken(session('auth_token'))
                ->get($this->baseUrl . '/api/orders', $params);

            if ($response->successful()) {
                return response()->json($response->json());
            }

            return response()->json(['error' => 'Failed to fetch orders'], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Create order from cart
     */
    public function createFromCart(Request $request)
    {
        $request->validate([
            'delivery_address' => 'required|string',
            'payment_method' => 'required|string|in:cash,card,wallet',
            'notes' => 'nullable|string'
        ]);

        try {
            $response = Http::withToken(session('auth_token'))
                ->post($this->baseUrl . '/api/orders/create-from-cart', $request->all());

            if ($response->successful()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Order created successfully',
                    'data' => $response->json()
                ]);
            }

            return response()->json([
                'success' => false,
                'error' => $response->json()['error'] ?? 'Failed to create order'
            ], $response->status());
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get order details
     */
    public function show($id)
    {
        try {
            $response = Http::withToken(session('auth_token'))
                ->get($this->baseUrl . "/api/orders/{$id}");

            if ($response->successful()) {
                $order = $response->json();
                return view('orders.detail', compact('order'));
            }

            return redirect()->back()->with('error', 'Order not found');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Update order status (Admin only)
     */
    public function updateStatus(Request $request, $id)
    {
        if (auth()->user()->role_id !== 1) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'status' => 'required|string|in:pending,confirmed,preparing,delivering,completed,cancelled'
        ]);

        try {
            $response = Http::withToken(session('auth_token'))
                ->put($this->baseUrl . "/api/orders/{$id}/status", $request->all());

            if ($response->successful()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Order status updated successfully',
                    'data' => $response->json()
                ]);
            }

            return response()->json([
                'success' => false,
                'error' => $response->json()['error'] ?? 'Failed to update order status'
            ], $response->status());
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cancel order
     */
    public function cancel($id)
    {
        try {
            $response = Http::withToken(session('auth_token'))
                ->put($this->baseUrl . "/api/orders/{$id}/cancel");

            if ($response->successful()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Order cancelled successfully'
                ]);
            }

            return response()->json([
                'success' => false,
                'error' => $response->json()['error'] ?? 'Failed to cancel order'
            ], $response->status());
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get order statistics (Admin only)
     */
    public function getStatistics(Request $request)
    {
        if (auth()->user()->role_id !== 1) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        try {
            $params = $request->only(['date_from', 'date_to', 'period']);
            
            $response = Http::withToken(session('auth_token'))
                ->get($this->baseUrl . '/api/orders/statistics', $params);

            if ($response->successful()) {
                return response()->json($response->json());
            }

            return response()->json(['error' => 'Failed to fetch statistics'], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}