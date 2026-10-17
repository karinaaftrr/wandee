<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ManageController as AdminManageController;
use App\Http\Controllers\Admin\PaymentController as AdminPaymentController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');

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
});

// Admin routes
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Manage destinations
        Route::get('/manage', [AdminManageController::class, 'index'])->name('manage');
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
