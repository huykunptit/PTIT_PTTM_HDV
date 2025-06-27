<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MachineController extends Controller
{
    private $baseUrl;
    public function __construct()
    {
        $this->baseUrl = env('GATEWAY_URL', 'http://localhost:8000') . '/api/machine/machines';
    }
    private function getTokenOrRedirect()
    {
        $token = session('token');
        if (!$token) {
            return redirect()->route('login')->withErrors(['Vui lòng đăng nhập lại!']);
        }
        return $token;
    }
    public function index()
    {
        $token = $this->getTokenOrRedirect();
        if ($token instanceof \Illuminate\Http\RedirectResponse) return $token;
        $res = Http::withToken($token)->get($this->baseUrl);
        $machines = $res->successful() ? $res->json() : [];
        // Lấy danh sách area để hiển thị tên khu vực
        $areaRes = Http::withToken($token)->get(env('GATEWAY_URL', 'http://localhost:8000') . '/api/area/areas');
        $areas = $areaRes->successful() ? $areaRes->json() : [];
        return view('admin.machines.index', compact('machines', 'areas'));
    }
    public function create()
    {
        $token = $this->getTokenOrRedirect();
        if ($token instanceof \Illuminate\Http\RedirectResponse) return $token;
        $areaRes = Http::withToken($token)->get(env('GATEWAY_URL', 'http://localhost:8000') . '/api/area/areas');
        $areas = $areaRes->successful() ? $areaRes->json() : [];
        return view('admin.machines.create', compact('areas'));
    }
    public function store(Request $request)
    {
        $token = $this->getTokenOrRedirect();
        if ($token instanceof \Illuminate\Http\RedirectResponse) return $token;
        $validated = $request->validate([
            'code' => 'required|string',
            'ip_address' => 'required|string',
            'area_id' => 'required|integer',
            'status' => 'required|string',
        ]);
        $res = Http::withToken($token)->post($this->baseUrl, $validated);
        return $res->successful()
            ? redirect()->route('admin.admin.machines.index')->with('success', 'Tạo máy thành công')
            : back()->withErrors(['error' => 'Tạo máy thất bại']);
    }
    public function edit($id)
    {
        $token = $this->getTokenOrRedirect();
        if ($token instanceof \Illuminate\Http\RedirectResponse) return $token;
        $res = Http::withToken($token)->get("{$this->baseUrl}/{$id}");
        $machine = $res->successful() ? $res->json() : null;
        $areaRes = Http::withToken($token)->get(env('GATEWAY_URL', 'http://localhost:8000') . '/api/area/areas');
        $areas = $areaRes->successful() ? $areaRes->json() : [];
        return view('admin.machines.edit', compact('machine', 'areas'));
    }
    public function update(Request $request, $id)
    {
        $token = $this->getTokenOrRedirect();
        if ($token instanceof \Illuminate\Http\RedirectResponse) return $token;
        $validated = $request->validate([
            'code' => 'required|string',
            'ip_address' => 'required|string',
            'area_id' => 'required|integer',
            'status' => 'required|string',
        ]);
        $res = Http::withToken($token)->put("{$this->baseUrl}/{$id}", $validated);
        return $res->successful()
            ? redirect()->route('admin.admin.machines.index')->with('success', 'Cập nhật máy thành công')
            : back()->withErrors(['error' => 'Cập nhật máy thất bại']);
    }
    public function destroy($id)
    {
        $token = $this->getTokenOrRedirect();
        if ($token instanceof \Illuminate\Http\RedirectResponse) return $token;
        $res = Http::withToken($token)->delete("{$this->baseUrl}/{$id}");
        return redirect()->route('admin.admin.machines.index')->with(
            $res->successful() ? ['success' => 'Xóa máy thành công'] : ['error' => 'Xóa máy thất bại']
        );
    }
} 