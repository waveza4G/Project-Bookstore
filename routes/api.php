<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use Inertia\Inertia;
// เส้นทางสำหรับการเข้าสู่ระบบ
Route::post('/login', [AuthController::class, 'login'])->name('login');

// เส้นทางสำหรับการล็อกเอาต์
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum')->name('logout');


Route::post('/admin/register', [AdminController::class, 'register']);

// เส้นทางที่ต้องการการยืนยันตัวตน
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// เส้นทางสำหรับ Admin Dashboard
Route::middleware('auth:admin')->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
});

// เส้นทางสำหรับ Customer Dashboard
Route::middleware('auth:web')->group(function () {
    Route::get('/customer/dashboard', [CustomerController::class, 'dashboard'])->name('customer.dashboard');
});
