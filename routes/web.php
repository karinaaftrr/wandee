<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ManageController as AdminManageController;
use App\Http\Controllers\Admin\PaymentController as AdminPaymentController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');

// Halaman informasi (bisa dibuka tanpa login)
Route::view('/tentang-kami', 'pages.about')->name('about');
Route::view('/kebijakan-privasi', 'pages.privacy')->name('privacy');
Route::view('/syarat-ketentuan', 'pages.terms')->name('terms');

// Auth routes (guest only for login/register pages)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
});

// Auth POST routes (accessible by all)
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.reset');

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // User routes
    Route::prefix('user')->name('user.')->group(function () {
        Route::get('/dashboard', [UserController::class, 'index'])->name('dashboard');
        Route::get('/detail/{id?}', [UserController::class, 'detail'])->name('detail');
        Route::get('/favorite', [UserController::class, 'favorite'])->name('favorite');
        Route::post('/favorite_toggle', [UserController::class, 'toggleFavorite'])->name('favorite_toggle');

        // Booking & Payment
        Route::get('/payment/{id?}', [UserController::class, 'payment'])->name('payment');
        Route::post('/payment_init', [UserController::class, 'paymentInit'])->name('payment_init');
        Route::get('/payment_detail', [UserController::class, 'paymentDetail'])->name('payment_detail');
        Route::post('/payment_upload', [UserController::class, 'paymentUpload'])->name('payment_upload');
        Route::get('/payment_status', [UserController::class, 'paymentStatus'])->name('payment_status');
        Route::get('/trip_detail', [UserController::class, 'tripDetail'])->name('trip_detail');

        // Riwayat & Ulasan
        Route::get('/riwayat', [UserController::class, 'riwayat'])->name('riwayat');
        Route::get('/ulasan', [UserController::class, 'ulasan'])->name('ulasan');
        Route::post('/ulasan', [UserController::class, 'submitReview'])->name('ulasan.submit');

        // Profil
        Route::get('/profile', [UserController::class, 'profile'])->name('profile');
        Route::post('/profile', [UserController::class, 'updateProfile'])->name('profile.update');
    });
});

// Admin routes
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Manage destinations
        Route::get('/manage', [AdminManageController::class, 'index'])->name('manage');
        Route::post('/store-destination', [AdminManageController::class, 'store'])->name('store_destination');
        Route::get('/edit-destination/{id}', [AdminManageController::class, 'edit'])->name('edit_destination');
        Route::put('/update-destination/{id}', [AdminManageController::class, 'update'])->name('update_destination');
        Route::delete('/delete-destination/{id}', [AdminManageController::class, 'destroy'])->name('delete_destination');

        // Manage payments
        Route::get('/manage-payments', [AdminPaymentController::class, 'index'])->name('manage_payments');
        Route::post('/payment-update', [AdminPaymentController::class, 'updateStatus'])->name('payment_update');
        Route::post('/trip-complete', [AdminPaymentController::class, 'tripComplete'])->name('trip_complete');

        // Manage reviews
        Route::get('/manage-reviews', [AdminReviewController::class, 'index'])->name('manage_reviews');
        Route::delete('/review-delete/{id}', [AdminReviewController::class, 'destroy'])->name('review_delete');

        // Profile
        Route::get('/profile', [AdminProfileController::class, 'index'])->name('profile');
    });