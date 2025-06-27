<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ProductController extends Controller
{
    public function index()
    {
        $token = session('token');
        $res = Http::withToken($token)->get(env('GATEWAY_URL') . '/api/product/products');
        $products = $res->successful() ? $res->json() : [];
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $token = session('token');
        $res = Http::withToken($token)->get(env('GATEWAY_URL') . '/api/category/categories');
        $categories = $res->successful() ? $res->json() : [];
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $token = session('token');
        $validated = $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'category_id' => 'required|integer',
        ]);

        $res = Http::withToken($token)->post(env('GATEWAY_URL') . '/api/product/products', $validated);
        if ($res->successful()) {
            $id = $res->json()['id'] ?? null;
            if ($id && $request->hasFile('image')) {
                Http::withToken($token)->attach(
                    'image', file_get_contents($request->file('image')->getRealPath()), $request->file('image')->getClientOriginalName()
                )->post(env('GATEWAY_URL') . "/api/product/products/$id/upload-image");
            }
            return redirect()->route('admin.products.index')->with('success', 'Tạo sản phẩm thành công');
        }
        dd($res->json());
        return back()->withErrors(['error' => 'Tạo sản phẩm thất bại']);
    }

    public function edit($id)
    {
        $token = session('token');
        $res = Http::withToken($token)->get(env('GATEWAY_URL') . "/api/product/products/$id");
        $product = $res->successful() ? $res->json() : null;

        $catRes = Http::withToken($token)->get(env('GATEWAY_URL') . '/api/category/categories');
        $categories = $catRes->successful() ? $catRes->json() : [];

        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $token = session('token');
        $validated = $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'category_id' => 'required|integer',
        ]);

        $res = Http::withToken($token)->put(env('GATEWAY_URL') . "/api/product/products/$id", $validated);
        if ($res->successful()) {
            if ($request->hasFile('image')) {
                Http::withToken($token)->attach(
                    'image', file_get_contents($request->file('image')->getRealPath()), $request->file('image')->getClientOriginalName()
                )->post(env('GATEWAY_URL') . "/api/product/products/$id/upload-image");
            }
            return redirect()->route('admin.products.index')->with('success', 'Cập nhật sản phẩm thành công');
        }
        return back()->withErrors(['error' => 'Cập nhật sản phẩm thất bại']);
    }

    public function destroy($id)
    {
        $token = session('token');
        $res = Http::withToken($token)->delete(env('GATEWAY_URL') . "/api/product/products/$id");
        return redirect()->route('admin.products.index')->with(
            $res->successful() ? ['success' => 'Xóa sản phẩm thành công'] : ['error' => 'Xóa sản phẩm thất bại']
        );
    }
}