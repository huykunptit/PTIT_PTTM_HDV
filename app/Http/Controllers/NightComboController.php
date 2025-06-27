<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class NightComboController extends Controller
{
    public function index()
    {
        $token = session('token');
        $res = Http::withToken($token)->get(env('GATEWAY_URL') . '/api/product/night-combos');
        $combos = $res->successful() ? $res->json() : [];
        return view('admin.night_combos.index', compact('combos'));
    }

    public function create()
    {
        $token = session('token');
        $res = Http::withToken($token)->get(env('GATEWAY_URL') . '/api/category/categories');
        $categories = $res->successful() ? $res->json() : [];
        return view('admin.night_combos.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $token = session('token');
        $validated = $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'category_id' => 'required|integer',
            'stock' => 'required|integer',
            'duration_hours' => 'required|integer',
            'start_hour' => 'required|integer',
            'end_hour' => 'required|integer',
            'metadata' => 'nullable',
        ]);

        $payload = $validated;
        $payload['product_type'] = 'night_combo';
        $payload['metadata'] = $validated['metadata'] ? json_decode($validated['metadata'], true) : [];

        $res = Http::withToken($token)->post(env('GATEWAY_URL') . '/api/product/night-combos', $payload);
        if ($res->successful()) {
            $id = $res->json()['id'] ?? null;
            if ($id && $request->hasFile('image')) {
                Http::withToken($token)->attach(
                    'image', file_get_contents($request->file('image')->getRealPath()), $request->file('image')->getClientOriginalName()
                )->post(env('GATEWAY_URL') . "/api/product/night-combos/$id/upload-image");
            }
            return redirect()->route('admin.night-combos.index')->with('success', 'Tạo Night Combo thành công');
        }
        return back()->withErrors(['error' => 'Tạo Night Combo thất bại']);
    }

    public function edit($id)
    {
        $token = session('token');
        $res = Http::withToken($token)->get(env('GATEWAY_URL') . "/api/product/night-combos/$id");
        $combo = $res->successful() ? $res->json() : null;

        $catRes = Http::withToken($token)->get(env('GATEWAY_URL') . '/api/category/categories');
        $categories = $catRes->successful() ? $catRes->json() : [];

        return view('admin.night_combos.edit', compact('combo', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $token = session('token');
        $validated = $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'category_id' => 'required|integer',
            'stock' => 'required|integer',
            'duration_hours' => 'required|integer',
            'start_hour' => 'required|integer',
            'end_hour' => 'required|integer',
            'metadata' => 'nullable',
        ]);

        $payload = $validated;
        $payload['product_type'] = 'night_combo';
        $payload['metadata'] = $validated['metadata'] ? json_decode($validated['metadata'], true) : [];

        $res = Http::withToken($token)->put(env('GATEWAY_URL') . "/api/product/night-combos/$id", $payload);
        if ($res->successful()) {
            if ($request->hasFile('image')) {
                Http::withToken($token)->attach(
                    'image', file_get_contents($request->file('image')->getRealPath()), $request->file('image')->getClientOriginalName()
                )->post(env('GATEWAY_URL') . "/api/product/night-combos/$id/upload-image");
            }
            return redirect()->route('admin.night-combos.index')->with('success', 'Cập nhật thành công');
        }
        return back()->withErrors(['error' => 'Cập nhật thất bại']);
    }

    public function destroy($id)
    {
        $token = session('token');
        $res = Http::withToken($token)->delete(env('GATEWAY_URL') . "/api/product/night-combos/$id");
        return redirect()->route('admin.night-combos.index')->with(
            $res->successful() ? ['success' => 'Xóa thành công'] : ['error' => 'Xóa thất bại']
        );
    }

    public function uploadImage(Request $request, $id)
    {
        $request->validate(['image' => 'required|image|max:2048']);
        $token = session('token');
        $res = Http::withToken($token)->attach(
            'image', file_get_contents($request->file('image')->getRealPath()), $request->file('image')->getClientOriginalName()
        )->post(env('GATEWAY_URL') . "/api/product/night-combos/$id/upload-image");

        return $res->successful()
            ? back()->with('success', 'Tải ảnh lên thành công')
            : back()->withErrors(['error' => 'Tải ảnh thất bại']);
    }
}
