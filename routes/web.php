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
use App\Http\Controllers\CMSController;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\CustomMenuController;
use App\Http\Controllers\QuotationController;
use App\Http\Controllers\FoodTasteController;

use App\Http\Controllers\BotmanController;

use App\Http\Middleware\AuthMiddleware;
use App\Http\Middleware\RoleMiddleware;


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
Route::get('/menu/customize/{packageId}/{menuName}', [CustomMenuController::class, 'edit'])->name('menu.customize');
Route::post('/menu/update/{packageId}', [CustomMenuController::class, 'update'])->name('menu.update');

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
Route::post('/payment/{reservation_id}/pencil', [GuestReservationController::class, 'processPencilBooking'])->name('guest.pencil.booking');
Route::get('/check-status', [GuestReservationController::class, 'showCheckStatus'])->name('guest.check');
Route::post('/check-status', [GuestReservationController::class, 'checkStatus'])->name('guest.check.submit');
Route::delete('/reservation/{reservation_id}/cancel', [GuestReservationController::class, 'cancelReservation'])->name('reservation.cancel');
Route::get('/edit-reserve/{reservation_id}', [GuestReservationController::class, 'edit'])->name('guest.reserve.edit');
Route::put('/edit-reserve/{reservation_id}', [GuestReservationController::class, 'update'])->name('guest.reservation.update');

//Quotation
Route::get('/quotation', [QuotationController::class, 'index'])->name('guest.quote');

//Food Taste
Route::get('/food-taste', [FoodTasteController::class, 'index'])->name('guest.foodtaste');

Route::view('/unauthorized', 'unauthorized')->name('unauthorized');

// Admin Route

// Login Route
Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login'])->name('firebase.login');
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

// Password reset form
Route::get('/reset/password', [PasswordResetController::class, 'showResetForm'])->name('password.reset.form');
// Send reset password link
Route::post('/reset/password', [PasswordResetController::class, 'sendResetLink'])->name('password.reset.send');
// Show the new password form (after clicking the link)
Route::get('/reset-password/{oobCode}', [PasswordResetController::class, 'showNewPasswordForm'])->name('password.new');
// Confirm the password reset and update the password in Firebase
Route::post('/reset-password/{oobCode}', [PasswordResetController::class, 'confirmReset'])->name('password.reset.confirm');

// Email Verification Routes
Route::get('/email/verify', [EmailVerificationController::class, 'notice'])
    ->name('verification.notice');
Route::post('/email/verification-notification', [EmailVerificationController::class, 'send'])
    ->name('verification.send');
Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])
    ->name('verification.verify');

// Auth Middleware
Route::middleware([AuthMiddleware::class])->group(function () {

    // Dashboard Route
    Route::group(['middleware' => RoleMiddleware::class . ':Super Admin,Admin,Manager,Staff'], function () {
        Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
        Route::patch('/admin/reservations/mark-as-read/{reservationId}', [DashboardController::class, 'markAsRead'])->name('admin.notification.markAsRead');
    });

    Route::group(['middleware' => RoleMiddleware::class . ':Super Admin,Admin,Manager,Staff'], function () {
    // Calendar Route
        Route::get('/admin/calendar', [CalendarController::class, 'index'])->name('admin.calendar');
        Route::post('/admin/calendar/reserve', [CalendarController::class, 'store'])->name('admin.calendar.reservation');
    });

    Route::group(['middleware' => RoleMiddleware::class . ':Super Admin,Admin,Manager'], function () {
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

        // Content Route
        Route::prefix('/admin/content')->group(function () {
            Route::get('/home', [CMSController::class, 'editHome'])->name('admin.home.edit');
            Route::post('/edit-home', [CMSController::class, 'updateHome'])->name('admin.home.update');
            Route::get('/carousel', [CMSController::class, 'editCarousel'])->name('admin.carousel.edit');
            Route::post('/edit-carousel', [CMSController::class, 'updateCarousel'])->name('admin.carousel.update');
            Route::get('/terms', [CMSController::class, 'editTerms'])->name('admin.terms.edit');
            Route::post('/edit-terms', [CMSController::class, 'updateTerms'])->name('admin.terms.update');
            Route::get('/gallery', [CMSController::class, 'editGallery'])->name('admin.gallery.edit');
            Route::post('/edit-gallery', [CMSController::class, 'updateGallery'])->name('admin.gallery.update');
            Route::get('/about', [CMSController::class, 'editAbout'])->name('admin.about.edit');
            Route::post('/edit-about', [CMSController::class, 'updateAbout'])->name('admin.about.update');
        });
    });

    Route::group(['middleware' => RoleMiddleware::class . ':Super Admin,Admin,Manager,Staff'], function () {
        // Reservation Route 
        Route::prefix('admin/reservations')->group(function () {
            Route::get('/', [ReservationController::class, 'index'])->name('admin.reservation');
            Route::patch('/mark-as-read/{reservationId}', [DashboardController::class, 'markAsRead'])->name('admin.notification.markAsRead');
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
    });

    Route::group(['middleware' => RoleMiddleware::class . ':Super Admin'], function () {
        // Reports Route
        Route::prefix('admin/reports')->group(function () {
            Route::get('/logs', [ReportsController::class, 'showLogs'])->name('admin.reports.logs');
            Route::get('/admin/logs/download', [ReportsController::class, 'download'])->name('admin.logs.download');
            Route::post('/logs/remove-all', [ReportsController::class, 'removeAll'])->name('admin.logs.removeAll');
        });
    });

    Route::group(['middleware' => RoleMiddleware::class . ':Super Admin,Admin'], function () {
        // Reports Route
        Route::prefix('admin/reports')->group(function () {
            Route::get('/reservation', [ReportsController::class, 'reservation'])->name('admin.reports.reservation');
            Route::get('/reservation/print', [ReportsController::class, 'printReservations'])->name('reservation.print');
            Route::get('/sales', [ReportsController::class, 'sales'])->name('admin.reports.sales');
            Route::get('/sales/print', [ReportsController::class, 'printSales'])->name('admin.reports.sales.print');
            Route::get('/packages', [ReportsController::class, 'packages'])->name('admin.reports.packages');
            Route::get('/packages/yearly/{year}', [ReportsController::class, 'getYearlyData']);
            Route::get('/packages/monthly', [ReportsController::class, 'getMonthlyData']);
            Route::get('/packages/weekly', [ReportsController::class, 'getWeeklyData']);
            Route::get('/locations', [ReportsController::class, 'locations'])->name('admin.reports.locations');
        });
    });

    Route::group(['middleware' => RoleMiddleware::class . ':Super Admin,Admin'], function () {
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
});

//Botman Route
Route::match(['get', 'post'], '/botman', [BotmanController::class, 'handle']);
Route::get('/botman/widget', function () {
    return view('botman.botmanwidget');
}); 
