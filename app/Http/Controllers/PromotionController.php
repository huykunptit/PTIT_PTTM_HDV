<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PromotionController extends Controller
{
    protected $baseUrl;

    public function __construct()
    {
        $this->baseUrl = env('GATEWAY_URL', 'http://localhost:8000') . '/api/v1/promotion/promotions';
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
        dd(session('token'));
        $token = $this->getTokenOrRedirect();
        if ($token instanceof \Illuminate\Http\RedirectResponse) return $token;
        $res = Http::withToken($token)->get("{$this->baseUrl}");
        $promotions = $res->successful() ? $res->json() : [];
        return view('admin.promotions.index', compact('promotions'));
    }

    public function create()
    {
        $token = $this->getTokenOrRedirect();
        if ($token instanceof \Illuminate\Http\RedirectResponse) return $token;
        return view('admin.promotions.create');
    }

    public function store(Request $request)
    {
        $token = $this->getTokenOrRedirect();
        if ($token instanceof \Illuminate\Http\RedirectResponse) return $token;
        $validated = $request->validate([
            'name' => 'required',
            'description' => 'nullable',
            'discount_percent' => 'required|numeric',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'is_active' => 'boolean',
        ]);
        $res = Http::withToken($token)->post($this->baseUrl, $validated);
        return $res->successful()
            ? redirect()->route('admin.promotions.index')->with('success', 'Tạo promotion thành công')
            : back()->withErrors(['error' => 'Tạo promotion thất bại']);
    }

    public function edit($id)
    {
        $token = $this->getTokenOrRedirect();
        if ($token instanceof \Illuminate\Http\RedirectResponse) return $token;
        $res = Http::withToken($token)->get("{$this->baseUrl}/{$id}");
        $promotion = $res->successful() ? $res->json() : null;
        return view('admin.promotions.edit', compact('promotion'));
    }

    public function update(Request $request, $id)
    {
        $token = $this->getTokenOrRedirect();
        if ($token instanceof \Illuminate\Http\RedirectResponse) return $token;
        $validated = $request->validate([
            'name' => 'required',
            'description' => 'nullable',
            'discount_percent' => 'required|numeric',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'is_active' => 'boolean',
        ]);
        $res = Http::withToken($token)->put("{$this->baseUrl}/{$id}", $validated);
        return $res->successful()
            ? redirect()->route('admin.promotions.index')->with('success', 'Cập nhật promotion thành công')
            : back()->withErrors(['error' => 'Cập nhật promotion thất bại']);
    }

    public function destroy($id)
    {
        $token = $this->getTokenOrRedirect();
        if ($token instanceof \Illuminate\Http\RedirectResponse) return $token;
        $res = Http::withToken($token)->delete("{$this->baseUrl}/{$id}");
        return redirect()->route('admin.promotions.index')->with(
            $res->successful() ? ['success' => 'Xóa promotion thành công'] : ['error' => 'Xóa promotion thất bại']
        );
    }

    public function getPromotionProducts($id)
    {
        $token = $this->getTokenOrRedirect();
        if ($token instanceof \Illuminate\Http\RedirectResponse) return $token;
        $res = Http::withToken($token)->get("{$this->baseUrl}/{$id}/products");
        $products = $res->successful() ? $res->json() : [];
        return view('admin.promotions.products', compact('products', 'id'));
    }

    public function addPromotionProduct(Request $request, $id)
    {
        $token = $this->getTokenOrRedirect();
        if ($token instanceof \Illuminate\Http\RedirectResponse) return $token;
        $validated = $request->validate(['product_id' => 'required|integer']);
        $res = Http::withToken($token)->post("{$this->baseUrl}/{$id}/products", $validated);
        return back()->with($res->successful() ? ['success' => 'Thêm sản phẩm thành công'] : ['error' => 'Thêm sản phẩm thất bại']);
    }

    public function removePromotionProduct($id, $product_id)
    {
        $token = $this->getTokenOrRedirect();
        if ($token instanceof \Illuminate\Http\RedirectResponse) return $token;
        $res = Http::withToken($token)->delete("{$this->baseUrl}/{$id}/products/{$product_id}");
        return back()->with($res->successful() ? ['success' => 'Xóa sản phẩm thành công'] : ['error' => 'Xóa sản phẩm thất bại']);
    }

    public function getPromotionCategories($id)
    {
        $token = $this->getTokenOrRedirect();
        if ($token instanceof \Illuminate\Http\RedirectResponse) return $token;
        $res = Http::withToken($token)->get("{$this->baseUrl}/{$id}/categories");
        $categories = $res->successful() ? $res->json() : [];
        return view('admin.promotions.categories', compact('categories', 'id'));
    }

    public function addPromotionCategory(Request $request, $id)
    {
        $token = $this->getTokenOrRedirect();
        if ($token instanceof \Illuminate\Http\RedirectResponse) return $token;
        $validated = $request->validate(['category_id' => 'required|integer']);
        $res = Http::withToken($token)->post("{$this->baseUrl}/{$id}/categories", $validated);
        return back()->with($res->successful() ? ['success' => 'Thêm category thành công'] : ['error' => 'Thêm category thất bại']);
    }

    public function removePromotionCategory($id, $category_id)
    {
        $token = $this->getTokenOrRedirect();
        if ($token instanceof \Illuminate\Http\RedirectResponse) return $token;
        $res = Http::withToken($token)->delete("{$this->baseUrl}/{$id}/categories/{$category_id}");
        return back()->with($res->successful() ? ['success' => 'Xóa category thành công'] : ['error' => 'Xóa category thất bại']);
    }

    public function getPromotionUsage($id)
    {
        $token = $this->getTokenOrRedirect();
        if ($token instanceof \Illuminate\Http\RedirectResponse) return $token;
        $res = Http::withToken($token)->get("{$this->baseUrl}/{$id}/usage");
        $usage = $res->successful() ? $res->json() : [];
        return view('admin.promotions.usage', compact('usage', 'id'));
    }

    public function applyPromotion(Request $request, $id)
    {
        $token = $this->getTokenOrRedirect();
        if ($token instanceof \Illuminate\Http\RedirectResponse) return $token;
        $validated = $request->validate(['promotion_code' => 'required|string']);
        $res = Http::withToken($token)->post("{$this->baseUrl}/{$id}/apply", $validated);
        return back()->with($res->successful() ? ['success' => 'Áp dụng promotion thành công'] : ['error' => 'Áp dụng promotion thất bại']);
    }

    public function getActivePromotions()
    {
        $token = $this->getTokenOrRedirect();
        if ($token instanceof \Illuminate\Http\RedirectResponse) return $token;
        $res = Http::withToken($token)->get("{$this->baseUrl}/active");
        $promotions = $res->successful() ? $res->json() : [];
        return view('admin.promotions.active', compact('promotions'));
    }

    public function login(Request $request)
    {
        // ... validate và gọi API login ...
        $response = Http::post(env('GATEWAY_URL') . '/api/v1/auth/login', [
            'email' => $request->email,
            'password' => $request->password,
        ]);

        if ($response->successful() && isset($response['token'])) {
            $token = $response['token'];

            // Gọi API verify/profile
            $verify = Http::withToken($token)->get(env('GATEWAY_URL') . '/api/v1/auth/me');
            if ($verify->successful()) {
                session(['token' => $token]);
                // Có thể lưu thêm user info nếu muốn
                return redirect()->route('admin.dashboard');
            } else {
                return back()->withErrors(['error' => 'Token không hợp lệ, vui lòng thử lại!']);
            }
        }

        return back()->withErrors(['error' => 'Đăng nhập thất bại!']);
    }
}
