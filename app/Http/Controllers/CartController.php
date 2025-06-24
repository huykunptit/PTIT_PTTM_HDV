<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Get cart details
     */
    public function getCart(): JsonResponse
    {
        try {
            $userId = Auth::id();
            
            $cart = DB::table('carts')
                ->where('user_id', $userId)
                ->where('status', 'active')
                ->first();

            if (!$cart) {
                // Create new cart if doesn't exist
                $cartId = DB::table('carts')->insertGetId([
                    'user_id' => $userId,
                    'total_amount' => 0,
                    'total_items' => 0,
                    'status' => 'active',
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                
                $cart = DB::table('carts')->where('id', $cartId)->first();
            }

            // Get cart items with product details
            $items = DB::table('cart_items as ci')
                ->join('products as p', 'ci.product_id', '=', 'p.id')
                ->select('ci.*', 'p.name as product_name', 'p.price as product_price', 'p.stock as product_stock')
                ->where('ci.cart_id', $cart->id)
                ->get()
                ->map(function ($item) {
                    $item->product = [
                        'id' => $item->product_id,
                        'name' => $item->product_name,
                        'price' => $item->product_price,
                        'stock' => $item->product_stock
                    ];
                    unset($item->product_name, $item->product_price, $item->product_stock);
                    return $item;
                });

            $cart->items = $items;

            return response()->json($cart);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve cart'], 500);
        }
    }

    /**
     * Add item to cart
     */
    public function addItem(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|integer|exists:products,id',
            'quantity' => 'required|integer|min:1|max:100'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 400);
        }

        try {
            $userId = Auth::id();
            $productId = $request->product_id;
            $quantity = $request->quantity;

            // Check product stock
            $product = DB::table('products')->where('id', $productId)->first();
            if (!$product || $product->stock < $quantity) {
                return response()->json(['error' => 'Insufficient stock'], 400);
            }

            DB::beginTransaction();

            // Get or create cart
            $cart = DB::table('carts')
                ->where('user_id', $userId)
                ->where('status', 'active')
                ->first();

            if (!$cart) {
                $cartId = DB::table('carts')->insertGetId([
                    'user_id' => $userId,
                    'total_amount' => 0,
                    'total_items' => 0,
                    'status' => 'active',
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            } else {
                $cartId = $cart->id;
            }

            // Check if item already exists in cart
            $existingItem = DB::table('cart_items')
                ->where('cart_id', $cartId)
                ->where('product_id', $productId)
                ->first();

            $subtotal = $product->price * $quantity;

            if ($existingItem) {
                // Update existing item
                $newQuantity = $existingItem->quantity + $quantity;
                $newSubtotal = $product->price * $newQuantity;
                
                DB::table('cart_items')
                    ->where('id', $existingItem->id)
                    ->update([
                        'quantity' => $newQuantity,
                        'subtotal' => $newSubtotal,
                        'updated_at' => now()
                    ]);
            } else {
                // Add new item
                DB::table('cart_items')->insert([
                    'cart_id' => $cartId,
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'price' => $product->price,
                    'subtotal' => $subtotal,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            // Update cart totals
            $this->updateCartTotals($cartId);

            DB::commit();

            return $this->getCart();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to add item to cart'], 500);
        }
    }

    /**
     * Update cart item quantity
     */
    public function updateItem(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|integer',
            'quantity' => 'required|integer|min:1|max:100'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 400);
        }

        try {
            $userId = Auth::id();
            $productId = $request->product_id;
            $quantity = $request->quantity;

            $cart = DB::table('carts')
                ->where('user_id', $userId)
                ->where('status', 'active')
                ->first();

            if (!$cart) {
                return response()->json(['error' => 'Cart not found'], 404);
            }

            $cartItem = DB::table('cart_items')
                ->where('cart_id', $cart->id)
                ->where('product_id', $productId)
                ->first();

            if (!$cartItem) {
                return response()->json(['error' => 'Item not found in cart'], 404);
            }

            // Check product stock
            $product = DB::table('products')->where('id', $productId)->first();
            if (!$product || $product->stock < $quantity) {
                return response()->json(['error' => 'Insufficient stock'], 400);
            }

            DB::beginTransaction();

            $subtotal = $product->price * $quantity;

            DB::table('cart_items')
                ->where('id', $cartItem->id)
                ->update([
                    'quantity' => $quantity,
                    'subtotal' => $subtotal,
                    'updated_at' => now()
                ]);

            $this->updateCartTotals($cart->id);

            DB::commit();

            return $this->getCart();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to update cart item'], 500);
        }
    }

    /**
     * Remove item from cart
     */
    public function removeItem(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 400);
        }

        try {
            $userId = Auth::id();
            $productId = $request->product_id;

            $cart = DB::table('carts')
                ->where('user_id', $userId)
                ->where('status', 'active')
                ->first();

            if (!$cart) {
                return response()->json(['error' => 'Cart not found'], 404);
            }

            DB::beginTransaction();

            $deleted = DB::table('cart_items')
                ->where('cart_id', $cart->id)
                ->where('product_id', $productId)
                ->delete();

            if (!$deleted) {
                return response()->json(['error' => 'Item not found in cart'], 404);
            }

            $this->updateCartTotals($cart->id);

            DB::commit();

            return $this->getCart();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to remove item from cart'], 500);
        }
    }

    /**
     * Clear cart
     */
    public function clearCart(): JsonResponse
    {
        try {
            $userId = Auth::id();

            $cart = DB::table('carts')
                ->where('user_id', $userId)
                ->where('status', 'active')
                ->first();

            if (!$cart) {
                return response()->json(['error' => 'Cart not found'], 404);
            }

            DB::beginTransaction();

            DB::table('cart_items')->where('cart_id', $cart->id)->delete();

            DB::table('carts')
                ->where('id', $cart->id)
                ->update([
                    'total_amount' => 0,
                    'total_items' => 0,
                    'updated_at' => now()
                ]);

            DB::commit();

            return $this->getCart();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to clear cart'], 500);
        }
    }

    /**
     * Update cart totals
     */
    private function updateCartTotals($cartId): void
    {
        $totals = DB::table('cart_items')
            ->where('cart_id', $cartId)
            ->selectRaw('SUM(subtotal) as total_amount, SUM(quantity) as total_items')
            ->first();

        DB::table('carts')
            ->where('id', $cartId)
            ->update([
                'total_amount' => $totals->total_amount ?? 0,
                'total_items' => $totals->total_items ?? 0,
                'updated_at' => now()
            ]);
    }
}