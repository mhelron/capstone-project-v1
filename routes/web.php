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
use App\Http\Controllers\EmailController;
use App\Http\Controllers\PasswordResetController;

use App\Http\Controllers\BotmanController;

use App\Http\Middleware\AuthMiddleware;

// Sample
Route::get('/send-email', [EmailController::class, 'sendEmail']);

// Guest Route

//Home
Route::get('/', [GuestController::class, 'indexHome'])->name('guest.home');

//Packages
Route::get('/packages', [GuestController::class, 'indexPackages'])->name('guest.packages');
Route::get('/package/{id}', [ListPackageController::class, 'show'])->name('package.show');
Route::get('/packages/markina', [ListPackageController::class, 'marikina'])->name('guest.packages.marikina');
Route::get('/packages/san-mateo', [ListPackageController::class, 'sanmateo'])->name('guest.packages.sanmateo');
Route::get('/packages/motalban', [ListPackageController::class, 'montalban'])->name('guest.packages.montalban');

//Gallery
Route::get('/gallery', [GuestController::class, 'indexGallery'])->name('guest.gallery');

//Calendar
Route::get('/calendar', [GuestController::class, 'indexCalendar'])->name('guest.calendar');

//Contact
Route::get('/contact', [GuestController::class, 'indexContact'])->name('guest.contact');
Route::post('/contact-us', [EmailController::class, 'sendContactus'])->name('contactus.send');

//About
Route::get('/about', [GuestController::class, 'indexAbout'])->name('guest.about');

//Reservation
Route::get('/reserve', [GuestReservationController::class, 'index'])->name('guest.reserve');
Route::post('/reserve', [GuestReservationController::class, 'store'])->name('guest.reserve.add');
Route::get('/payment/{reservation_id}', [GuestReservationController::class, 'payment'])->name('guest.payment');
Route::post('/payment/{reservation_id}/proof', [GuestReservationController::class, 'storePaymentProof'])->name('guest.payment.proof');
Route::get('/check-status', [GuestReservationController::class, 'showCheckStatus'])->name('guest.check');
Route::post('/check-status', [GuestReservationController::class, 'checkStatus'])->name('guest.check.submit');
Route::delete('/reservation/{reservation_id}/cancel', [GuestReservationController::class, 'cancelReservation'])->name('reservation.cancel');
Route::get('/edit-reserve/{reservation_id}', [GuestReservationController::class, 'edit'])->name('guest.reserve.edit');

//Botman Route
Route::match(['get', 'post'], '/botman', [BotmanController::class, 'handle']);
Route::get('/botman/widget', function () {
    return view('botman.botmanwidget');
}); 

// Admin Route

// Login Route
Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login'])->name('firebase.login');
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

// Password reset form
Route::get('/password/reset', [PasswordResetController::class, 'showResetForm'])->name('password.reset.form');

// Send reset password link
Route::post('/password/reset', [PasswordResetController::class, 'sendResetLink'])->name('password.reset.send');

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
        Route::get('/add-pencil-reservation', [ReservationController::class, 'createPencil'])->name('admin.reserve.addPen');
        Route::post('/add-pencil-reservation', [ReservationController::class, 'pencilReservation'])->name('admin.reserve.pencil');
        Route::put('/confirm-pencil/{id}', [ReservationController::class, 'confirmPencil'])->name('admin.pencil.confirm');
        Route::put('/cancel-pencil/{id}', [ReservationController::class, 'cancelPencil'])->name('admin.pencil.cancel');
        Route::put('/confirm-reservation/{id}', [ReservationController::class, 'confirmReservation'])->name('admin.reserve.confirm');
        Route::put('/finish-reservation/{id}', [ReservationController::class, 'finishReservation'])->name('admin.reserve.finish');
        Route::put('/admin/reservations/{id}/finish', [ReservationController::class, 'finishReservationAuto']);
        Route::put('/cancel-reservation/{id}', [ReservationController::class, 'cancelReservation'])->name('admin.reserve.cancel');
        Route::put('/archive-reservation/{id}', [ReservationController::class, 'destroy'])->name('admin.reserve.archive');
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
