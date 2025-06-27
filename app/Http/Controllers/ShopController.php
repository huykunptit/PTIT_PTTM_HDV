<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ShopController extends Controller
{
    private $gatewayUrl = 'https://f628-1-54-69-3.ngrok-free.app';

    public function index()
    {
        try {
            $token = session('token');
            
            // Get products
            $productsResponse = Http::withHeaders(['Authorization' => 'Bearer ' . $token])
                ->get($this->gatewayUrl . '/api/product/products');
            $products = $productsResponse->successful() ? $productsResponse->json() : [];

            // Get cart
            $cartResponse = Http::withHeaders(['Authorization' => 'Bearer ' . $token])
                ->get($this->gatewayUrl . '/api/cart');
            $cart = $cartResponse->successful() ? $cartResponse->json() : null;

            return view('shop.index', compact('products', 'cart'));
        } catch (\Exception $e) {
            return view('shop.index', ['products' => [], 'cart' => null]);
        }
    }

    public function products()
    {
        try {
            $token = session('token');
            
            // Get all products
            $productsResponse = Http::withHeaders(['Authorization' => 'Bearer ' . $token])
                ->get($this->gatewayUrl . '/api/product/products');
            $products = $productsResponse->successful() ? $productsResponse->json() : [];

            // Get categories
            $categoriesResponse = Http::withHeaders(['Authorization' => 'Bearer ' . $token])
                ->get($this->gatewayUrl . '/api/category/categories');
            $categories = $categoriesResponse->successful() ? $categoriesResponse->json() : [];

            // Create a map of category_id to category name for easy lookup
            $categoryMap = [];
            foreach ($categories as $category) {
                $categoryMap[$category['id']] = $category['name'];
            }

            // Add category name to each product
            foreach ($products as &$product) {
                $product['category_name'] = $categoryMap[$product['category_id']] ?? 'Không phân loại';
            }

            // Sort products by category_id
            usort($products, function($a, $b) {
                return $a['category_id'] <=> $b['category_id'];
            });

            return view('shop.products', compact('products', 'categories'));
        } catch (\Exception $e) {
            return view('shop.products', ['products' => [], 'categories' => []]);
        }
    }

    public function productDetail($id)
    {
        return view('shop.product-detail', compact('id'));
    }

    public function cart()
    {
        try {
            $token = session('token');
            $response = Http::withHeaders(['Authorization' => 'Bearer ' . $token])
                ->get($this->gatewayUrl . '/api/cart');

            $cart = $response->successful() ? $response->json() : null;
            return view('shop.cart', compact('cart'));
        } catch (\Exception $e) {
            return view('shop.cart', ['cart' => null]);
        }
    }

    public function checkout()
    {
        try {
            $token = session('token');
            $user = session('user');
            
            // Get cart
            $cartResponse = Http::withHeaders(['Authorization' => 'Bearer ' . $token])
                ->get($this->gatewayUrl . '/api/cart');
            $cart = $cartResponse->successful() ? $cartResponse->json() : null;

            // Get user profile
            $profileResponse = Http::withHeaders(['Authorization' => 'Bearer ' . $token])
                ->get($this->gatewayUrl . '/api/account/users/' . $user['id'] . '/information');
            $profile = $profileResponse->successful() ? $profileResponse->json() : null;

            return view('shop.checkout', compact('cart', 'profile'));
        } catch (\Exception $e) {
            return view('shop.checkout', ['cart' => null, 'profile' => null]);
        }
    }

    public function profile()
    {
        try {
            $token = session('token');
            $user = session('user');
            $url = route('api.account.users.information', ['user_id' => $user['id']]);
            $response = \Illuminate\Support\Facades\Http::withHeaders([
                'Authorization' => 'Bearer ' . $token
            ])->get($url);
            $profile = $response->successful() ? $response->json() : null;
            return view('shop.profile', compact('profile'));
        } catch (\Exception $e) {
            return view('shop.profile', ['profile' => null]);
        }
    }

    public function orders()
    {
        try {
            $token = session('token');
            $user = session('user');
            
            $response = Http::withHeaders(['Authorization' => 'Bearer ' . $token])
                ->get($this->gatewayUrl . '/api/game-session/game-sessions', [
                    'user_id' => $user['id'],
                    'page' => request('page', 1),
                    'per_page' => request('per_page', 10),
                ]);

            $sessions = $response->successful() ? $response->json() : [];
            return view('shop.orders', compact('sessions'));
        } catch (\Exception $e) {
            return view('shop.orders', ['sessions' => []]);
        }
    }

    public function dashboard()
    {
        // Debug: Kiểm tra session data
        // dd([
        //     'user' => session('user'),
        //     'account' => session('account'),
        //     'playtime' => session('playtime'),
        //     'token' => session('token'),
        // ]);
        
        return view('shop.dashboard');
    }

    public function getProducts()
    {
        try {
            $token = session('token');
            
            // Get all products
            $productsResponse = Http::withHeaders(['Authorization' => 'Bearer ' . $token])
                ->get($this->gatewayUrl . '/api/product/products');
            $products = $productsResponse->successful() ? $productsResponse->json() : [];

            // Get categories
            $categoriesResponse = Http::withHeaders(['Authorization' => 'Bearer ' . $token])
                ->get($this->gatewayUrl . '/api/category/categories');
            $categories = $categoriesResponse->successful() ? $categoriesResponse->json() : [];

            // Create a map of category_id to category name for easy lookup
            $categoryMap = [];
            foreach ($categories as $category) {
                $categoryMap[$category['id']] = $category['name'];
            }

            // Add category name to each product
            foreach ($products as &$product) {
                $product['category_name'] = $categoryMap[$product['category_id']] ?? 'Không phân loại';
            }

            // Sort products by category_id
            usort($products, function($a, $b) {
                return $a['category_id'] <=> $b['category_id'];
            });

            return response()->json($products);
        } catch (\Exception $e) {
            return response()->json([]);
        }
    }

    public function getCategories()
    {
        try {
            $token = session('token');
            $response = Http::withHeaders(['Authorization' => 'Bearer ' . $token])
                ->get($this->gatewayUrl . '/api/category/categories');

            return response()->json($response->successful() ? $response->json() : []);
        } catch (\Exception $e) {
            return response()->json([]);
        }
    }
} 