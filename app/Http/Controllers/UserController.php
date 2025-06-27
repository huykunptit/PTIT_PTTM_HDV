<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class UserController extends Controller
{
    public function getUserInformation(Request $request, $user_id)
    {
        $token = $request->bearerToken() ?? session('token');
        try {
            $response = \Illuminate\Support\Facades\Http::withHeaders([
                'Authorization' => 'Bearer ' . $token
            ])->get(env('GATEWAY_URL', 'https://f628-1-54-69-3.ngrok-free.app') . "/api/account/users/{$user_id}/information");
            if ($response->successful()) {
                return response()->json($response->json());
            }
            return response()->json(['error' => 'User not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function updateUser(Request $request, $user_id)
    {
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

    public function listUsers(Request $request)
    {
        $token = $request->bearerToken() ?? session('token');
        try {
            $response = \Illuminate\Support\Facades\Http::withHeaders([
                'Authorization' => 'Bearer ' . $token
            ])->get(env('GATEWAY_URL', 'https://f628-1-54-69-3.ngrok-free.app') . "/api/account/admin/users/list");
            if ($response->successful()) {
                return response()->json($response->json());
            }
            return response()->json(['error' => 'No users found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Danh sách user (GET /api/account/admin/users/list)
    public function index()
    {
        $token = session('token');
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token
            ])->get(env('GATEWAY_URL', 'https://f628-1-54-69-3.ngrok-free.app') . '/api/account/admin/users/list');
            
            $responseData = $response->successful() ? $response->json() : [];
            
            // Đảm bảo $users là array
            $users = [];
            if (is_array($responseData)) {
                if (isset($responseData['users']) && is_array($responseData['users'])) {
                    $users = $responseData['users'];
                } elseif (isset($responseData[0])) {
                    // Nếu response trực tiếp là array của users
                    $users = $responseData;
                }
            }
            
            return view('admin.users.index', compact('users'));
        } catch (\Exception $e) {
            return view('admin.users.index', ['users' => []]);
        }
    }

    // Form tạo user mới
    public function create()
    {
        return view('admin.users.create');
    }

    // Lưu user mới (POST /api/account/admin/users)
    public function store(Request $request)
    {
        $token = session('token');
        $validated = $request->validate([
            'full_name' => 'required',
            'username' => 'required',
            'email' => 'required|email',
            'phone' => 'nullable',
            'bod' => 'nullable|date',
            'address' => 'nullable',
            'role_id' => 'required|in:1,2',
            'password' => 'required|min:6',
        ]);
        // dd($validated);
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->post(env('GATEWAY_URL', 'https://f628-1-54-69-3.ngrok-free.app') . '/api/account/auth/register', $validated);
        // dd($response->json());
        if ($response->successful()) {
            return redirect()->route('admin.users.index')->with('success', 'Tạo user thành công!');
        }
        return back()->withErrors(['error' => 'Tạo user thất bại!']);
    }

    // Form sửa user (GET /api/account/users/{user_id}/information)
    public function edit($id)
    {
        $token = session('token');
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->get(env('GATEWAY_URL', 'https://f628-1-54-69-3.ngrok-free.app') . "/api/account/users/{$id}/information");
        $user = $response->successful() ? $response->json() : null;
        if (!$user) {
            return redirect()->route('admin.users.index')->withErrors(['error' => 'Không tìm thấy user!']);
        }
        return view('admin.users.edit', compact('user'));
    }

    // Cập nhật user (PUT /api/account/users/{user_id}/update)
    public function update(Request $request, $id)
    {
        $token = session('token');
        $validated = $request->validate([
            'full_name' => 'required',
            'email' => 'required|email',
            'phone' => 'nullable',
            'bod' => 'nullable|date',
            'address' => 'nullable',
            'role_id' => 'required|in:1,2',
            'password' => 'nullable|min:6',
        ]);
        if (empty($validated['password'])) {
            unset($validated['password']);
        }
        
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->put(env('GATEWAY_URL', 'https://f628-1-54-69-3.ngrok-free.app') . "/api/account/users/{$id}/update", $validated);
        if ($response->successful()) {
            // dd(1);
            return redirect()->route('admin.users.index')->with('success', 'Cập nhật user thành công!');
        }
        dd($response->json());
        return back()->withErrors(['error' => 'Cập nhật user thất bại!']);
    }

    // Xóa user (DELETE /api/account/admin/delete/{user_id})
    public function destroy($id)
    {
        $token = session('token');
       
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->delete(env('GATEWAY_URL', 'https://f628-1-54-69-3.ngrok-free.app') . "/api/account/admin/delete/{$id}");
        if ($response->successful()) {
            return redirect()->route('admin.users.index')->with('success', 'Xóa user thành công!');
        }
        dd($response->json());
        return back()->withErrors(['error' => 'Xóa user thất bại!']);
    }
} 