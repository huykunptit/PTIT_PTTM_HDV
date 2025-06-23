<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function dashboard()
    {
        return view('dashboard.index');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function showResetRequestForm()
    {
        return view('auth.reset-request');
    }

    public function showResetForm($token)
    {
        return view('auth.passwords.reset', ['token' => $token]);
    }

    public function showSessionsPage()
    {
        return view('auth.sessions');
    }

    public function register(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string',
            'bod' => 'required|string',
            'address' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $response = Http::post(env('GATEWAY_URL') . '/api/account/auth/register', $request->all());
        $data = $response->json();

        if ($response->successful()) {
            return redirect()->route('login')->with([
                'toast_type' => 'success',
                'toast_message' => 'Đăng ký thành công! Chào mừng bạn đến với hệ thống.'
            ]);
        }

        return back()->withErrors(['register' => 'Đăng ký thất bại.'])->with([
            'toast_type' => 'error',
            'toast_message' => 'Đăng ký thất bại. Vui lòng kiểm tra lại thông tin.'
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
            'machine_id' => 'required|integer',
        ]);

        $response = Http::post(env('GATEWAY_URL') . '/api/account/auth/login', $request->only('username', 'password', 'machine_id'));
        $data = $response->json();

        if ($response->successful()) {
            Session::put('user', $data['user'] ?? []);
            Session::put('token', $data['token'] ?? null);
            Session::put('account', $data['account'] ?? []);
            Session::put('playtime', $data['playtime'] ?? []);

            return redirect('/dashboard')->with([
                'toast_type' => 'success',
                'toast_message' => 'Đăng nhập thành công! Chào mừng bạn quay trở lại.'
            ]);
        }

        return back()->withErrors(['login' => 'Sai thông tin đăng nhập.'])->with([
            'toast_type' => 'error',
            'toast_message' => 'Đăng nhập thất bại. Vui lòng kiểm tra lại thông tin đăng nhập.'
        ]);
    }

    public function logout(Request $request)
    {
        $token = session('token');
        if ($token) {
            Http::withToken($token)->post(env('GATEWAY_URL') . '/api/account/auth/logout');
        }

        Session::flush();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'toast' => [
                    'type' => 'info',
                    'message' => 'Đã đăng xuất thành công. Hẹn gặp lại!'
                ],
                'redirect' => '/login'
            ]);
        }

        return redirect('/login')->with([
            'toast_type' => 'info',
            'toast_message' => 'Đã đăng xuất thành công. Hẹn gặp lại!'
        ]);
    }

    public function verify()
    {
        $token = session('token');
        $response = Http::withToken($token)->get(env('GATEWAY_URL') . '/api/account/auth/verify');

        return response()->json($response->json());
    }

    public function requestPasswordReset(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $response = Http::post(env('GATEWAY_URL') . '/api/account/auth/password/reset-request', [
            'email' => $request->email,
        ]);

        $data = $response->json();
        $data['toast'] = [
            'type' => $response->successful() ? 'success' : 'error',
            'message' => $response->successful()
                ? 'Email đặt lại mật khẩu đã được gửi. Vui lòng kiểm tra hộp thư của bạn.'
                : 'Không thể gửi email đặt lại mật khẩu. Vui lòng thử lại.'
        ];

        return response()->json($data);
    }

    public function confirmPasswordReset(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
            'new_password' => 'required|string',
        ]);

        $response = Http::post(env('GATEWAY_URL') . '/api/account/auth/password/reset-confirm', $request->only('token', 'new_password'));
        $data = $response->json();

        $data['toast'] = [
            'type' => $response->successful() ? 'success' : 'error',
            'message' => $response->successful()
                ? 'Mật khẩu đã được đặt lại thành công. Bạn có thể đăng nhập với mật khẩu mới.'
                : 'Không thể đặt lại mật khẩu. Token có thể đã hết hạn hoặc không hợp lệ.'
        ];

        return response()->json($data);
    }

    public function verifyEmail(Request $request)
    {
        $request->validate(['token' => 'required|string']);

        $response = Http::post(env('GATEWAY_URL') . '/api/account/auth/email/verify', $request->only('token'));
        $data = $response->json();

        $data['toast'] = [
            'type' => $response->successful() ? 'success' : 'error',
            'message' => $response->successful()
                ? 'Email đã được xác thực thành công!'
                : 'Không thể xác thực email. Token có thể đã hết hạn hoặc không hợp lệ.'
        ];

        return response()->json($data);
    }

    public function resendVerification(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $response = Http::post(env('GATEWAY_URL') . '/api/account/auth/email/resend', $request->only('email'));
        $data = $response->json();

        $data['toast'] = [
            'type' => $response->successful() ? 'success' : 'error',
            'message' => $response->successful()
                ? 'Email xác thực đã được gửi lại. Vui lòng kiểm tra hộp thư của bạn.'
                : 'Không thể gửi lại email xác thực. Vui lòng thử lại.'
        ];

        return response()->json($data);
    }

    public function getActiveSessions()
    {
        $token = session('token');
        $response = Http::withToken($token)->get(env('GATEWAY_URL') . '/api/account/auth/sessions');

        return response()->json($response->json());
    }

    public function revokeAllSessions()
    {
        $token = session('token');
        $response = Http::withToken($token)->delete(env('GATEWAY_URL') . '/api/account/auth/sessions');
        $data = $response->json();

        $data['toast'] = [
            'type' => $response->successful() ? 'success' : 'error',
            'message' => $response->successful()
                ? 'Đã đăng xuất khỏi tất cả thiết bị thành công!'
                : 'Không thể đăng xuất khỏi tất cả thiết bị. Vui lòng thử lại.'
        ];

        return response()->json($data);
    }

    public function revokeSession(Request $request)
    {
        $token = session('token');
        $sessionId = $request->input('session_id');

        $response = Http::withToken($token)->delete(env('GATEWAY_URL') . "/api/account/auth/sessions/{$sessionId}");
        $data = $response->json();

        $data['toast'] = [
            'type' => $response->successful() ? 'success' : 'error',
            'message' => $response->successful()
                ? 'Đã đăng xuất khỏi thiết bị được chọn thành công!'
                : 'Không thể đăng xuất khỏi thiết bị được chọn. Vui lòng thử lại.'
        ];

        return response()->json($data);
    }
}
