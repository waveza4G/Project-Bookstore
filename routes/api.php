<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use Inertia\Inertia;
// เส้นทางสำหรับการเข้าสู่ระบบ
Route::post('/login', [ApiController::class, 'login'])->name('login');
Route::post('/admin/login', [ApiController::class, 'login'])->name('admin.login');

// เส้นทางสำหรับการล็อกเอาต์
Route::post('/logout', [ApiController::class, 'logout'])->name('logout');
Route::post('/admin/logout', [ApiController::class, 'logout'])->name('admin.logout');

Route::post('/register', [AuthController::class, 'addadmin']);  // แก้ไขให้ใช้ store แทน register

Route::post('/admin/register', [ApiController::class, 'addadmin']);

// เส้นทางที่ต้องการการยืนยันตัวตน
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// เส้นทางสำหรับ Admin Dashboard
Route::middleware('auth:admin')->group(function () {
    Route::get('/admin/dashboard', [Controller::class, 'dashboard'])->name('admin.dashboard');
});

// เส้นทางสำหรับ Customer Dashboard
Route::middleware('auth:web')->group(function () {
    Route::get('/customer/dashboard', [Controller::class, 'dashboard'])->name('customer.dashboard');
});
