<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class PaymentsController extends Controller
{
    /**
     * Get all payments
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            
            // Mock payment data
            $payments = [
                [
                    'id' => 1,
                    'user_id' => $user->id,
                    'invoice_id' => 1,
                    'amount' => 150.00,
                    'method' => 'wallet',
                    'status' => 'completed',
                    'transaction_id' => 'TXN001',
                    'gateway_response' => 'Success',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            ];
            
            // Apply filters
            if ($request->has('status')) {
                $payments = array_filter($payments, function($payment) use ($request) {
                    return $payment['status'] === $request->status;
                });
            }
            
            if ($request->has('method')) {
                $payments = array_filter($payments, function($payment) use ($request) {
                    return $payment['method'] === $request->method;
                });
            }
            
            return response()->json([
                'payments' => array_values($payments),
                'total' => count($payments),
                'page' => $request->get('page', 1),
                'per_page' => $request->get('per_page', 10)
            ]);
            
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve payments'], 500);
        }
    }
    
    /**
     * Get payment by ID
     */
    public function show(Request $request, $id): JsonResponse
    {
        try {
            $user = $request->user();
            
            $payment = [
                'id' => $id,
                'user_id' => $user->id,
                'invoice_id' => 1,
                'amount' => 150.00,
                'method' => 'wallet',
                'status' => 'completed',
                'transaction_id' => 'TXN001',
                'gateway_response' => 'Success',
                'processing_fee' => 2.50,
                'net_amount' => 147.50,
                'created_at' => now(),
                'updated_at' => now(),
            ];
            
            // Check access permission
            if ($user->role_id !== 1 && $payment['user_id'] !== $user->id) {
                return response()->json(['error' => 'Forbidden'], 403);
            }
            
            return response()->json($payment);
            
        } catch (\Exception $e) {
            return response()->json(['error' => 'Payment not found'], 404);
        }
    }
    
    /**
     * Process payment
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'invoice_id' => 'required|integer',
                'method' => 'required|string|in:wallet,cash,card,bank_transfer',
                'amount' => 'required|numeric|min:0.01',
                'payment_details' => 'nullable|array',
            ]);
            
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }
            
            $user = $request->user();
            
            // Process payment based on method
            $payment = [
                'id' => rand(1000, 9999),
                'user_id' => $user->id,
                'invoice_id' => $request->invoice_id,
                'amount' => $request->amount,
                'method' => $request->method,
                'status' => 'processing',
                'transaction_id' => 'TXN' . time(),
                'created_at' => now(),
                'updated_at' => now(),
            ];
            
            // Simulate payment processing
            switch ($request->method) {
                case 'wallet':
                    $payment['status'] = 'completed';
                    $payment['gateway_response'] = 'Wallet payment successful';
                    break;
                case 'cash':
                    $payment['status'] = 'pending';
                    $payment['gateway_response'] = 'Cash payment pending verification';
                    break;
                case 'card':
                    $payment['status'] = 'completed';
                    $payment['gateway_response'] = 'Card payment processed';
                    break;
                case 'bank_transfer':
                    $payment['status'] = 'pending';
                    $payment['gateway_response'] = 'Bank transfer initiated';
                    break;
            }
            
            return response()->json($payment, 201);
            
        } catch (\Exception $e) {
            return response()->json(['error' => 'Payment processing failed'], 500);
        }
    }
    
    /**
     * Update payment status
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'status' => 'required|string|in:pending,processing,completed,failed,refunded',
                'gateway_response' => 'nullable|string',
                'failure_reason' => 'nullable|string',
            ]);
            
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }
            
            $payment = [
                'id' => $id,
                'status' => $request->status,
                'gateway_response' => $request->gateway_response,
                'failure_reason' => $request->failure_reason,
                'updated_at' => now(),
            ];
            
            return response()->json($payment);
            
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update payment'], 500);
        }
    }
    
    /**
     * Refund payment
     */
    public function refund(Request $request, $id): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'refund_amount' => 'nullable|numeric|min:0.01',
                'reason' => 'required|string',
            ]);
            
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }
            
            $refund = [
                'id' => rand(1000, 9999),
                'payment_id' => $id,
                'amount' => $request->refund_amount ?? 150.00,
                'reason' => $request->reason,
                'status' => 'processing',
                'refund_transaction_id' => 'REF' . time(),
                'created_at' => now(),
            ];
            
            return response()->json($refund, 201);
            
        } catch (\Exception $e) {
            return response()->json(['error' => 'Refund processing failed'], 500);
        }
    }
    
    /**
     * Get payment methods
     */
    public function methods(): JsonResponse
    {
        try {
            $methods = [
                [
                    'id' => 'wallet',
                    'name' => 'Digital Wallet',
                    'description' => 'Pay using your account wallet balance',
                    'enabled' => true,
                    'processing_fee' => 0,
                ],
                [
                    'id' => 'cash',
                    'name' => 'Cash Payment',
                    'description' => 'Pay with cash at the counter',
                    'enabled' => true,
                    'processing_fee' => 0,
                ],
                [
                    'id' => 'card',
                    'name' => 'Credit/Debit Card',
                    'description' => 'Pay with your credit or debit card',
                    'enabled' => true,
                    'processing_fee' => 2.5,
                ],
                [
                    'id' => 'bank_transfer',
                    'name' => 'Bank Transfer',
                    'description' => 'Direct bank account transfer',
                    'enabled' => true,
                    'processing_fee' => 1.0,
                ],
            ];
            
            return response()->json($methods);
            
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve payment methods'], 500);
        }
    }
    
    /**
     * Get payment statistics
     */
    public function statistics(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            
            $stats = [
                'total_payments' => 50,
                'total_amount' => 7500.00,
                'successful_payments' => 45,
                'failed_payments' => 3,
                'pending_payments' => 2,
                'refunded_amount' => 300.00,
                'method_breakdown' => [
                    'wallet' => ['count' => 25, 'amount' => 3750.00],
                    'card' => ['count' => 15, 'amount' => 2250.00],
                    'cash' => ['count' => 8, 'amount' => 1200.00],
                    'bank_transfer' => ['count' => 2, 'amount' => 300.00],
                ],
                'daily_revenue' => [
                    date('Y-m-d', strtotime('-6 days')) => 120.00,
                    date('Y-m-d', strtotime('-5 days')) => 180.00,
                    date('Y-m-d', strtotime('-4 days')) => 150.00,
                    date('Y-m-d', strtotime('-3 days')) => 200.00,
                    date('Y-m-d', strtotime('-2 days')) => 175.00,
                    date('Y-m-d', strtotime('-1 days')) => 190.00,
                    date('Y-m-d') => 85.00,
                ]
            ];
            
            if ($user->role_id !== 1) {
                // Filter for regular users
                $stats['total_payments'] = 8;
                $stats['total_amount'] = 1200.00;
            }
            
            return response()->json($stats);
            
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve statistics'], 500);
        }
    }
}