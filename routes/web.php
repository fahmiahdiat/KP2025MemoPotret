<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/paket', [HomeController::class, 'packages'])->name('packages');
Route::get('/paket/{package}', [HomeController::class, 'showPackage'])->name('package.show');

// Auth Routes (from Breeze)
require __DIR__ . '/auth.php';

// =================== CLIENT ROUTES ===================
Route::middleware(['auth', 'role:client'])->prefix('client')->name('client.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Client\DashboardController::class, 'index'])->name('dashboard');

    // Bookings Routes
    Route::get('/bookings', [\App\Http\Controllers\Client\BookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/create-step2', [\App\Http\Controllers\Client\BookingController::class, 'createStep2'])->name('bookings.create-step2');
    Route::post('/bookings/store-step2', [\App\Http\Controllers\Client\BookingController::class, 'storeStep2'])->name('bookings.store-step2');
    Route::get('/bookings/{booking}', [\App\Http\Controllers\Client\BookingController::class, 'show'])->name('bookings.show');
    Route::post('/bookings/{booking}/upload-payment', [\App\Http\Controllers\Client\BookingController::class, 'uploadPayment'])->name('bookings.upload-payment');
    // TAMBAHKAN RUTE INI:
    Route::post('/bookings/{booking}/upload-remaining-payment', [\App\Http\Controllers\Client\BookingController::class, 'uploadRemainingPayment'])->name('bookings.upload-remaining-payment');
    Route::delete('/bookings/{booking}/cancel', [\App\Http\Controllers\Client\BookingController::class, 'cancel'])
        ->name('bookings.cancel');


});

// =================== ADMIN ROUTES ===================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    // Bookings Routes
    Route::get('/bookings', [\App\Http\Controllers\Admin\BookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/create', [\App\Http\Controllers\Admin\BookingController::class, 'create'])->name('bookings.create');
    Route::post('/bookings', [\App\Http\Controllers\Admin\BookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings/{booking}', [\App\Http\Controllers\Admin\BookingController::class, 'show'])->name('bookings.show');
    Route::put('/bookings/{booking}', [\App\Http\Controllers\Admin\BookingController::class, 'update'])->name('bookings.update');
    Route::put('/bookings/{booking}/status', [\App\Http\Controllers\Admin\BookingController::class, 'updateStatus'])->name('bookings.status');
    Route::post('/bookings/{booking}/verify-payment', [\App\Http\Controllers\Admin\BookingController::class, 'verifyPayment'])->name('bookings.verify-payment');
    Route::post('/bookings/{booking}/verify-full-payment', [\App\Http\Controllers\Admin\BookingController::class, 'verifyFullPayment'])->name('bookings.verify-full-payment');
    Route::post('/bookings/{booking}/cancel', [\App\Http\Controllers\Admin\BookingController::class, 'cancel'])->name('bookings.cancel');
    Route::post('/bookings/{booking}/upload-results', [\App\Http\Controllers\Admin\BookingController::class, 'uploadResults'])->name('bookings.upload-results');

    // Packages Routes
    Route::get('/packages', [\App\Http\Controllers\Admin\PackageController::class, 'index'])->name('packages.index');
    Route::get('/packages/create', [\App\Http\Controllers\Admin\PackageController::class, 'create'])->name('packages.create');
    Route::post('/packages', [\App\Http\Controllers\Admin\PackageController::class, 'store'])->name('packages.store');
    Route::get('/packages/{package}/edit', [\App\Http\Controllers\Admin\PackageController::class, 'edit'])->name('packages.edit');
    Route::put('/packages/{package}', [\App\Http\Controllers\Admin\PackageController::class, 'update'])->name('packages.update');
    Route::delete('/packages/{package}', [\App\Http\Controllers\Admin\PackageController::class, 'destroy'])->name('packages.destroy');

    // Calendar
    Route::get('/calendar', [\App\Http\Controllers\Admin\ScheduleController::class, 'index'])->name('calendar');
    Route::get('/calendar/events', [\App\Http\Controllers\Admin\ScheduleController::class, 'getEvents'])->name('calendar.events');
});

// =================== OWNER ROUTES ===================
Route::middleware(['auth', 'role:owner'])->prefix('owner')->name('owner.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [\App\Http\Controllers\Owner\DashboardController::class, 'index'])->name('dashboard');

    // Reports
    Route::get('/reports', [\App\Http\Controllers\Owner\ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export', [\App\Http\Controllers\Owner\ReportController::class, 'export'])->name('reports.export');
    Route::get('/reports/financial', [\App\Http\Controllers\Owner\ReportController::class, 'financial'])->name('reports.financial');
    Route::get('/reports/bookings', [\App\Http\Controllers\Owner\ReportController::class, 'bookings'])->name('reports.bookings');
    Route::get('/reports/packages', [\App\Http\Controllers\Owner\ReportController::class, 'packages'])->name('reports.packages');
    Route::get('/reports/clients', [\App\Http\Controllers\Owner\ReportController::class, 'clients'])->name('reports.clients');

    // Users Management
    Route::get('/users', [\App\Http\Controllers\Owner\UserController::class, 'index'])->name('users.index');
    Route::get('/users/create-admin', [\App\Http\Controllers\Owner\UserController::class, 'createAdmin'])->name('users.create-admin');
    Route::post('/users/create-admin', [\App\Http\Controllers\Owner\UserController::class, 'storeAdmin'])->name('users.store-admin');
    Route::post('/users/{user}/toggle-status', [\App\Http\Controllers\Owner\UserController::class, 'toggleStatus'])->name('users.toggle-status');
    Route::get('/users/{user}/client', [\App\Http\Controllers\Owner\UserController::class, 'showClient'])->name('users.show-client');

});

