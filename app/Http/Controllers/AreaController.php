<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AreaController extends Controller
{
    private $baseUrl;
    public function __construct()
    {
        $this->baseUrl = env('GATEWAY_URL', 'http://localhost:8000') . '/api/area/areas';
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
        $areas = $res->successful() ? $res->json() : [];
        return view('admin.areas.index', compact('areas'));
    }
    public function create()
    {
        $token = $this->getTokenOrRedirect();
        if ($token instanceof \Illuminate\Http\RedirectResponse) return $token;
        return view('admin.areas.create');
    }
    public function store(Request $request)
    {
        $token = $this->getTokenOrRedirect();
        if ($token instanceof \Illuminate\Http\RedirectResponse) return $token;
        $validated = $request->validate([
            'name' => 'required',
            'description' => 'nullable',
            'price_per_hour' => 'required|numeric',
        ]);
        $res = Http::withToken($token)->post($this->baseUrl, $validated);
        return $res->successful()
            ? redirect()->route('admin.admin.areas.index')->with('success', 'Tạo area thành công')
            : back()->withErrors(['error' => 'Tạo area thất bại']);
    }
    public function edit($id)
    {
        $token = $this->getTokenOrRedirect();
        if ($token instanceof \Illuminate\Http\RedirectResponse) return $token;
        $res = Http::withToken($token)->get("{$this->baseUrl}/{$id}");
        $area = $res->successful() ? $res->json() : null;
        return view('admin.areas.edit', compact('area'));
    }
    public function update(Request $request, $id)
    {
        $token = $this->getTokenOrRedirect();
        if ($token instanceof \Illuminate\Http\RedirectResponse) return $token;
        $validated = $request->validate([
            'name' => 'required',
            'description' => 'nullable',
            'price_per_hour' => 'required|numeric',
        ]);
        $res = Http::withToken($token)->put("{$this->baseUrl}/{$id}", $validated);
        return $res->successful()
            ? redirect()->route('admin.admin.areas.index')->with('success', 'Cập nhật area thành công')
            : back()->withErrors(['error' => 'Cập nhật area thất bại']);
    }
    public function destroy($id)
    {
        $token = $this->getTokenOrRedirect();
        if ($token instanceof \Illuminate\Http\RedirectResponse) return $token;
        $res = Http::withToken($token)->delete("{$this->baseUrl}/{$id}");
        return redirect()->route('admin.admin.areas.index')->with(
            $res->successful() ? ['success' => 'Xóa area thành công'] : ['error' => 'Xóa area thất bại']
        );
    }
} 