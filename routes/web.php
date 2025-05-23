<?php

use Illuminate\Support\Facades\Route;

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

// Dashboard Routes
Route::get('/', function () {
    return view('dashboard.index');
})->name('dashboard');

// Authentication Routes
Route::prefix('auth')->group(function () {
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');

    Route::get('/register', function () {
        return view('auth.register');
    })->name('register');
});

// UI Components Routes
Route::prefix('ui')->group(function () {
    Route::get('/alerts', function () {
        return view('ui.alerts');
    })->name('ui.alerts');

    Route::get('/buttons', function () {
        return view('ui.buttons');
    })->name('ui.buttons');

    Route::get('/cards', function () {
        return view('ui.cards');
    })->name('ui.cards');

    Route::get('/forms', function () {
        return view('ui.forms');
    })->name('ui.forms');

    Route::get('/typography', function () {
        return view('ui.typography');
    })->name('ui.typography');
});

// Pages Routes
Route::prefix('pages')->group(function () {
    Route::get('/sample', function () {
        return view('pages.sample-page');
    })->name('sample-page');

    Route::get('/icon-tabler', function () {
        return view('pages.icon-tabler');
    })->name('icon-tabler');

    Route::get('/docs', function () {
        return view('pages.docs');
    })->name('docs');

    Route::get('/discount-code', function () {
        return view('pages.discount-code');
    })->name('discount-code');
});