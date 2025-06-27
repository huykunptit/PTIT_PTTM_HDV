<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CategoryController extends Controller
{
    // Danh sách category (GET /api/category/categories)
    public function index()
    {
        $token = session('token');
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->get(env('GATEWAY_URL', 'https://f628-1-54-69-3.ngrok-free.app') . '/api/category/categories');
        $categories = $response->successful() ? ($response->json() ?? []) : [];
        return view('admin.categories.index', compact('categories'));
    }

    // Form tạo category
    public function create()
    {
        return view('admin.categories.create');
    }

    // Lưu category mới (POST /api/category/categories)
    public function store(Request $request)
    {
        $token = session('token');
        $validated = $request->validate([
            'name' => 'required',
            'description' => 'nullable',
        ]);
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->post(env('GATEWAY_URL', 'https://f628-1-54-69-3.ngrok-free.app') . '/api/category/categories', $validated);
        if ($response->successful()) {
            return redirect()->route('admin.categories.index')->with('success', 'Tạo category thành công!');
        }
        return back()->withErrors(['error' => 'Tạo category thất bại!']);
    }

    // Form sửa category (GET /api/category/categories/{category_id})
    public function edit($id)
    {
        $token = session('token');
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->get(env('GATEWAY_URL', 'https://f628-1-54-69-3.ngrok-free.app') . "/api/category/categories/{$id}");
        $category = $response->successful() ? $response->json() : null;
        if (!$category) {
            return redirect()->route('admin.categories.index')->withErrors(['error' => 'Không tìm thấy category!']);
        }
        return view('admin.categories.edit', compact('category'));
    }

    // Cập nhật category (PUT /api/category/categories/{category_id})
    public function update(Request $request, $id)
    {
        $token = session('token');
        $validated = $request->validate([
            'name' => 'required',
            'description' => 'nullable',
        ]);
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->put(env('GATEWAY_URL', 'https://f628-1-54-69-3.ngrok-free.app') . "/api/category/categories/{$id}", $validated);
        if ($response->successful()) {
            return redirect()->route('admin.categories.index')->with('success', 'Cập nhật category thành công!');
        }
        return back()->withErrors(['error' => 'Cập nhật category thất bại!']);
    }

    // Xóa category (DELETE /api/category/categories/{category_id})
    public function destroy($id)
    {
        $token = session('token');
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->delete(env('GATEWAY_URL', 'https://f628-1-54-69-3.ngrok-free.app') . "/api/category/categories/{$id}");
        if ($response->successful()) {
            return redirect()->route('admin.categories.index')->with('success', 'Xóa category thành công!');
        }
        return back()->withErrors(['error' => 'Xóa category thất bại!']);
    }

    public function subcategories($id) {}
    public function tree() {}
} 