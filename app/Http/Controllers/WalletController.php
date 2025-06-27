<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function statistics(Request $request)
    {
        $token = session('token');
        if (!$token) return redirect()->route('login')->withErrors(['Vui lòng đăng nhập lại!']);
        
        $gateway = env('GATEWAY_URL', 'http://localhost:8000');
        
        // Lấy số dư
        $balance = 0;
        $transactions = [];
        $error = null;
        
        try {
            $balanceRes = \Illuminate\Support\Facades\Http::withToken($token)->get($gateway . '/api/wallet/statistics');
            
            if ($balanceRes->status() === 401 || $balanceRes->status() === 403) {
                // Nếu không phải admin, chuyển sang payment
                return redirect()->route('payment.index')->with('info', 'Bạn không có quyền truy cập ví. Vui lòng sử dụng chức năng thanh toán.');
            }
            
            if ($balanceRes->successful()) {
                $balance = $balanceRes->json('balance') ?? 0;
            } else {
                $error = $balanceRes->json('message') ?? 'Không lấy được số dư ví!';
            }
            
            // Lấy lịch sử giao dịch
            $txRes = \Illuminate\Support\Facades\Http::withToken($token)->get($gateway . '/api/wallet/transactions/filter');
            
            if ($txRes->status() === 401 || $txRes->status() === 403) {
                // Nếu không phải admin, chuyển sang payment
                return redirect()->route('payment.index')->with('info', 'Bạn không có quyền truy cập ví. Vui lòng sử dụng chức năng thanh toán.');
            }
            
            if ($txRes->successful()) {
                $transactions = $txRes->json('transactions') ?? [];
            } else {
                $error = $txRes->json('message') ?? $error;
            }
        } catch (\Exception $e) {
            $error = 'Có lỗi xảy ra khi kết nối đến server!';
        }
        
        return view('wallet', compact('balance', 'transactions', 'error'));
    }

    public function payment(Request $request)
    {
        $token = session('token');
        if (!$token) return redirect()->route('login')->withErrors(['Vui lòng đăng nhập lại!']);
        
        $gateway = env('GATEWAY_URL', 'http://localhost:8000');
        $amount = $request->input('amount');
        
        try {
            $res = \Illuminate\Support\Facades\Http::withToken($token)->post($gateway . '/api/wallet/payment', [
                'amount' => $amount,
            ]);
            
            if ($res->status() === 401 || $res->status() === 403) {
                return redirect()->route('payment.index')->with('info', 'Bạn không có quyền thực hiện thanh toán từ ví. Vui lòng sử dụng chức năng thanh toán trực tuyến.');
            }
            
            if ($res->failed() && ($res->json('message') === 'Không đủ tiền')) {
                // Lỗi không đủ tiền
                $balanceRes = \Illuminate\Support\Facades\Http::withToken($token)->get($gateway . '/api/wallet/statistics');
                $balance = $balanceRes->successful() ? $balanceRes->json('balance') : 0;
                $txRes = \Illuminate\Support\Facades\Http::withToken($token)->get($gateway . '/api/wallet/transactions/filter');
                $transactions = $txRes->successful() ? $txRes->json('transactions') : [];
                $error = 'Không đủ tiền để thanh toán!';
                return view('wallet', compact('balance', 'transactions', 'error'));
            }
            
            // Nếu thành công, trừ tiền và reload lại wallet
            return redirect()->route('wallet')->with('success', 'Thanh toán thành công!');
        } catch (\Exception $e) {
            return redirect()->route('payment.index')->with('error', 'Có lỗi xảy ra khi thực hiện thanh toán. Vui lòng thử lại.');
        }
    }

    public function deposit(Request $request)
    {
        $token = session('token');
        if (!$token) return redirect()->route('login')->withErrors(['Vui lòng đăng nhập lại!']);
        
        $gateway = env('GATEWAY_URL', 'http://localhost:8000');
        $amount = $request->input('amount');
        
        try {
            $res = \Illuminate\Support\Facades\Http::withToken($token)->post($gateway . '/api/wallet/deposit', [
                'amount' => $amount,
            ]);
            
            if ($res->status() === 401 || $res->status() === 403) {
                return redirect()->route('payment.index')->with('info', 'Bạn không có quyền nạp tiền vào ví. Vui lòng sử dụng chức năng thanh toán trực tuyến.');
            }
            
            if ($res->successful()) {
                return redirect()->route('wallet')->with('success', 'Nạp tiền thành công!');
            }
            
            $error = $res->json('message') ?? 'Nạp tiền thất bại!';
            $balanceRes = \Illuminate\Support\Facades\Http::withToken($token)->get($gateway . '/api/wallet/statistics');
            $balance = $balanceRes->successful() ? $balanceRes->json('balance') : 0;
            $txRes = \Illuminate\Support\Facades\Http::withToken($token)->get($gateway . '/api/wallet/transactions/filter');
            $transactions = $txRes->successful() ? $txRes->json('transactions') : [];
            return view('wallet', compact('balance', 'transactions', 'error'));
        } catch (\Exception $e) {
            return redirect()->route('payment.index')->with('error', 'Có lỗi xảy ra khi nạp tiền. Vui lòng thử lại.');
        }
    }

    public function filterTransactions(Request $request) 
    {
        $token = session('token');
        if (!$token) {
            return response()->json(['error' => 'Token không hợp lệ'], 401);
        }
        
        $gateway = env('GATEWAY_URL', 'http://localhost:8000');
        
        try {
            $params = $request->all();
            $res = \Illuminate\Support\Facades\Http::withToken($token)->get($gateway . '/api/wallet/transactions/filter', $params);
            
            if ($res->status() === 401 || $res->status() === 403) {
                return response()->json(['error' => 'Không có quyền truy cập'], 403);
            }
            
            if ($res->successful()) {
                return response()->json($res->json());
            }
            
            return response()->json(['error' => $res->json('message') ?? 'Lỗi không xác định'], $res->status());
        } catch (\Exception $e) {
            return response()->json(['error' => 'Có lỗi xảy ra khi kết nối đến server'], 500);
        }
    }

    public function getBalance()
    {
        $token = session('token');
        if (!$token) {
            return response()->json(['error' => 'Token không hợp lệ'], 401);
        }
        
        $gateway = env('GATEWAY_URL', 'http://localhost:8000');
        
        try {
            $balanceRes = \Illuminate\Support\Facades\Http::withToken($token)->get($gateway . '/api/wallet/statistics');
            
            if ($balanceRes->status() === 401 || $balanceRes->status() === 403) {
                return response()->json(['error' => 'Không có quyền truy cập'], 403);
            }
            
            if ($balanceRes->successful()) {
                $balance = $balanceRes->json('balance') ?? 0;
                return response()->json(['balance' => $balance]);
            }
            
            return response()->json(['error' => $balanceRes->json('message') ?? 'Lỗi không xác định'], $balanceRes->status());
        } catch (\Exception $e) {
            return response()->json(['error' => 'Có lỗi xảy ra khi kết nối đến server'], 500);
        }
    }
} 