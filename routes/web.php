<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\AuthController;

use App\Http\Middleware\AuthMiddleware;

Route::get('/', function () {
    return view('welcome');
});

// Login Route
Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login'])->name('firebase.login');
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

// Auth Middleware
Route::middleware([AuthMiddleware::class])->group(function () {

    // Dashboard Route
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    // Calendar Route
    Route::get('/admin/calendar', [CalendarController::class, 'index'])->name('admin.calendar');

    // Packages Route
    Route::get('/admin/packages', [PackageController::class, 'index'])->name('admin.packages');

    // Reservation Route
    Route::prefix('admin/reservations')->group(function () {
        Route::get('/', [ReservationController::class, 'index'])->name('admin.reservation');
        Route::get('/add-reservation', [ReservationController::class, 'createReservation'])->name('admin.reserve.addRes');
        Route::post('/add-reservation', [ReservationController::class, 'reservation'])->name('admin.reserve.reserve');
        Route::put('/confirm-reservation/{id}', [ReservationController::class, 'confirmReservation'])->name('admin.reserve.confirm');
    });

    // Reports Route
    Route::prefix('admin/reports')->group(function () {
        Route::get('/reservation', [ReportsController::class, 'reservation'])->name('admin.reports.reservation');
        Route::get('/sales', [ReportsController::class, 'sales'])->name('admin.reports.sales');
    });

    // Users Route
    Route::prefix('admin/users')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('admin.users');
        Route::get('add-user', [UserController::class, 'create'])->name('admin.users.add');
        Route::post('add-user', [UserController::class, 'store'])->name('admin.users.store');
        Route::get('edit-user/{id}', [UserController::class, 'edit'])->name('admin.users.edit');
        Route::put('update-user/{id}', [UserController::class, 'update'])->name('admin.users.update');
        Route::get('delete-user/{id}', [UserController::class, 'archive'])->name('admin.users.delete');
    });
});
