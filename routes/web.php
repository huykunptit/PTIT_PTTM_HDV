<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\MachineController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\AdminPaymentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout.custom');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
// Password Reset Routes
Route::get('/password/reset', [AuthController::class, 'showPasswordResetForm'])->name('password.request');
Route::post('/password/email', [AuthController::class, 'sendPasswordResetLink'])->name('password.email');
Route::get('/password/reset/{token}', [AuthController::class, 'showPasswordResetForm'])->name('password.reset');
Route::post('/password/reset', [AuthController::class, 'resetPassword'])->name('password.update');

// Protected Routes
Route::middleware(['api.auth'])->group(function () {
    
    // User Shop Routes
    Route::prefix('shop')->name('shop.')->group(function () {
        Route::get('/', [ShopController::class, 'dashboard'])->name('index');
        Route::get('/products', [ShopController::class, 'products'])->name('products');
        Route::get('/cart', [CartController::class, 'index'])->name('cart');
        Route::post('/orders', [CartController::class, 'createOrder'])->name('create-order');
        Route::get('/orders', [CartController::class, 'orders'])->name('orders');
        Route::get('/orders/{id}', [CartController::class, 'orderDetails'])->name('order-details');
        Route::put('/orders/{id}/cancel', [CartController::class, 'cancelOrder'])->name('cancel-order');
        Route::get('/checkout', [ShopController::class, 'checkout'])->name('checkout');
        Route::get('/profile', [ShopController::class, 'profile'])->name('profile');
        Route::get('/promotions', [PromotionController::class, 'index'])->name('promotions');
    });
    
    // Cart API Routes (for AJAX calls)
    Route::prefix('api/cart')->name('cart.')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('index');
        Route::post('/add', [CartController::class, 'add'])->name('add');
        Route::put('/update', [CartController::class, 'update'])->name('update');
        Route::delete('/remove', [CartController::class, 'remove'])->name('remove');
        Route::delete('/clear', [CartController::class, 'clear'])->name('clear');
        Route::post('/apply-promotion', [CartController::class, 'applyPromotion'])->name('apply-promotion');
        Route::delete('/remove-promotion', [CartController::class, 'removePromotion'])->name('remove-promotion');
        Route::get('/applied-promotion', [CartController::class, 'getAppliedPromotion'])->name('applied-promotion');
    });

    // Product API Routes (for AJAX calls)
    Route::prefix('api/products')->name('products.')->group(function () {
        Route::get('/', [ShopController::class, 'getProducts'])->name('index');
        Route::get('/categories', [ShopController::class, 'getCategories'])->name('categories');
    });

    // Order API Routes (for AJAX calls)
    Route::prefix('api/orders')->name('orders.')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::post('/', [OrderController::class, 'store'])->name('store');
        Route::put('/{id}/cancel', [OrderController::class, 'cancel'])->name('cancel');
    });

    // Promotion API Routes (for AJAX calls)
    Route::prefix('api/promotions')->name('promotions.')->group(function () {
        Route::get('/active/{productId}', [PromotionController::class, 'getActivePromotions'])->name('active');
        Route::post('/apply', [PromotionController::class, 'applyPromotion'])->name('apply');
        Route::delete('/remove', [PromotionController::class, 'removePromotion'])->name('remove');
        Route::get('/applied', [PromotionController::class, 'getAppliedPromotion'])->name('applied');
    });

    // API Routes (for AJAX calls)
    Route::prefix('api')->name('api.')->group(function () {
        Route::get('/products', [ShopController::class, 'getProducts'])->name('products');
        Route::get('/categories', [ShopController::class, 'getCategories'])->name('categories');
    });

    // User Wallet Route
    Route::get('/wallet', [WalletController::class, 'statistics'])->name('wallet');
    
    // Payment Routes
    Route::prefix('payment')->name('payment.')->group(function () {
        Route::get('/', [PaymentController::class, 'showPaymentForm'])->name('index');
        Route::post('/vnpay', [PaymentController::class, 'createVNPayPayment'])->name('vnpay');
        Route::get('/vnpay-return', [PaymentController::class, 'vnpayReturn'])->name('vnpay-return');
        Route::get('/history', [PaymentController::class, 'paymentHistory'])->name('history');
    });
    
    // Wallet API Routes
    Route::prefix('api/wallet')->name('wallet.')->group(function () {
        Route::post('/payment', [WalletController::class, 'payment'])->name('payment');
        Route::post('/deposit', [WalletController::class, 'deposit'])->name('deposit');
        Route::get('/transactions/filter', [WalletController::class, 'filterTransactions'])->name('transactions.filter');
        Route::get('/balance', [WalletController::class, 'getBalance'])->name('balance');
    });
});

Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function() {
        return view('admin.dashboard');
    })->name('dashboard');
    Route::resource('users', UserController::class)
        ->names([
            'index' => 'users.index',
            'create' => 'users.create',
            'store' => 'users.store',
            'edit' => 'users.edit',
            'update' => 'users.update',
            'destroy' => 'users.destroy',
        ]);
    Route::resource('categories', CategoryController::class)
        ->names([
            'index' => 'categories.index',
            'create' => 'categories.create',
            'store' => 'categories.store',
            'edit' => 'categories.edit',
            'update' => 'categories.update',
            'destroy' => 'categories.destroy',
        ]);
    Route::post('products/{id}/upload-image', [ProductController::class, 'uploadImage'])->name('products.uploadImage');
    Route::resource('products', ProductController::class)
        ->names([
            'index' => 'products.index',
            'create' => 'products.create',
            'store' => 'products.store',
            'edit' => 'products.edit',
            'update' => 'products.update',
            'destroy' => 'products.destroy',
        ]);


        Route::prefix('admin/products')->name('admin.products.')->middleware('auth')->group(function () {
            Route::get('/', [\App\Http\Controllers\ProductController::class, 'index'])->name('index');
            Route::get('/create', [\App\Http\Controllers\ProductController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\ProductController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [\App\Http\Controllers\ProductController::class, 'edit'])->name('edit');
            Route::put('/{id}', [\App\Http\Controllers\ProductController::class, 'update'])->name('update');
            Route::delete('/{id}', [\App\Http\Controllers\ProductController::class, 'destroy'])->name('destroy');
        });
        
        // Admin routes for night combo management
        Route::prefix('admin/night-combos')->name('admin.night-combos.')->middleware('auth')->group(function () {
            Route::get('/', [\App\Http\Controllers\NightComboController::class, 'index'])->name('index');
            Route::get('/create', [\App\Http\Controllers\NightComboController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\NightComboController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [\App\Http\Controllers\NightComboController::class, 'edit'])->name('edit');
            Route::put('/{id}', [\App\Http\Controllers\NightComboController::class, 'update'])->name('update');
            Route::delete('/{id}', [\App\Http\Controllers\NightComboController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('admin/promotions')->name('admin.promotions.')->middleware('auth')->group(function () {
            Route::get('/', [\App\Http\Controllers\PromotionController::class, 'index'])->name('index');
            Route::get('/create', [\App\Http\Controllers\PromotionController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\PromotionController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [\App\Http\Controllers\PromotionController::class, 'edit'])->name('edit');
            Route::put('/{id}', [\App\Http\Controllers\PromotionController::class, 'update'])->name('update');
            Route::delete('/{id}', [\App\Http\Controllers\PromotionController::class, 'destroy'])->name('destroy');
        
            // Promotion products management
            Route::get('/{id}/products', [\App\Http\Controllers\PromotionController::class, 'getPromotionProducts'])->name('products');
            Route::post('/{id}/products', [\App\Http\Controllers\PromotionController::class, 'addPromotionProduct'])->name('products.add');
            Route::delete('/{id}/products/{product_id}', [\App\Http\Controllers\PromotionController::class, 'removePromotionProduct'])->name('products.remove');
        
            // Promotion categories management
            Route::get('/{id}/categories', [\App\Http\Controllers\PromotionController::class, 'getPromotionCategories'])->name('categories');
            Route::post('/{id}/categories', [\App\Http\Controllers\PromotionController::class, 'addPromotionCategory'])->name('categories.add');
            Route::delete('/{id}/categories/{category_id}', [\App\Http\Controllers\PromotionController::class, 'removePromotionCategory'])->name('categories.remove');
        
            // Usage and application
            Route::get('/{id}/usage', [\App\Http\Controllers\PromotionController::class, 'getPromotionUsage'])->name('usage');
            Route::post('/{id}/apply', [\App\Http\Controllers\PromotionController::class, 'applyPromotion'])->name('apply');
            Route::get('/active', [\App\Http\Controllers\PromotionController::class, 'getActivePromotions'])->name('active');
        });
    Route::get('/test-route', fn() => route('admin.promotions.index'));

    // Admin routes for area management
    Route::resource('areas', AreaController::class)
        ->names([
            'index' => 'admin.areas.index',
            'create' => 'admin.areas.create',
            'store' => 'admin.areas.store',
            'edit' => 'admin.areas.edit',
            'update' => 'admin.areas.update',
            'destroy' => 'admin.areas.destroy',
        ]);

    // Admin routes for machine management
    Route::resource('machines', MachineController::class)
        ->names([
            'index' => 'admin.machines.index',
            'create' => 'admin.machines.create',
            'store' => 'admin.machines.store',
            'edit' => 'admin.machines.edit',
            'update' => 'admin.machines.update',
            'destroy' => 'admin.machines.destroy',
        ]);

    Route::get('/dashboard/machines-by-area', [\App\Http\Controllers\DashboardController::class, 'machinesByAreaDashboard'])->name('dashboard.machines-by-area');
    
    // Admin Payment Routes
    Route::prefix('payment')->name('payment.')->group(function () {
        Route::get('/', [AdminPaymentController::class, 'index'])->name('index');
        Route::post('/deposit', [AdminPaymentController::class, 'depositForUser'])->name('deposit');
        Route::post('/withdraw', [AdminPaymentController::class, 'withdrawFromUser'])->name('withdraw');
        Route::post('/bulk-deposit', [AdminPaymentController::class, 'bulkDeposit'])->name('bulk-deposit');
        Route::get('/export', [AdminPaymentController::class, 'exportTransactions'])->name('export');
        Route::get('/user/{userId}/wallet', [AdminPaymentController::class, 'getUserWallet'])->name('user.wallet');
        Route::get('/transactions', [AdminPaymentController::class, 'getTransactions'])->name('transactions');
        Route::prefix('admin')->group(function () {
            Route::post('/vnpay', [AdminPaymentController::class, 'createVnpayPayment'])->name('admin.payment.vnpay');
        });
        
    });

    // Test route để kiểm tra API response
    Route::get('/test-api', function() {
        $token = session('token');
        if (!$token) {
            return response()->json(['error' => 'No token']);
        }
        
        $gateway = env('GATEWAY_URL', 'http://localhost:8000');
        try {
            $response = \Illuminate\Support\Facades\Http::withToken($token)->get($gateway . '/api/account/admin/users/list');
            return response()->json([
                'status' => $response->status(),
                'data' => $response->json(),
                'data_type' => gettype($response->json())
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    })->name('test.api');

    Route::get('/admin/payment/vnpay-return', [\App\Http\Controllers\AdminPaymentController::class, 'vnpayReturn'])->name('admin.payment.vnpay_return');
    Route::get('/admin/payment/wallet-statistics', [\App\Http\Controllers\AdminPaymentController::class, 'getWalletStatistics'])->name('admin.payment.wallet_statistics');
    
    Route::post('/admin/payment/deposit', [\App\Http\Controllers\AdminPaymentController::class, 'depositForUser'])->name('admin.payment.deposit');
    Route::post('/admin/payment/withdraw', [\App\Http\Controllers\AdminPaymentController::class, 'withdrawFromUser'])->name('admin.payment.withdraw');
});