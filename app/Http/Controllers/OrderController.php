<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class OrderController extends Controller
{
    private $gatewayUrl = 'https://f628-1-54-69-3.ngrok-free.app';

    public function store(Request $request)
    {
        try {
            $token = session('token');
            $user = session('user');

            // Validate request
            $request->validate([
                'full_name' => 'required|string|max:255',
                'phone' => 'required|string|max:20',
                'address' => 'required|string',
                'payment_method' => 'required|string|in:cash,bank_transfer,momo,zalopay',
                'items' => 'required|array|min:1',
                'total_amount' => 'required|numeric|min:0',
            ]);

            // Prepare order data
            $orderData = [
                'user_id' => $user['id'],
                'full_name' => $request->full_name,
                'phone' => $request->phone,
                'address' => $request->address,
                'notes' => $request->notes,
                'payment_method' => $request->payment_method,
                'items' => $request->items,
                'total_amount' => $request->total_amount,
                'status' => 'pending'
            ];

            // Call API to create order
            $response = Http::withHeaders(['Authorization' => 'Bearer ' . $token])
                ->post($this->gatewayUrl . '/api/orders', $orderData);

            if ($response->successful()) {
                // Clear cart after successful order
                Http::withHeaders(['Authorization' => 'Bearer ' . $token])
                    ->delete($this->gatewayUrl . '/api/cart/clear');

                return response()->json([
                    'success' => true,
                    'message' => 'Đặt hàng thành công!',
                    'order' => $response->json()
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Lỗi khi đặt hàng: ' . ($response->json()['error'] ?? 'Unknown error')
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi server: ' . $e->getMessage()
            ], 500);
        }
    }

    public function index()
    {
        try {
            $token = session('token');
            $user = session('user');

            $response = Http::withHeaders(['Authorization' => 'Bearer ' . $token])
                ->get($this->gatewayUrl . '/api/orders', [
                    'user_id' => $user['id'],
                    'page' => request('page', 1),
                    'per_page' => request('per_page', 10),
                ]);

            if (request()->expectsJson()) {
                return response()->json($response->successful() ? $response->json() : []);
            }

            $orders = $response->successful() ? $response->json() : [];
            return view('shop.orders', compact('orders'));
        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return response()->json([]);
            }
            return view('shop.orders', ['orders' => []]);
        }
    }

    public function indexView()
    {
        try {
            $token = session('token');
            $user = session('user');

            $response = Http::withHeaders(['Authorization' => 'Bearer ' . $token])
                ->get($this->gatewayUrl . '/api/orders', [
                    'user_id' => $user['id'],
                    'page' => request('page', 1),
                    'per_page' => request('per_page', 10),
                ]);

            $orders = $response->successful() ? $response->json() : [];
            return view('shop.orders', compact('orders'));
        } catch (\Exception $e) {
            return view('shop.orders', ['orders' => []]);
        }
    }

    public function show($id)
    {
        try {
            $token = session('token');
            $user = session('user');

            $response = Http::withHeaders(['Authorization' => 'Bearer ' . $token])
                ->get($this->gatewayUrl . '/api/orders/' . $id);

            if ($response->successful()) {
                $order = $response->json();
                return view('shop.order-detail', compact('order'));
            } else {
                return redirect()->route('shop.orders')->with('error', 'Không tìm thấy đơn hàng');
            }
        } catch (\Exception $e) {
            return redirect()->route('shop.orders')->with('error', 'Lỗi khi tải đơn hàng');
        }
    }

    public function cancel($id)
    {
        try {
            $token = session('token');

            $response = Http::withHeaders(['Authorization' => 'Bearer ' . $token])
                ->put($this->gatewayUrl . '/api/orders/' . $id . '/cancel');

            if ($response->successful()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Đã hủy đơn hàng thành công'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Lỗi khi hủy đơn hàng: ' . ($response->json()['error'] ?? 'Unknown error')
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi server: ' . $e->getMessage()
            ], 500);
        }
    }
} 