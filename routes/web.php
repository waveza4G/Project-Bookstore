<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use Inertia\Inertia;
use App\Http\Controllers\CustomerController;

// หน้า Login

Route::post('/login', [AuthController::class, 'login']);

Route::get('/login', function () {
    return Inertia::render('Bookstore/Login');  // ใช้ Inertia แทน render
})->name('login');



Route::post('/register', [AuthController::class, 'register']);  // แก้ไขให้ใช้ store แทน register

Route::get('/register', function () {
    return Inertia::render('Bookstore/Register');  // ใช้ Inertia แทน render
})->name('register');



// ฟอร์ม Login
Route::middleware('auth:admin')->get('/admin/dashboard', function () {
    return Inertia::render('admin/dashboard');  // ใช้ Inertia แทน render
})->name('admin.dashboard');

// สำหรับการลงทะเบียน Admin
Route::post('/admin/register', [AdminController::class, 'addadmin']);  // แก้ไขให้ใช้ store แทน register

// ฟังก์ชัน logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// สำหรับ Customer Dashboard
Route::middleware('auth:customer')->get('/customer/dashboard', function () {
    return Inertia::render('customer/dashboard');  // ใช้ Inertia แทน render
})->name('customer.dashboard');

// หน้า Home หรือ Bookstore
Route::get('/', function () {
    return Inertia::render('Bookstore/Dashboard');});

