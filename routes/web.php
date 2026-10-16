
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/login', [AuthController::class, 'showLogin'])
    ->name('login');

Route::post('/login', [AuthController::class, 'login'])
    ->name('login.post');

Route::post('/register', [AuthController::class, 'register'])
    ->name('register.post');

Route::redirect('/user', '/user/dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/user/dashboard', [UserDashboardController::class, 'index'])
        ->name('user.dashboard');

    Route::post('/user/favorites/toggle', [
        UserDashboardController::class,
        'toggleFavorite'
    ])->name('user.favorites.toggle');

    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');
});

Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])
            ->name('dashboard');
    });
