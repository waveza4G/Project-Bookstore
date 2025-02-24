<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\RentalController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Auth;

Route::middleware('guest')->group(function () {
    Route::get('/', function () {return redirect()->route('dashboard');});

    Route::get('/dashboard', function () {return inertia('Bookstore/Dashboard');})->name('dashboard');

    Route::get('/login', function () {return inertia('Store/Login');})->name('login');

    Route::get('/register', function () {return inertia('Store/Register');})->name('register');

    Route::post('/login', [AuthController::class, 'login']);

    Route::post('/register', [AuthController::class, 'register']);

    Route::post('/admin/register', [AuthController::class, 'AdminRegister'])->name('admin.register');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/book/{book}', [BookController::class, 'show'])->name('books.show');

    Route::get('/Showcategory', [BookController::class, 'Showcategory'])->name('Showcategory.index');

});
// เส้นทางสำหรับการเช่าหนังสือ
// เส้นทางสำหรับหน้าชำระเงิน

// Route::get('/qrcode/{customerId}', [RentalController::class, 'show'])->name('qrcode.show');
Route::post('/rental', [RentalController::class, 'rental'])->name('rental.wait');
Route::post('/rental/complete', [AdminController::class, 'complete'])->name('rental.complete');

Route::get('/rental/{customerId}/book/{bookId}/days/{rentalDays}', [RentalController::class, 'create'])->name('rental.create');
Route::post('/payment/confirm', [RentalController::class, 'confirmPayment'])->name('payment.confirm');


// Route::get('/admin/upload-image/{id}', [AdminController::class, 'showUploadQRImage'])
//     ->name('admin.upload-image'); // แสดงหน้าอัปโหลดรูปภาพ

// Route::post('/admin/upload-image/{id}', [AdminController::class, 'uploadQRImage'])
//     ->name('admin.upload-image.store'); // อัปโหลดรูปภาพ

// Route::get('/payments/create/{rental_id}/{rental_amount}', [PaymentController::class, 'create'])->name('payments.create');

// Route::get('/rental/{customerId}/book/{bookId}/days/{rentalDays}', [RentalController::class, 'create'])->name('rental.create');

// Route::get('/qrcode/{customerId}/book/{bookId}', [RentalController::class, 'show'])->name('qrcode.show');
// Route::post('/payment/complete', [PaymentController::class, 'complete'])->name('payment.complete');


// Route::get('/highlight/{category}', [BookController::class, 'highlight'])->name('highlight');

// Route::get('/highlight', function () {return inertia('Bookstore/Highlight');})->name('highlight.index');

Route::middleware(['auth:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/edit/{table}/{id}', [AdminController::class, 'edit'])->name('admin.edit');
    Route::put('/admin/update/{table}/{id}', [AdminController::class, 'update'])->name('admin.update');
    Route::delete('/admin/dashboard/{table}/{id}', [AdminController::class, 'destroy'])->name('admin.destroy');


    Route::get('/admin/upload-image/{id}', [AdminController::class, 'showUploadImage'])->name('admin.upload-image');
    Route::post('/admin/upload-image/{id}', [AdminController::class, 'uploadImage'])->name('admin.upload-image.post');

    Route::get('/books/create', [BookController::class, 'create'])->name('books.create');
    Route::post('/books', [BookController::class, 'store'])->name('books.store');

    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    Route::get('/groups', [GroupController::class, 'index'])->name('groups.index');
    Route::post('/groups', [GroupController::class, 'store'])->name('groups.store');
    Route::delete('/groups/{id}', [GroupController::class, 'destroy'])->name('groups.destroy');
});

        Route::get('/admin/register', function () {
        return inertia('Store/AdminRegister');
        })->name('admin.register.form');

// Route::middleware('auth')->group(function () {

//     if (Auth::guard('admin')->check()) {
//             return redirect('/admin/dashboard');



//     }});

Route::middleware(['auth:customer'])->group(function () {


});
Route::get('/rental', function () {
    return inertia('Bookstore/Rental');
})->name('rental');
