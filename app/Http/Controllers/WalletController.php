<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Models\User;
use Carbon\Carbon;

class WalletController extends Controller
{
    /**
     * Get wallet balance and information
     */
    public function getWallet(): JsonResponse
    {
        try {
            $user = Auth::user();
            
            $wallet = Wallet::firstOrCreate(
                ['user_id' => $user->id],
                ['balance' => 0.00]
            );

            return response()->json([
                'wallet' => [
                    'id' => $wallet->id,
                    'user_id' => $wallet->user_id,
                    'balance' => $wallet->balance,
                    'currency' => 'VND',
                    'created_at' => $wallet->created_at,
                    'updated_at' => $wallet->updated_at
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to get wallet information'
            ], 500);
        }
    }

    /**
     * Add money to wallet (deposit)
     */
    public function deposit(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:10000|max:10000000', // Min 10k, Max 10M VND
            'description' => 'nullable|string|max:255',
            'payment_method' => 'required|string|in:bank_transfer,momo,zalopay,cash'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Invalid input data',
                'details' => $validator->errors()
            ], 400);
        }

        try {
            $user = Auth::user();
            $amount = $request->amount;
            $description = $request->get('description', 'Wallet deposit');

            $wallet = Wallet::firstOrCreate(
                ['user_id' => $user->id],
                ['balance' => 0.00]
            );

            // Create transaction record
            $transaction = WalletTransaction::create([
                'wallet_id' => $wallet->id,
                'amount' => $amount,
                'type' => 'deposit',
                'status' => 'pending', // Would be updated by payment gateway
                'description' => $description,
                'metadata' => [
                    'payment_method' => $request->payment_method,
                    'user_id' => $user->id
                ]
            ]);

            // For demo purposes, auto-complete the transaction
            // In real app, this would be handled by payment gateway webhook
            $transaction->status = 'completed';
            $transaction->save();

            // Update wallet balance
            $wallet->balance += $amount;
            $wallet->save();

            return response()->json([
                'message' => 'Deposit successful',
                'transaction' => $transaction,
                'new_balance' => $wallet->balance
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to process deposit'
            ], 500);
        }
    }

    /**
     * Withdraw money from wallet
     */
    public function withdraw(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:50000', // Min 50k VND
            'description' => 'nullable|string|max:255',
            'bank_account' => 'required|string',
            'bank_name' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Invalid input data',
                'details' => $validator->errors()
            ], 400);
        }

        try {
            $user = Auth::user();
            $amount = $request->amount;

            $wallet = Wallet::where('user_id', $user->id)->first();

            if (!$wallet) {
                return response()->json([
                    'error' => 'Wallet not found'
                ], 404);
            }

            if ($wallet->balance < $amount) {
                return response()->json([
                    'error' => 'Insufficient balance'
                ], 400);
            }

            // Create withdrawal transaction
            $transaction = WalletTransaction::create([
                'wallet_id' => $wallet->id,
                'amount' => -$amount, // Negative for withdrawal
                'type' => 'withdrawal',
                'status' => 'pending',
                'description' => $request->get('description', 'Wallet withdrawal'),
                'metadata' => [
                    'bank_account' => $request->bank_account,
                    'bank_name' => $request->bank_name,
                    'user_id' => $user->id
                ]
            ]);

            // Update wallet balance
            $wallet->balance -= $amount;
            $wallet->save();

            return response()->json([
                'message' => 'Withdrawal request submitted successfully',
                'transaction' => $transaction,
                'new_balance' => $wallet->balance
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to process withdrawal'
            ], 500);
        }
    }

    /**
     * Make payment from wallet
     */
    public function makePayment(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:1',
            'description' => 'required|string|max:255',
            'reference_id' => 'nullable|string',
            'metadata' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Invalid input data',
                'details' => $validator->errors()
            ], 400);
        }

        try {
            $user = Auth::user();
            $amount = $request->amount;

            $wallet = Wallet::where('user_id', $user->id)->first();

            if (!$wallet) {
                return response()->json([
                    'error' => 'Wallet not found'
                ], 404);
            }

            if ($wallet->balance < $amount) {
                return response()->json([
                    'error' => 'Insufficient balance'
                ], 400);
            }

            // Create payment transaction
            $transaction = WalletTransaction::create([
                'wallet_id' => $wallet->id,
                'amount' => -$amount, // Negative for payment
                'type' => 'payment',
                'status' => 'completed',
                'description' => $request->description,
                'reference_id' => $request->reference_id,
                'metadata' => $request->metadata ?? []
            ]);

            // Update wallet balance
            $wallet->balance -= $amount;
            $wallet->save();

            return response()->json([
                'message' => 'Payment successful',
                'transaction' => $transaction,
                'new_balance' => $wallet->balance
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to process payment'
            ], 500);
        }
    }

    /**
     * Get wallet transaction history
     */
    public function getTransactions(Request $request): JsonResponse
    {
        try {
            $user = Auth::user();
            $page = $request->get('page', 1);
            $perPage = $request->get('per_page', 10);
            $type = $request->get('type');
            $status = $request->get('status');

            $wallet = Wallet::where('user_id', $user->id)->first();

            if (!$wallet) {
                return response()->json([
                    'transactions' => [],
                    'total' => 0,
                    'current_page' => 1,
                    'per_page' => $perPage,
                    'last_page' => 1
                ]);
            }

            $query = WalletTransaction::where('wallet_id', $wallet->id);

            if ($type) {
                $query->where('type', $type);
            }

            if ($status) {
                $query->where('status', $status);
            }

            $transactions = $query->orderBy('created_at', 'desc')
                ->paginate($perPage, ['*'], 'page', $page);

            return response()->json([
                'transactions' => $transactions->items(),
                'total' => $transactions->total(),
                'current_page' => $transactions->currentPage(),
                'per_page' => $transactions->perPage(),
                'last_page' => $transactions->lastPage()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to get transaction history'
            ], 500);
        }
    }

    /**
     * Get wallet statistics
     */
    public function getStatistics(Request $request): JsonResponse
    {
        try {
            $user = Auth::user();
            $wallet = Wallet::where('user_id', $user->id)->first();

            if (!$wallet) {
                return response()->json([
                    'error' => 'Wallet not found'
                ], 404);
            }

            // Get transaction statistics
            $transactions = WalletTransaction::where('wallet_id', $wallet->id)->get();
            
            $totalTransactions = $transactions->count();
            $totalAmount = $transactions->where('status', 'completed')->sum('amount');
            $averageAmount = $totalTransactions > 0 ? $totalAmount / $totalTransactions : 0;

            // Daily transactions for last 30 days
            $dailyTransactions = [];
            for ($i = 29; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i)->format('Y-m-d');
                $dayTransactions = $transactions->filter(function ($transaction) use ($date) {
                    return Carbon::parse($transaction->created_at)->format('Y-m-d') === $date;
                });

                $dailyTransactions[$date] = [
                    'count' => $dayTransactions->count(),
                    'amount' => $dayTransactions->sum('amount')
                ];
            }

            // Transaction categories
            $transactionCategories = [];
            foreach (['deposit', 'withdrawal', 'payment', 'refund'] as $type) {
                $typeTransactions = $transactions->where('type', $type);
                $transactionCategories[$type] = [
                    'count' => $typeTransactions->count(),
                    'amount' => $typeTransactions->sum('amount')
                ];
            }

            return response()->json([
                'total_transactions' => $totalTransactions,
                'total_amount' => $totalAmount,
                'average_amount' => $averageAmount,
                'daily_transactions' => $dailyTransactions,
                'transaction_categories' => $transactionCategories,
                'current_balance' => $wallet->balance
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to get wallet statistics'
            ], 500);
        }
    }

    /**
     * Get filtered transactions
     */
    public function getFilteredTransactions(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'transaction_type' => 'nullable|string|in:deposit,withdrawal,payment,refund',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'min_amount' => 'nullable|numeric',
            'max_amount' => 'nullable|numeric|gte:min_amount',
            'page' => 'nullable|integer|min:1',
            'per_page' => 'nullable|integer|min:1|max:100'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Invalid filter parameters',
                'details' => $validator->errors()
            ], 400);
        }

        try {
            $user = Auth::user();
            $wallet = Wallet::where('user_id', $user->id)->first();

            if (!$wallet) {
                return response()->json([
                    'transactions' => [],
                    'total' => 0,
                    'page' => 1,
                    'per_page' => 10,
                    'total_pages' => 1
                ]);
            }

            $query = WalletTransaction::where('wallet_id', $wallet->id);

            // Apply filters
            if ($request->transaction_type) {
                $query->where('type', $request->transaction_type);
            }

            if ($request->start_date) {
                $query->where('created_at', '>=', $request->start_date);
            }

            if ($request->end_date) {
                $query->where('created_at', '<=', $request->end_date . ' 23:59:59');
            }

            if ($request->min_amount) {
                $query->where('amount', '>=', $request->min_amount);
            }

            if ($request->max_amount) {
                $query->where('amount', '<=', $request->max_amount);
            }

            $page = $request->get('page', 1);
            $perPage = $request->get('per_page', 10);

            $transactions = $query->orderBy('created_at', 'desc')
                ->paginate($perPage, ['*'], 'page', $page);

            return response()->json([
                'transactions' => $transactions->items(),
                'total' => $transactions->total(),
                'page' => $transactions->currentPage(),
                'per_page' => $transactions->perPage(),
                'total_pages' => $transactions->lastPage()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to get filtered transactions'
            ], 500);
        }
    }
}