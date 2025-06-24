<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class InvoicesController extends Controller
{
    /**
     * Get all invoices
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            
            // Admin can see all invoices, users can only see their own
            $query = collect(); // Mock data - replace with actual model
            
            if ($user->role_id !== 1) { // Not admin
                $query = $query->where('user_id', $user->id);
            }
            
            // Apply filters
            if ($request->has('status')) {
                $query = $query->where('status', $request->status);
            }
            
            if ($request->has('start_date')) {
                $query = $query->where('created_at', '>=', $request->start_date);
            }
            
            if ($request->has('end_date')) {
                $query = $query->where('created_at', '<=', $request->end_date);
            }
            
            // Pagination
            $page = $request->get('page', 1);
            $perPage = $request->get('per_page', 10);
            
            $invoices = [
                [
                    'id' => 1,
                    'user_id' => $user->id,
                    'total_amount' => 150.00,
                    'status' => 'paid',
                    'created_at' => now(),
                    'updated_at' => now(),
                    'items' => [
                        [
                            'id' => 1,
                            'product_name' => 'Gaming Session',
                            'quantity' => 2,
                            'price' => 75.00,
                            'subtotal' => 150.00
                        ]
                    ]
                ]
            ];
            
            return response()->json([
                'invoices' => $invoices,
                'total' => count($invoices),
                'page' => $page,
                'per_page' => $perPage,
                'total_pages' => 1
            ]);
            
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve invoices'], 500);
        }
    }
    
    /**
     * Get invoice by ID
     */
    public function show(Request $request, $id): JsonResponse
    {
        try {
            $user = $request->user();
            
            // Mock invoice data
            $invoice = [
                'id' => $id,
                'user_id' => $user->id,
                'total_amount' => 150.00,
                'tax_amount' => 15.00,
                'subtotal' => 135.00,
                'status' => 'paid',
                'payment_method' => 'wallet',
                'created_at' => now(),
                'updated_at' => now(),
                'items' => [
                    [
                        'id' => 1,
                        'product_name' => 'Gaming Session',
                        'quantity' => 2,
                        'price' => 67.50,
                        'subtotal' => 135.00
                    ]
                ]
            ];
            
            // Check if user can access this invoice
            if ($user->role_id !== 1 && $invoice['user_id'] !== $user->id) {
                return response()->json(['error' => 'Forbidden'], 403);
            }
            
            return response()->json($invoice);
            
        } catch (\Exception $e) {
            return response()->json(['error' => 'Invoice not found'], 404);
        }
    }
    
    /**
     * Create new invoice
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'cart_id' => 'required|integer',
                'payment_method' => 'required|string|in:wallet,cash,card',
                'billing_address' => 'required|string',
            ]);
            
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }
            
            $user = $request->user();
            
            // Mock invoice creation
            $invoice = [
                'id' => rand(1000, 9999),
                'user_id' => $user->id,
                'cart_id' => $request->cart_id,
                'total_amount' => 150.00,
                'tax_amount' => 15.00,
                'subtotal' => 135.00,
                'status' => 'pending',
                'payment_method' => $request->payment_method,
                'billing_address' => $request->billing_address,
                'created_at' => now(),
                'updated_at' => now(),
            ];
            
            return response()->json($invoice, 201);
            
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create invoice'], 500);
        }
    }
    
    /**
     * Update invoice status
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'status' => 'required|string|in:pending,paid,cancelled,refunded',
                'payment_reference' => 'nullable|string',
            ]);
            
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }
            
            // Mock invoice update
            $invoice = [
                'id' => $id,
                'status' => $request->status,
                'payment_reference' => $request->payment_reference,
                'updated_at' => now(),
            ];
            
            return response()->json($invoice);
            
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update invoice'], 500);
        }
    }
    
    /**
     * Delete invoice
     */
    public function destroy($id): JsonResponse
    {
        try {
            // Mock invoice deletion
            return response()->json(['message' => 'Invoice deleted successfully']);
            
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete invoice'], 500);
        }
    }
    
    /**
     * Get invoice statistics
     */
    public function statistics(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            
            // Mock statistics
            $stats = [
                'total_invoices' => 25,
                'total_amount' => 3750.00,
                'paid_invoices' => 20,
                'pending_invoices' => 3,
                'cancelled_invoices' => 2,
                'monthly_revenue' => [
                    '2024-01' => 1200.00,
                    '2024-02' => 1350.00,
                    '2024-03' => 1200.00,
                ],
                'top_products' => [
                    ['name' => 'Gaming Session', 'count' => 15, 'revenue' => 2250.00],
                    ['name' => 'Snacks', 'count' => 10, 'revenue' => 150.00],
                ]
            ];
            
            if ($user->role_id !== 1) {
                // Filter stats for regular users
                $stats['total_invoices'] = 5;
                $stats['total_amount'] = 750.00;
            }
            
            return response()->json($stats);
            
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve statistics'], 500);
        }
    }
}