<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use Inertia\Inertia;
// หน้า Login


Route::get('/login', function () {
    return view('auth.login');  // หน้าฟอร์ม login ที่คุณสร้างไว้
})->name('login');

// ฟอร์ม Login
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

// ฟังก์ชัน logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
Route::post('/admin/register', [AdminController::class, 'register']);

// สำหรับ Admin Dashboard
Route::middleware('auth:admin')->get('/admin/dashboard', function () {
    return view('admin.dashboard');
})->name('admin.dashboard');

// สำหรับ Customer Dashboard
Route::middleware('auth:web')->get('/customer/dashboard', function () {
    return view('customer.dashboard');
})->name('customer.dashboard');

Route::get('/', function () {return Inertia::render('Bookstore/Index');});