<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Auth routes (no middleware for register, login, password reset)
Route::post('/account/auth/register', [App\Http\Controllers\AuthController::class, 'register']);
Route::post('/account/auth/login', [App\Http\Controllers\AuthController::class, 'login']);
Route::post('/account/auth/password/reset-request', [App\Http\Controllers\AuthController::class, 'passwordResetRequest']);
Route::post('/account/auth/password/reset-confirm', [App\Http\Controllers\AuthController::class, 'passwordResetConfirm']);
Route::post('/account/auth/email/verify', [App\Http\Controllers\AuthController::class, 'emailVerify']);
Route::post('/account/auth/email/resend', [App\Http\Controllers\AuthController::class, 'emailResend']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth protected routes
    Route::post('/account/auth/logout', [App\Http\Controllers\AuthController::class, 'logout']);
    Route::get('/account/auth/sessions', [App\Http\Controllers\AuthController::class, 'sessions']);
    Route::delete('/account/auth/sessions', [App\Http\Controllers\AuthController::class, 'revokeAllSessions']);
    Route::delete('/account/auth/sessions/{token}', [App\Http\Controllers\AuthController::class, 'revokeSession']);

    // User routes
    // Route::get('/account/users/{user_id}/information', ...
    // Route::put('/account/users/{user_id}/update', ...
    // Route::delete('/account/admin/delete/{user_id}', ...
    // Route::get('/account/admin/users/list', ...
    // Route::resource('users', UserController::class);

    // Role routes
    Route::get('/roles', [App\Http\Controllers\RoleController::class, 'index']);
    Route::post('/roles', [App\Http\Controllers\RoleController::class, 'store']);
    Route::get('/roles/{id}', [App\Http\Controllers\RoleController::class, 'show']);
    Route::put('/roles/{id}', [App\Http\Controllers\RoleController::class, 'update']);
    Route::delete('/roles/{id}', [App\Http\Controllers\RoleController::class, 'destroy']);

    // Cart
    Route::get('/cart', [App\Http\Controllers\CartController::class, 'getCart']);
    Route::post('/cart/add', [App\Http\Controllers\CartController::class, 'addItem']);
    Route::put('/cart/update', [App\Http\Controllers\CartController::class, 'updateItem']);
    Route::delete('/cart/remove', [App\Http\Controllers\CartController::class, 'removeItem']);
    Route::delete('/cart/clear', [App\Http\Controllers\CartController::class, 'clearCart']);

    // Category
    Route::get('/category/categories', [App\Http\Controllers\CategoryController::class, 'index']);
    Route::post('/category/categories', [App\Http\Controllers\CategoryController::class, 'store']);
    Route::get('/category/categories/tree', [App\Http\Controllers\CategoryController::class, 'tree']);
    Route::get('/category/categories/{category_id}', [App\Http\Controllers\CategoryController::class, 'show']);
    Route::put('/category/categories/{category_id}', [App\Http\Controllers\CategoryController::class, 'update']);
    Route::delete('/category/categories/{category_id}', [App\Http\Controllers\CategoryController::class, 'destroy']);
    Route::get('/category/categories/{category_id}/subcategories', [App\Http\Controllers\CategoryController::class, 'subcategories']);

    // Product
    Route::get('/product/products', [App\Http\Controllers\ProductController::class, 'index']);
    Route::post('/product/products', [App\Http\Controllers\ProductController::class, 'store']);
    Route::get('/product/products/search', [App\Http\Controllers\ProductController::class, 'search']);
    Route::get('/product/products/category/{category_id}', [App\Http\Controllers\ProductController::class, 'getByCategory']);
    Route::get('/product/products/{id}', [App\Http\Controllers\ProductController::class, 'show']);
    Route::put('/product/products/{id}', [App\Http\Controllers\ProductController::class, 'update']);
    Route::delete('/product/products/{id}', [App\Http\Controllers\ProductController::class, 'destroy']);

    // Wallet
    Route::get('/wallet/statistics', [App\Http\Controllers\WalletController::class, 'statistics']);
    Route::get('/wallet/transactions/filter', [App\Http\Controllers\WalletController::class, 'filterTransactions']);

    // Notification
    Route::get('/notifications/preferences', [App\Http\Controllers\NotificationController::class, 'getPreferences']);
    Route::put('/notifications/preferences', [App\Http\Controllers\NotificationController::class, 'updatePreferences']);
    Route::post('/notifications/mark-read', [App\Http\Controllers\NotificationController::class, 'markRead']);

    // Game Session
    Route::get('/game-session/game-sessions', [App\Http\Controllers\GameSessionController::class, 'index']);
    Route::post('/game-session/game-sessions', [App\Http\Controllers\GameSessionController::class, 'store']);
    Route::get('/game-session/game-sessions/{id}', [App\Http\Controllers\GameSessionController::class, 'show']);
    Route::put('/game-session/game-sessions/{id}', [App\Http\Controllers\GameSessionController::class, 'update']);
    Route::delete('/game-session/game-sessions/{id}', [App\Http\Controllers\GameSessionController::class, 'destroy']);
    Route::get('/game-session/game-sessions/check-expired', [App\Http\Controllers\GameSessionController::class, 'checkExpired']);
    Route::get('/game-sessions/statistics', [App\Http\Controllers\GameSessionController::class, 'statistics']);
    Route::get('/game-sessions/analytics', [App\Http\Controllers\GameSessionController::class, 'analytics']);

    // Area
    Route::get('/area/areas', [App\Http\Controllers\AreaController::class, 'index']);
    Route::post('/area/areas', [App\Http\Controllers\AreaController::class, 'store']);
    Route::get('/area/areas/{area_id}', [App\Http\Controllers\AreaController::class, 'show']);
    Route::put('/area/areas/{area_id}', [App\Http\Controllers\AreaController::class, 'update']);
    Route::delete('/area/areas/{area_id}', [App\Http\Controllers\AreaController::class, 'destroy']);

    // Machine
    Route::get('/machine/machines', [App\Http\Controllers\MachineController::class, 'index'])->name('api.machine.machines.index');
    Route::post('/machine/machines', [App\Http\Controllers\MachineController::class, 'store'])->name('api.machine.machines.store');
    Route::get('/machine/machines/{id}', [App\Http\Controllers\MachineController::class, 'show'])->name('api.machine.machines.show');
    Route::put('/machine/machines/{id}', [App\Http\Controllers\MachineController::class, 'update'])->name('api.machine.machines.update');
    Route::delete('/machine/machines/{id}', [App\Http\Controllers\MachineController::class, 'destroy'])->name('api.machine.machines.destroy');
});

Route::post('/admin/machines', [App\Http\Controllers\MachineController::class, 'store'])->name('admin.machines.store');
Route::put('/admin/machines/{id}', [App\Http\Controllers\MachineController::class, 'update'])->name('admin.machines.update');
Route::delete('/admin/machines/{id}', [App\Http\Controllers\MachineController::class, 'destroy'])->name('admin.machines.destroy');

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
    ]);;

    // Bổ sung các route name cho sidebar
    Route::view('/products', 'admin.products')->name('products');
    Route::view('/orders', 'admin.orders')->name('orders');
    Route::view('/promotions', 'admin.promotions')->name('promotions');
    Route::view('/machines', 'admin.machines')->name('machines');
    Route::view('/sessions', 'admin.sessions')->name('sessions');
    Route::view('/reports', 'admin.reports')->name('reports');
});
