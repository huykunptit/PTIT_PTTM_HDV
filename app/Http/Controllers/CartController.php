<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    private $gatewayUrl = 'https://f628-1-54-69-3.ngrok-free.app';

    /**
     * Display cart page (now handled by JavaScript)
     */
    public function index()
    {
        return view('shop.cart');
    }

    /**
     * Create order from cart (called when user clicks "Thanh toán")
     */
    public function createOrder(Request $request)
    {
        try {
            $token = session('token');
            
            if (!$token) {
                return response()->json(['error' => 'Token không hợp lệ'], 401);
            }

            // Validate request
            $request->validate([
                'items' => 'required|array|min:1',
                'items.*.product_id' => 'required|integer',
                'items.*.quantity' => 'required|integer|min:1',
                'items.*.price' => 'required|numeric|min:0',
                'total_amount' => 'required|numeric|min:0',
                'promotion_code' => 'nullable|string',
                'discount_amount' => 'nullable|numeric|min:0'
            ]);

            // Prepare order data
            $orderData = [
                'items' => $request->items,
                'total_amount' => $request->total_amount,
                'promotion_code' => $request->promotion_code,
                'discount_amount' => $request->discount_amount ?? 0,
                'notes' => $request->notes ?? ''
            ];

            Log::info('Creating order:', $orderData);

            // Call API to create order
            $response = Http::withHeaders(['Authorization' => 'Bearer ' . $token])
                ->post($this->gatewayUrl . '/api/orders', $orderData);

            if ($response->successful()) {
                $order = $response->json();
                Log::info('Order created successfully:', $order);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Đặt hàng thành công!',
                    'order' => $order
                ]);
            } else {
                $errorData = $response->json();
                Log::error('Failed to create order:', $errorData ?: []);
                
                return response()->json([
                    'success' => false,
                    'message' => $errorData['error'] ?? 'Lỗi khi tạo đơn hàng'
                ], $response->status());
            }

        } catch (\Exception $e) {
            Log::error('Exception creating order:', ['error' => $e->getMessage()]);
            
            return response()->json([
                'success' => false,
                'message' => 'Lỗi hệ thống khi tạo đơn hàng'
            ], 500);
        }
    }

    /**
     * Get order history
     */
    public function orders()
    {
        try {
            $token = session('token');
            
            if (!$token) {
                return redirect()->route('login');
            }

            $response = Http::withHeaders(['Authorization' => 'Bearer ' . $token])
                ->get($this->gatewayUrl . '/api/orders');

            if ($response->successful()) {
                $orders = $response->json();
                return view('shop.orders', compact('orders'));
            } else {
                Log::error('Failed to load orders:', $response->json() ?: []);
                return view('shop.orders', ['orders' => []]);
            }

        } catch (\Exception $e) {
            Log::error('Exception loading orders:', ['error' => $e->getMessage()]);
            return view('shop.orders', ['orders' => []]);
        }
    }

    /**
     * Get order details
     */
    public function orderDetails($orderId)
    {
        try {
            $token = session('token');
            
            if (!$token) {
                return redirect()->route('login');
            }

            $response = Http::withHeaders(['Authorization' => 'Bearer ' . $token])
                ->get($this->gatewayUrl . '/api/orders/' . $orderId);

            if ($response->successful()) {
                $order = $response->json();
                return view('shop.order-details', compact('order'));
            } else {
                Log::error('Failed to load order details:', $response->json() ?: []);
                return redirect()->route('shop.orders')->with('error', 'Không thể tải chi tiết đơn hàng');
            }

        } catch (\Exception $e) {
            Log::error('Exception loading order details:', ['error' => $e->getMessage()]);
            return redirect()->route('shop.orders')->with('error', 'Lỗi hệ thống');
        }
    }

    /**
     * Cancel order
     */
    public function cancelOrder($orderId)
    {
        try {
            $token = session('token');
            
            if (!$token) {
                return response()->json(['error' => 'Token không hợp lệ'], 401);
            }

            $response = Http::withHeaders(['Authorization' => 'Bearer ' . $token])
                ->put($this->gatewayUrl . '/api/orders/' . $orderId . '/cancel');

            if ($response->successful()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Đã hủy đơn hàng thành công'
                ]);
            } else {
                $errorData = $response->json();
                return response()->json([
                    'success' => false,
                    'message' => $errorData['error'] ?? 'Lỗi khi hủy đơn hàng'
                ], $response->status());
            }

        } catch (\Exception $e) {
            Log::error('Exception canceling order:', ['error' => $e->getMessage()]);
            
            return response()->json([
                'success' => false,
                'message' => 'Lỗi hệ thống khi hủy đơn hàng'
            ], 500);
        }
    }
} 