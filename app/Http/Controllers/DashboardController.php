<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DashboardController extends Controller
{
    private $gatewayUrl = 'https://f628-1-54-69-3.ngrok-free.app';

    public function index()
    {
        try {
            $token = session('token');
            
            // Get users list
            $usersResponse = Http::withHeaders(['Authorization' => 'Bearer ' . $token])
                ->get($this->gatewayUrl . '/api/account/admin/users/list');
            $users = $usersResponse->successful() ? $usersResponse->json() : [];

            // Get products
            $productsResponse = Http::withHeaders(['Authorization' => 'Bearer ' . $token])
                ->get($this->gatewayUrl . '/api/product/products');
            $products = $productsResponse->successful() ? $productsResponse->json() : [];

            return view('dashboard.index', compact('users', 'products'));
        } catch (\Exception $e) {
            return view('dashboard.index', ['users' => [], 'products' => []]);
        }
    }

    public function userDashboard()
    {
        try {
            $token = session('token');
            $user = session('user');
            
            // Get user's recent orders
            $ordersResponse = Http::withHeaders(['Authorization' => 'Bearer ' . $token])
                ->get($this->gatewayUrl . '/api/order/orders', [
                    'user_id' => $user['id'],
                    'limit' => 100
                ]);
            $recentOrders = $ordersResponse->successful() ? $ordersResponse->json() : [];

            // Get user's account info
            $accountResponse = Http::withHeaders(['Authorization' => 'Bearer ' . $token])
                ->get($this->gatewayUrl . '/api/account/account-info');
            $accountInfo = $accountResponse->successful() ? $accountResponse->json() : [];

            // Get user's playtime info
            $playtimeResponse = Http::withHeaders(['Authorization' => 'Bearer ' . $token])
                ->get($this->gatewayUrl . '/api/account/playtime-info');
            $playtimeInfo = $playtimeResponse->successful() ? $playtimeResponse->json() : [];

            return view('dashboard.user-dashboard', compact('user', 'recentOrders', 'accountInfo', 'playtimeInfo'));
        } catch (\Exception $e) {
            return view('dashboard.user-dashboard', [
                'user' => session('user'),
                'recentOrders' => [],
                'accountInfo' => [],
                'playtimeInfo' => []
            ]);
        }
    }

    public function statistics()
    {
        return view('dashboard.statistics');
    }

    public function users()
    {
        try {
            $token = session('token');
            
            $response = Http::withHeaders(['Authorization' => 'Bearer ' . $token])
                ->get($this->gatewayUrl . '/api/account/admin/users/list', [
                    'page' => request('page', 1),
                    'per_page' => request('per_page', 10),
                ]);
            
            $data = $response->successful() ? $response->json() : ['users' => [], 'total' => 0];
            
            return view('dashboard.users', compact('data'));
        } catch (\Exception $e) {
            return view('dashboard.users', ['data' => ['users' => [], 'total' => 0]]);
        }
    }

    // Thêm method để tạo user mới
    public function storeUser(Request $request)
    {
        try {
            $token = session('token');
            
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/json'
            ])->post($this->gatewayUrl . '/api/account/admin/users', [
                'full_name' => $request->full_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'role_id' => $request->role_id,
                'password' => $request->password,
            ]);
            dd($response);
            if ($response->successful()) {
                return redirect()->route('admin.users.index')->with('success', 'User created successfully');
            } else {
                $error = $response->json()['message'] ?? 'Failed to create user';
                return redirect()->back()->with('error', $error);
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while creating user');
        }
    }

    // Cập nhật method updateUser trong DashboardController
    public function updateUser(Request $request, $id)
    {
        try {
            $token = session('token');
            dd($token);
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/json'
            ])->put($this->gatewayUrl . '/api/account/users/' . $id . '/update', [
                'full_name' => $request->full_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'role_id' => $request->role_id,
            ]);

            if ($response->successful()) {
                return redirect()->route('admin.users.index')->with('success', 'User updated successfully');
            } else {
                $error = $response->json()['message'] ?? 'Failed to update user';
                return redirect()->back()->with('error', $error);
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while updating user');
        }
    }

    // Cập nhật method deleteUser trong DashboardController
    public function deleteUser($id)
    {
        try {
            $token = session('token');
            
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
            ])->delete($this->gatewayUrl . '/api/account/admin/delete/' . $id);

            if ($response->successful()) {
                return redirect()->route('admin.users.index')->with('success', 'User deleted successfully');
            } else {
                $error = $response->json()['message'] ?? 'Failed to delete user';
                return redirect()->back()->with('error', $error);
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while deleting user');
        }
    }

    public function products()
    {
        return view('dashboard.products');
    }

    public function categories()
    {
        return view('dashboard.categories');
    }

    public function orders()
    {
        return view('dashboard.orders');
    }

    public function machines()
    {
        try {
            $token = session('token');
            // Lấy danh sách máy
            $machinesResponse = \Illuminate\Support\Facades\Http::withHeaders([
                'Authorization' => 'Bearer ' . $token
            ])->get($this->gatewayUrl . '/api/machine/machines');
            $machines = $machinesResponse->successful() ? $machinesResponse->json() : [];
            // Lấy danh sách khu vực
            $areasResponse = \Illuminate\Support\Facades\Http::withHeaders([
                'Authorization' => 'Bearer ' . $token
            ])->get($this->gatewayUrl . '/api/area/areas');
            $areas = $areasResponse->successful() ? $areasResponse->json() : [];
            return view('dashboard.machines', compact('machines', 'areas'));
        } catch (\Exception $e) {
            return view('dashboard.machines', ['machines' => [], 'areas' => []]);
        }
    }

    public function areas()
    {
        return view('dashboard.areas');
    }

    public function promotions()
    {
        return view('dashboard.promotions');
    }

    public function sessions()
    {
        return view('dashboard.sessions');
    }

    public function reports()
    {
        return view('dashboard.reports');
    }
    public function machinesByAreaDashboard(Request $request)
    {
        $token = session('token');
        if (!$token) return redirect()->route('login')->withErrors(['Vui lòng đăng nhập lại!']);
        $gateway = env('GATEWAY_URL', 'http://localhost:8000');
        $areaRes = \Illuminate\Support\Facades\Http::withToken($token)->get($gateway . '/api/area/areas');
        $areas = $areaRes->successful() ? $areaRes->json() : [];
        $selectedArea = $request->input('area_id');
        $selectedStatus = $request->input('status');
        $areaMachines = [];
        foreach ($areas as $area) {
            if ($selectedArea && $area['id'] != $selectedArea) continue;
            $machineRes = \Illuminate\Support\Facades\Http::withToken($token)->get($gateway . "/api/area/areas/{$area['id']}/machines");
            $machines = $machineRes->successful() ? $machineRes->json() : [];
            if ($selectedStatus && isset($machines['machines']) && is_array($machines['machines'])) {
                $machines['machines'] = array_values(array_filter($machines['machines'], fn($m) => $m['status'] === $selectedStatus));
            }
            $areaMachines[] = [
                'area' => $area,
                'machines' => $machines,
            ];
        }
        return view('dashboard.machines_by_area', [
            'areaMachines' => $areaMachines,
            'areas' => $areas,
            'selectedArea' => $selectedArea,
            'selectedStatus' => $selectedStatus,
        ]);
    }
}