<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\CategoryController;
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
    return view('auth.login')->name;
});

Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');
// Auth routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register'); // Thêm GET route
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/logout', [AuthController::class, 'logout'])->name('logout.post');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout'); // Thêm GET route
Route::get('/auth/verify', [AuthController::class, 'verify']);
Route::post('/auth/password/reset-request', [AuthController::class, 'requestPasswordReset']);
Route::post('/auth/password/reset-confirm', [AuthController::class, 'confirmPasswordReset']);
Route::get('/password/reset', [AuthController::class, 'showResetRequestForm'])->name('password.request'); // Thêm
Route::get('/password/reset/{token}', [AuthController::class, 'showResetForm'])->name('password.reset'); // Thêm
Route::post('/auth/email/verify', [AuthController::class, 'verifyEmail']);
Route::post('/auth/email/resend', [AuthController::class, 'resendVerification']);
Route::get('/auth/sessions', [AuthController::class, 'getActiveSessions']);
Route::delete('/auth/sessions', [AuthController::class, 'revokeAllSessions']);
Route::delete('/auth/sessions/{session_id}', [AuthController::class, 'revokeSession']);
Route::get('/sessions', [AuthController::class, 'showSessionsPage'])->name('sessions');


//Role routes
Route::get('/roles', [RolesController::class, 'list_roles'])->name('roles.list');
Route::get('/roles/create', [RolesController::class, 'create_role'])->name('roles.create');
Route::post('/roles/store', [RolesController::class, 'store_role'])->name('roles.store');
Route::get('/roles/edit/{id}', [RolesController::class, 'edit_role'])->name('roles.edit');
// Route::post('/roles/update/{id}', [RolesController::class, 'update_role'])->name('roles.update');
Route::delete('/roles/delete/{id}', [RolesController::class, 'delete_role'])->name('roles.delete');
Route::get('/roles/{id}', [RolesController::class, 'get_roles'])->name('roles.show');
//update role routes
Route::put('/roles/update/{id}', [RolesController::class, 'update_roles'])->name('roles.update');


Route::resource('categories', CategoryController::class);
Route::prefix('api/category')->name('api.category.')->group(function () {
    Route::get('/list', [CategoryController::class, 'index'])->name('list'); // List categories (index method)
    Route::get('/{id}', [CategoryController::class, 'getCategory'])->name('get'); // Get specific category
    Route::post('/', [CategoryController::class, 'store'])->name('create'); // Create category (store method)
    Route::put('/{id}', [CategoryController::class, 'updateCategory'])->name('update'); // Update category (updateCategory method)
    Route::delete('/{id}', [CategoryController::class, 'deleteCategory'])->name('delete'); // Delete category (deleteCategory method)
    Route::get('/{id}/search', [CategoryController::class, 'searchSubCategories'])->name('searchSub'); // Search subcategories
    Route::get('/tree', [CategoryController::class, 'getCategoryTree'])->name('tree'); // Get category tree
});


use App\Http\Controllers\ProductsController;

// WEB
Route::resource('products', ProductsController::class)->names('products');

// API
Route::prefix('api/product')->name('api.product.')->group(function () {
    Route::get('/products', [ProductsController::class, 'index'])->name('list');
    Route::post('/products/{product}/upload-image', [ProductsController::class, 'uploadImage'])->name('uploadImage');
    Route::get('/night-combos', [ProductsController::class, 'listNightCombos'])->name('nightCombos.list');
    Route::post('/night-combos', [ProductsController::class, 'storeNightCombo'])->name('nightCombos.create');
    Route::get('/night-combos/{combo}', [ProductsController::class, 'getCombo'])->name('nightCombos.get');
    Route::put('/night-combos/{combo}', [ProductsController::class, 'updateCombo'])->name('nightCombos.update');
    Route::delete('/night-combos/{combo}', [ProductsController::class, 'destroyCombo'])->name('nightCombos.delete');
    Route::get('/night-combos/active', [ProductsController::class, 'getActiveNightCombos'])->name('nightCombos.active');
});
