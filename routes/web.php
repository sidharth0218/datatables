<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\OrderController;

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
Route::get('/table',function() {
 return view('table');
});
Route::prefix('admin')->group(function () {

    // ✅ Admin Login Page (IMPORTANT)
    Route::get('login', [AdminController::class, 'showLogin'])
        ->name('admin.login');

    Route::post('login', [AdminController::class, 'login']);

    // ✅ Protected Admin Routes
    Route::middleware('auth:admin')->group(function () {
        Route::get('dashboard', [AdminController::class, 'dashboard'])
            ->name('admin.dashboard');
            Route::get('razorpay/test', [AdminController::class, 'test'])->name('admin.test');
    
    // Add these here!
    Route::post('orders', [OrderController::class, 'store'])->name('orders.store');
    Route::post('orders/verify', [OrderController::class, 'verifyPayment'])->name('orders.verify');

        Route::post('logout', [AdminController::class, 'logout'])
            ->name('admin.logout');
    });
});

