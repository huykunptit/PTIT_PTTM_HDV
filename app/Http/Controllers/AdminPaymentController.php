<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Services\VNPayService;

class AdminPaymentController extends Controller
{
    private $gatewayUrl;

    public function __construct()
    {
        $this->gatewayUrl = env('GATEWAY_URL', 'http://localhost:8000');
    }

    /**
     * Helper function để xử lý response từ API users
     */
    private function parseUsersResponse($response)
    {
        if (!$response->successful()) {
            return [];
        }

        $data = $response->json();
        
        // Debug logging
        Log::info('Users API Response:', [
            'status' => $response->status(),
            'data' => $data,
            'data_type' => gettype($data)
        ]);

        // Xử lý các trường hợp response khác nhau
        if (is_array($data)) {
            if (isset($data['users']) && is_array($data['users'])) {
                return $data['users'];
            } elseif (isset($data[0]) && is_array($data[0])) {
                // Nếu response trực tiếp là array của users
                return $data;
            } elseif (isset($data['data']) && is_array($data['data'])) {
                return $data['data'];
            }
        }

        return [];
    }

    public function index()
    {
        $token = session('token');
        if (!$token) {
            return redirect()->route('login')->withErrors(['Vui lòng đăng nhập lại!']);
        }

        // Lấy danh sách users để admin có thể nạp tiền cho
        try {
            $usersResponse = Http::withToken($token)->get($this->gatewayUrl . '/api/account/admin/users/list');
            $users = $this->parseUsersResponse($usersResponse);

            // Lấy thống kê tổng quan
            $statsResponse = Http::withToken($token)->get($this->gatewayUrl . '/api/wallet/admin/statistics');
            $stats = $statsResponse->successful() ? $statsResponse->json() : [
                'total_balance' => 0,
                'total_users' => 0,
                'total_transactions' => 0,
                'today_transactions' => 0
            ];
            // dd($users);
            return view('admin.payment.index', compact('users', 'stats'));
        } catch (\Exception $e) {
            Log::error('AdminPaymentController index error: ' . $e->getMessage());
            return view('admin.payment.index', [
                'users' => [],
                'stats' => [
                    'total_balance' => 0,
                    'total_users' => 0,
                    'total_transactions' => 0,
                    'today_transactions' => 0
                ]
            ]);
        }
    }

    public function depositForUser(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'amount' => 'required|numeric|min:1000',
            'description' => 'nullable|string|max:255',
            'admin_note' => 'nullable|string|max:255',
        ]);
    
        $token = session('token');
        $admin = session('user');
    
        if (!$token || !$admin) {
            return redirect()->route('login')->withErrors(['Vui lòng đăng nhập lại!']);
        }
    
        // Tạo payload khớp với FastAPI yêu cầu
        $payload = [
            'user_id' => (int) $request->user_id,
            'amount' => round((float) $request->amount, 2),
            'transaction_type' => 'deposit',
            // 'description' => $request->description ?? 'Nạp tiền từ admin',
            'reference_id' => 'user_' . $request->user_id . '_admin_topup_' . time(),
            // 'reference_type' => 'MANUAL',
        ];
    
        try {
            $response = \Illuminate\Support\Facades\Http::withToken($token)
                ->post(env('GATEWAY_URL', 'http://localhost:8000') . '/api/wallets/transactions', $payload);
    
            if ($response->successful()) {
                $data = $response->json();
                dd($data);
                return redirect()->back()->with('success', 'Nạp tiền thành công cho user ID: ' . $request->user_id);
            } else {
                dd($response->json());
                $errors = $response->json();
                return redirect()->back()->withErrors([
                    'Lỗi từ hệ thống thanh toán: ' . ($errors['detail'] ?? json_encode($errors))
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('Lỗi nạp tiền admin: ' . $e->getMessage());
            return redirect()->back()->withErrors(['Đã xảy ra lỗi không mong muốn khi nạp tiền.']);
        }
    }
    


    public function withdrawFromUser(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'amount' => 'required|numeric|min:1000',
            'description' => 'required|string|max:255',
        ]);

        $token = session('token');
        if (!$token) {
            return redirect()->route('login')->withErrors(['Vui lòng đăng nhập lại!']);
        }

        try {
            $response = Http::withToken($token)->post($this->gatewayUrl . '/api/wallet/admin/withdraw', [
                'user_id' => $request->user_id,
                'amount' => $request->amount,
                'description' => $request->description,
                'admin_note' => $request->admin_note ?? '',
            ]);

            if ($response->successful()) {
                return redirect()->route('admin.payment.index')->with('success', 'Rút tiền thành công từ user ID: ' . $request->user_id);
            } else {
                $error = $response->json('message') ?? 'Rút tiền thất bại!';
                return redirect()->route('admin.payment.index')->with('error', $error);
            }
        } catch (\Exception $e) {
            Log::error('Admin withdraw error: ' . $e->getMessage());
            return redirect()->route('admin.payment.index')->with('error', 'Có lỗi xảy ra khi rút tiền!');
        }
    }

    public function getUserWallet(Request $request, $userId)
    {
        $token = session('token');
        if (!$token) {
            return response()->json(['error' => 'Token không hợp lệ'], 401);
        }

        try {
            $response = Http::withToken($token)->get($this->gatewayUrl . '/api/wallet/admin/user/' . $userId);
            
            if ($response->successful()) {
                return response()->json($response->json());
            } else {
                return response()->json(['error' => $response->json('message') ?? 'Không lấy được thông tin ví'], $response->status());
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Có lỗi xảy ra khi kết nối đến server'], 500);
        }
    }

    public function getTransactions(Request $request)
    {
        $token = session('token');
        if (!$token) {
            return response()->json(['error' => 'Token không hợp lệ'], 401);
        }

        try {
            $params = $request->all();
            $response = Http::withToken($token)->get($this->gatewayUrl . '/api/wallet/admin/transactions', $params);
            
            if ($response->successful()) {
                return response()->json($response->json());
            } else {
                return response()->json(['error' => $response->json('message') ?? 'Không lấy được danh sách giao dịch'], $response->status());
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Có lỗi xảy ra khi kết nối đến server'], 500);
        }
    }

    public function bulkDeposit(Request $request)
    {
        $request->validate([
            'user_ids' => 'required|array|min:1',
            'user_ids.*' => 'integer',
            'amount' => 'required|numeric|min:1000',
            'description' => 'required|string|max:255',
        ]);

        $token = session('token');
        if (!$token) {
            return redirect()->route('login')->withErrors(['Vui lòng đăng nhập lại!']);
        }

        try {
            $response = Http::withToken($token)->post($this->gatewayUrl . '/api/wallet/admin/bulk-deposit', [
                'user_ids' => $request->user_ids,
                'amount' => $request->amount,
                'description' => $request->description,
                'admin_note' => $request->admin_note ?? '',
            ]);

            if ($response->successful()) {
                $result = $response->json();
                $successCount = $result['success_count'] ?? 0;
                $failedCount = $result['failed_count'] ?? 0;
                
                $message = "Nạp tiền hàng loạt hoàn tất. Thành công: {$successCount}, Thất bại: {$failedCount}";
                return redirect()->route('admin.payment.index')->with('success', $message);
            } else {
                $error = $response->json('message') ?? 'Nạp tiền hàng loạt thất bại!';
                return redirect()->route('admin.payment.index')->with('error', $error);
            }
        } catch (\Exception $e) {
            Log::error('Admin bulk deposit error: ' . $e->getMessage());
            return redirect()->route('admin.payment.index')->with('error', 'Có lỗi xảy ra khi nạp tiền hàng loạt!');
        }
    }

    public function exportTransactions(Request $request)
    {
        $token = session('token');
        if (!$token) {
            return redirect()->route('login')->withErrors(['Vui lòng đăng nhập lại!']);
        }

        try {
            $params = $request->all();
            $response = Http::withToken($token)->get($this->gatewayUrl . '/api/wallet/admin/transactions/export', $params);
            
            if ($response->successful()) {
                $filename = 'transactions_' . date('Y-m-d_H-i-s') . '.csv';
                return response($response->body())
                    ->header('Content-Type', 'text/csv')
                    ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
            } else {
                return redirect()->route('admin.payment.index')->with('error', 'Không thể xuất dữ liệu!');
            }
        } catch (\Exception $e) {
            return redirect()->route('admin.payment.index')->with('error', 'Có lỗi xảy ra khi xuất dữ liệu!');
        }
    }
    
    public function redirectVNPay($id)
    {
        $payment = \App\Models\Payment::findOrFail($id);
        $vnpayUrl = \App\Services\VNPayService::createRedirectUrl($payment);
        return redirect()->away($vnpayUrl);
    }

    public function createVnpayPayment(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'amount' => 'required|numeric|min:1000',
            'order_info' => 'required|string|max:255',
        ]);
        $token = session('token');
        $admin = session('user');
        if (!$token || !$admin) {
            return back()->with('error', 'Vui lòng đăng nhập lại!');
        }

        // Lấy thông tin user từ API hoặc DB
        $user = $this->getUserInfo($request->user_id, $token);
        $orderId = 'ORDER_' . time();
        $amount = $request->amount;
        $orderInfo = $request->order_info;
        $returnUrl = route('admin.payment.vnpay_return'); 
        $paymentUrl = VNPayService::createPaymentUrl($orderId, $amount, $orderInfo, $returnUrl);
        return redirect()->away($paymentUrl);
    }
    
    /**
     * Get user information from API
     */
    private function getUserInfo($userId, $token)
    {
        try {
            $response = Http::timeout(10)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $token,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json'
                ])
                ->get(config('app.api_base_url') . "/users/{$userId}");
    
            if ($response->successful()) {
                return $response->json();
            }
    
            Log::error('getUserInfo API Error:', [
                'status' => $response->status(),
                'user_id' => $userId,
                'response' => $response->body()
            ]);
            
            return null;
            
        } catch (Exception $e) {
            Log::error('getUserInfo Exception:', [
                'message' => $e->getMessage(),
                'user_id' => $userId
            ]);
            
            return null;
        }
    }
    
    /**
     * Format phone number for VNPay
     */
    private function formatPhoneNumber($phone)
    {
        if (empty($phone)) {
            return '';
        }
        
        // Remove all non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        // Ensure Vietnamese phone number format
        if (strlen($phone) === 10 && substr($phone, 0, 1) === '0') {
            return $phone;
        } elseif (strlen($phone) === 9) {
            return '0' . $phone;
        }
        
        return $phone;
    }
    
    /**
     * Save transaction record to database
     */
    private function saveTransactionRecord($data)
    {
        try {
            // Implement your transaction saving logic here
            // This could be saving to database or calling another API
            
            Log::info('Transaction record saved:', $data);
            
        } catch (Exception $e) {
            Log::error('Failed to save transaction record:', [
                'error' => $e->getMessage(),
                'data' => $data
            ]);
        }
    }

    public function vnpayReturn(Request $request)
    {
        // Có thể kiểm tra thêm logic test mode ở đây nếu cần
        return view('admin.payment.vnpay_return', [
            'status' => 'success',
            'message' => 'Giao dịch đã được xác nhận thành công (test mode).'
        ]);
    }

    /**
     * Lấy thống kê ví (giao dịch) cho user hoặc toàn hệ thống
     */
    public function getWalletStatistics(Request $request)
    {
        $token = session('token');
        if (!$token) {
            return response()->json(['error' => 'Token không hợp lệ'], 401);
        }
        $userId = $request->input('user_id');
        $type = $request->input('type');
        $from = $request->input('from');
        $to = $request->input('to');
        $params = [];
        if ($userId) $params['user_id'] = $userId;
        if ($type) $params['type'] = $type;
        if ($from) $params['from'] = $from;
        if ($to) $params['to'] = $to;
        try {
            $response = \Illuminate\Support\Facades\Http::withToken($token)
                ->get(env('GATEWAY_URL', 'http://localhost:8000') . '/api/wallet/statistics', $params);
            if ($response->successful()) {
                return response()->json($response->json());
            } else {
                return response()->json(['error' => $response->json('message') ?? 'Không lấy được thống kê'], $response->status());
            }
        } catch (\Exception $e) {
            \Log::error('Get wallet statistics error: ' . $e->getMessage());
            return response()->json(['error' => 'Có lỗi xảy ra khi lấy thống kê ví!'], 500);
        }
    }

    /**
     * Tạo transaction ví cho user (nạp, rút, hoàn tiền, trừ tiền)
     */
    public function createWalletTransaction(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'amount' => 'required|numeric|min:1000',
            'type' => 'required|string|in:deposit,withdraw,refund,deduct',
            'description' => 'required|string|max:255',
        ]);
        $token = session('token');
        if (!$token) {
            return back()->with('error', 'Token không hợp lệ');
        }
        $payload = [
            'user_id' => $request->user_id,
            'amount' => $request->amount,
            'type' => $request->type,
            'description' => $request->description,
        ];
        try {
            $response = \Illuminate\Support\Facades\Http::withToken($token)
                ->post(env('GATEWAY_URL', 'http://localhost:8000') . '/api/wallet/transaction', $payload);
            if ($response->successful()) {
                return back()->with('success', 'Tạo transaction thành công!');
            } else {
                $error = $response->json('message') ?? 'Tạo transaction thất bại!';
                return back()->with('error', $error);
            }
        } catch (\Exception $e) {
            \Log::error('Create wallet transaction error: ' . $e->getMessage());
            return back()->with('error', 'Có lỗi xảy ra khi tạo transaction!');
        }
    }
}