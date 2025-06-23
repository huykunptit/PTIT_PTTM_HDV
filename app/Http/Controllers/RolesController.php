<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
class RolesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function list_roles(Request $request)
    {
        $response = Http::get(env('GATEWAY_URL') . '/api/role/roles');
        $data = $response->json();
        return response()->json([
            'success' => $response->successful(),
            'roles' => $data['roles'] ?? [],
            'toast' => [
                'type' => $response->successful() ? 'success' : 'error',
                'message' => $response->successful()
                    ? 'Danh sách vai trò đã được tải thành công.'
                    : ($data['message'] ?? 'Không thể tải danh sách vai trò. Vui lòng thử lại.')
            ]
        ], $response->successful() ? 200 : $response->status());
    }

    public function get_roles(Request $request)
    {
        $request->validate(['role_id' => 'required|integer']);
        $token = session('token');

        $response = Http::withToken($token)
            ->get(env('GATEWAY_URL') . '/api/role/roles/', [
                'role_id' => $request->role_id,
            ]);

        $data = $response->json();

        if ($response->successful()) {
            return response()->json([
                'success' => true,
                'roles' => $data['roles'] ?? [],
                'toast' => [
                    'type' => 'info',
                    'message' => 'Đã lấy danh sách vai trò thành công!'
                ]
            ]);
        }

        return response()->json([
            'success' => false,
            'roles' => [],
            'toast' => [
                'type' => 'error',
                'message' => $data['message'] ?? 'Không thể lấy danh sách vai trò. Vui lòng thử lại.'
            ]
        ], $response->status());
    }

    public function update_roles(Request $request)
    {
        $request->validate([
            'role_id' => 'required|integer'
        ]);

        $token = session('token');

        $response = Http::withToken($token)
            ->put(env('GATEWAY_URL') . '/api/role/roles/' . $request->role_id, [
                'role_id' => $request->role_id,
            ]);

        $data = $response->json();
            dd($data);
        if ($response->successful()) {
            return response()->json([
                'success' => true,
                'toast' => [
                    'type' => 'success',
                    'message' => 'Cập nhật vai trò thành công!'
                ]
            ]);
        }

        return response()->json([
            'success' => false,
            'toast' => [
                'type' => 'error',
                'message' => $data['message'] ?? 'Không thể cập nhật vai trò. Vui lòng thử lại.'
            ]
        ], $response->status());
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
