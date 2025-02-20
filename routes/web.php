<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Auth;

Route::middleware('guest')->group(function () {
    Route::get('/', function () {return redirect()->route('dashboard');});

    Route::get('/dashboard', function () {return inertia('Bookstore/Dashboard');})->name('dashboard');

    Route::get('/login', function () {return inertia('Bookstore/Login');})->name('login');

    Route::get('/register', function () {return inertia('Bookstore/Register');})->name('register');

    Route::post('/login', [AuthController::class, 'login']);

    Route::post('/register', [AuthController::class, 'register']);


});

// Route::middleware('auth')->group(function () {

//     if (Auth::guard('admin')->check()) {
//             return redirect('/admin/dashboard');



//     }});

Route::middleware(['auth:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/dashboard/{table}/{id}/edit', [AdminController::class, 'edit'])->name('admin.edit');
    Route::put('/admin/dashboard/{table}/{id}', [AdminController::class, 'update'])->name('admin.update');
    Route::delete('/admin/dashboard/{table}/{id}', [AdminController::class, 'destroy'])->name('admin.destroy');

});

Route::post('/admin/register', [AuthController::class, 'AdminRegister'])->name('admin.register');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

        Route::get('/admin/register', function () {
        return inertia('Bookstore/AdminRegister');
        })->name('admin.register.form');
