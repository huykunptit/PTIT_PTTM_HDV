<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    private $gatewayUrl = 'https://f628-1-54-69-3.ngrok-free.app';

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|max:255',
            'bod' => 'required|date',
            'email' => 'required|string|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'username' => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $requestData = [
                'full_name' => $request->full_name,
                'bod' => $request->bod,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'username' => $request->username,
                'password' => $request->password,
            ];

            $response = Http::post($this->gatewayUrl . '/api/account/auth/register', $requestData);

            if ($response->successful()) {
                $userData = $response->json();
                session(['user' => $userData]);
                return redirect()->route('shop.index')->with('success', 'Registration successful!');
            } else {
                $error = $response->json();
                return redirect()->back()->withErrors(['error' => $error['error'] ?? 'Registration failed'])->withInput();
            }
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Connection error'])->withInput();
        }
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string',
            'machine_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $response = Http::post($this->gatewayUrl . '/api/account/auth/login', [
                'username' => $request->username,
                'password' => $request->password,
                'machine_id' => $request->machine_id,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                if (is_array($data) && isset($data['user'], $data['token'])) {
                    session([
                        'user' => $data['user'],
                        'token' => $data['token'],
                        'account' => $data['account'] ?? null,
                        'playtime' => $data['playtime'] ?? null,
                    ]);

                    if ($data['user']['role_id'] === 1) {
                        return redirect()->route('admin.dashboard');
                    } else {
                        return redirect()->route('shop.index');
                    }
                } else {
                    // Kiểm tra nếu có thông báo lỗi về nạp tiền
                    if (isset($data['message']) && strpos($data['message'], 'nạp tiền') !== false) {
                        // Lưu thông tin user tạm thời để có thể chuyển hướng đến trang thanh toán
                        if (isset($data['user'], $data['token'])) {
                            session([
                                'user' => $data['user'],
                                'token' => $data['token'],
                                'account' => $data['account'] ?? null,
                                'playtime' => $data['playtime'] ?? null,
                            ]);
                        }
                       
                        // Chuyển hướng đến trang thanh toán với thông báo
                        return redirect()->route('payment.index')->with('error', $data['message']);
                    }
                    
                    $errorMsg = isset($data['message']) ? $data['message'] : $data;
                    return redirect()->back()->withErrors(['login' => $errorMsg])->withInput();
                }
            } else {
                $errorData = $response->json();
                $errorMsg = is_array($errorData) && isset($errorData['message']) ? $errorData['message'] : 'Đăng nhập thất bại!';
                
                // Kiểm tra nếu có thông báo lỗi về nạp tiền trong response lỗi
                if (strpos($errorMsg, 'nạp tiền') !== false) {
                    // Nếu có thông tin user trong response lỗi, lưu vào session
                    if (isset($errorData['user'], $errorData['token'])) {
                        session([
                            'user' => $errorData['user'],
                            'token' => $errorData['token'],
                            'account' => $errorData['account'] ?? null,
                            'playtime' => $errorData['playtime'] ?? null,
                        ]);
                    }
                    
                    // Chuyển hướng đến trang thanh toán với thông báo
                    return redirect()->route('payment.index')->with('error', $errorMsg);
                }
                
                return redirect()->back()->withErrors(['login' => $errorMsg])->withInput();
            }
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['username' => 'Connection error'])->withInput();
        }
    }

    public function logout(Request $request)
    {
        try {
            $token = session('token');
            if ($token) {
                Http::withHeaders(['Authorization' => 'Bearer ' . $token])
                    ->post($this->gatewayUrl . '/api/account/auth/logout');
            }
        } catch (\Exception $e) {
            // Continue with logout
        }

        $request->session()->flush();
        return redirect()->route('login')->with('success', 'Logged out successfully');
    }

    public function showPasswordResetForm(Request $request)
    {
        $token = $request->route('token');
        if ($token) {
            return view('auth.passwords.reset', ['token' => $token, 'email' => $request->email]);
        }
        return view('auth.passwords.email');
    }

    public function sendPasswordResetLink(Request $request)
    {
        $validator = Validator::make($request->all(), ['email' => 'required|email']);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $response = Http::post($this->gatewayUrl . '/api/account/auth/password/reset-request', [
                'email' => $request->email,
            ]);

            if ($response->successful()) {
                return redirect()->back()->with('success', 'Password reset link sent!');
            } else {
                return redirect()->back()->withErrors(['email' => 'Failed to send reset link'])->withInput();
            }
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['email' => 'Connection error'])->withInput();
        }
    }

    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $response = Http::post($this->gatewayUrl . '/api/account/auth/password/reset-confirm', [
                'token' => $request->token,
                'new_password' => $request->password,
            ]);

            if ($response->successful()) {
                return redirect()->route('login')->with('success', 'Password reset successful!');
            } else {
                return redirect()->back()->withErrors(['password' => 'Failed to reset password'])->withInput();
            }
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['password' => 'Connection error'])->withInput();
        }
    }

    public function passwordResetRequest(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Email not found'], 404);
        }

        $result = $this->apiPostWithoutAuth('/api/account/auth/password/reset-request', [
            'email' => $request->email,
        ]);

        return $result ? response()->json($result) : response()->json(['error' => 'Connection error'], 500);
    }

    public function passwordResetConfirm(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required|string',
            'new_password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Invalid token'], 401);
        }

        $result = $this->apiPostWithoutAuth('/api/account/auth/password/reset-confirm', [
            'token' => $request->token,
            'new_password' => $request->new_password,
        ]);

        return $result ? response()->json($result) : response()->json(['error' => 'Connection error'], 500);
    }

    public function emailVerify(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Invalid token'], 400);
        }

        $result = $this->apiPostWithoutAuth('/api/account/auth/email/verify', [
            'token' => $request->token,
        ]);

        return $result ? response()->json($result) : response()->json(['error' => 'Connection error'], 500);
    }

    public function emailResend(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Invalid email'], 400);
        }

        $result = $this->apiPostWithoutAuth('/api/account/auth/email/resend', [
            'email' => $request->email,
        ]);

        return $result ? response()->json($result) : response()->json(['error' => 'Connection error'], 500);
    }

    public function sessions(Request $request)
    {
        $result = $this->apiGet('/api/account/auth/sessions');
        return $result ? response()->json($result) : response()->json(['error' => 'Connection error'], 500);
    }

    public function revokeAllSessions(Request $request)
    {
        $result = $this->apiDelete('/api/account/auth/sessions');
        return $result ? response()->json($result) : response()->json(['error' => 'Connection error'], 500);
    }

    public function revokeSession(Request $request, $token)
    {
        $result = $this->apiDelete('/api/account/auth/sessions/' . $token);
        return $result ? response()->json($result) : response()->json(['error' => 'Connection error'], 500);
    }

    public function store(Request $request)
    {
        $token = session('token');
        if (!$token) {
            return redirect()->route('login')->withErrors(['Vui lòng đăng nhập lại!']);
        }
        // Gọi API Gateway với token này
    }

    public function updateUser(Request $request, $user_id)
    {
        dd($request->all(), $user_id);
        $token = $request->bearerToken() ?? session('token');
        $data = $request->only(['full_name', 'bod', 'address', 'email', 'phone']);
        try {
            $response = \Illuminate\Support\Facades\Http::withHeaders([
                'Authorization' => 'Bearer ' . $token
            ])->put(env('GATEWAY_URL', 'https://f628-1-54-69-3.ngrok-free.app') . "/api/account/users/{$user_id}/update", $data);
            if ($response->successful()) {
                return response()->json($response->json());
            }
            return response()->json(['error' => 'Update failed'], 400);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function deleteUser(Request $request, $user_id)
    {
        dd($user_id);
        $token = $request->bearerToken() ?? session('token');
        try {
            $response = \Illuminate\Support\Facades\Http::withHeaders([
                'Authorization' => 'Bearer ' . $token
            ])->delete(env('GATEWAY_URL', 'https://f628-1-54-69-3.ngrok-free.app') . "/api/account/admin/delete/{$user_id}");
            if ($response->successful()) {
                return response()->json(['success' => true]);
            }
            return response()->json(['error' => 'Delete failed'], 400);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
} 