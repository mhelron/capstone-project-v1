<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\ListPackageController;
use App\Http\Controllers\GuestReservationController;

use App\Http\Middleware\AuthMiddleware;

// Guest Route
//Home
Route::get('/', [GuestController::class, 'indexHome'])->name('guest.home');

//Packages
Route::get('/packages', [GuestController::class, 'indexPackages'])->name('guest.packages');
Route::get('/packages/markina', [ListPackageController::class, 'marikina'])->name('guest.packages.marikina');
Route::get('/packages/san-mateo', [ListPackageController::class, 'sanmateo'])->name('guest.packages.sanmateo');
Route::get('/packages/motalban', [ListPackageController::class, 'montalban'])->name('guest.packages.montalban');
Route::get('/package/{id}', [ListPackageController::class, 'show'])->name('package.show');

//Gallery
Route::get('/gallery', [GuestController::class, 'indexGallery'])->name('guest.gallery');

//Calendar
Route::get('/calendar', [GuestController::class, 'indexCalendar'])->name('guest.calendar');

//Contact
Route::get('/contact', [GuestController::class, 'indexContact'])->name('guest.contact');

//About
Route::get('/about', [GuestController::class, 'indexAbout'])->name('guest.about');

//Reservation
Route::get('/reserve', [GuestReservationController::class, 'index'])->name('guest.reserve');
Route::post('/reserve', [GuestReservationController::class, 'store'])->name('guest.reserve.add');

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
    Route::post('/admin/calendar/reserve', [CalendarController::class, 'store'])->name('admin.calendar.reservation');

    // Packages Route
    Route::prefix('/admin/packages')->group(function () {
        Route::get('/', [PackageController::class, 'index'])->name('admin.packages');
        Route::get('/add-package', [PackageController::class, 'create'])->name('admin.packages.add');
        Route::post('/add-package', [PackageController::class, 'store'])->name('admin.packages.store');
        Route::get('/edit-package/{id}', [PackageController::class, 'edit'])->name('admin.packages.edit');
        Route::put('/update-package/{id}', [PackageController::class, 'update'])->name('admin.packages.update');
        Route::get('/archive-package/{id}', [PackageController::class, 'destroy'])->name('admin.packages.delete');
        Route::post('admin/packages/toggle-display/{packageId}', [PackageController::class, 'toggleDisplay'])->name('admin.packages.toggleDisplay');
    });

    // Reservation Route 
    Route::prefix('admin/reservations')->group(function () {
        Route::get('/', [ReservationController::class, 'index'])->name('admin.reservation');
        Route::get('/add-reservation', [ReservationController::class, 'createReservation'])->name('admin.reserve.addRes');
        Route::post('/add-reservation', [ReservationController::class, 'reservation'])->name('admin.reserve.reserve');
        Route::put('/confirm-reservation/{id}', [ReservationController::class, 'confirmReservation'])->name('admin.reserve.confirm');
        Route::put('/finish-reservation/{id}', [ReservationController::class, 'finishReservation'])->name('admin.reserve.finish');
        Route::put('/cancel-reservation/{id}', [ReservationController::class, 'cancelReservation'])->name('admin.reserve.cancel');
        
        Route::get('/add-pencil-reservation', [ReservationController::class, 'createPencil'])->name('admin.reserve.addPen');
        Route::post('/add-pencil-reservation', [ReservationController::class, 'pencilReservation'])->name('admin.reserve.pencil');
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
