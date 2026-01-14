<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminStartupController;
use App\Http\Controllers\InternController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\BlockedDateController;
use App\Http\Controllers\StartupController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

// Booking Routes (Public)
Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
Route::get('/bookings/date/{date}', [BookingController::class, 'getByDate'])->name('bookings.byDate');
Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
Route::get('/bookings/check-availability', [BookingController::class, 'checkAvailability'])->name('bookings.checkAvailability');
Route::get('/blocked-dates/check/{date}', [BlockedDateController::class, 'check'])->name('blocked.check');
Route::get('/blocked-dates', [BlockedDateController::class, 'index'])->name('blocked.index');

// Intern Portal Routes (No Login Required)
Route::get('/intern', [InternController::class, 'index'])->name('intern.portal');
Route::post('/intern/register', [InternController::class, 'register'])->name('intern.register');
Route::post('/intern/access', [InternController::class, 'accessWithCode'])->name('intern.access');
Route::post('/intern/clear-session', [InternController::class, 'clearSession'])->name('intern.clear');
Route::post('/intern/update-profile', [InternController::class, 'updateProfile'])->name('intern.update');
Route::post('/intern/time-in', [InternController::class, 'timeIn'])->name('intern.timein');
Route::post('/intern/time-out', [InternController::class, 'timeOut'])->name('intern.timeout');

// Startup Portal Routes (No Login Required)
Route::get('/startup', [StartupController::class, 'index'])->name('startup.portal');
Route::post('/startup/document', [StartupController::class, 'submitDocument'])->name('startup.document');
Route::post('/startup/room-issue', [StartupController::class, 'submitRoomIssue'])->name('startup.room-issue');
Route::post('/startup/moa', [StartupController::class, 'submitMoa'])->name('startup.moa');
Route::post('/startup/payment', [StartupController::class, 'submitPayment'])->name('startup.payment');
Route::post('/startup/track', [StartupController::class, 'track'])->name('startup.track');
Route::get('/startup/moa-template', [StartupController::class, 'downloadMoaTemplate'])->name('startup.moa-template');

Route::get('/agency', function () {
    return view('portals.agency');
})->name('agency.portal');

// Admin Authentication Routes
Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

// Protected Admin Routes
Route::middleware(['admin'])->group(function () {
    Route::get('/admin', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard.home');
    Route::post('/admin/attendance/{attendance}/approve-overtime', [AdminDashboardController::class, 'approveOvertime'])->name('admin.attendance.approve-overtime');
    
    // Task routes
    Route::post('/admin/tasks', [TaskController::class, 'store'])->name('task.store');
    Route::put('/admin/tasks/{task}', [TaskController::class, 'update'])->name('task.update');
    Route::post('/admin/tasks/{task}/complete', [TaskController::class, 'complete'])->name('task.complete');
    Route::delete('/admin/tasks/{task}', [TaskController::class, 'destroy'])->name('task.destroy');
    Route::get('/admin/intern/{intern}/tasks', [TaskController::class, 'getInternTasks'])->name('intern.tasks');

    // Booking management routes (Admin)
    Route::get('/admin/bookings/pending', [BookingController::class, 'pending'])->name('admin.bookings.pending');
    Route::post('/admin/bookings/{booking}/approve', [BookingController::class, 'approve'])->name('admin.bookings.approve');

    // Blocked dates management (Admin)
    Route::get('/admin/blocked-dates', [BlockedDateController::class, 'index'])->name('admin.blocked.index');
    Route::post('/admin/blocked-dates', [BlockedDateController::class, 'store'])->name('admin.blocked.store');
    Route::delete('/admin/blocked-dates/{blockedDate}', [BlockedDateController::class, 'destroy'])->name('admin.blocked.destroy');
    Route::post('/admin/bookings/{booking}/reject', [BookingController::class, 'reject'])->name('admin.bookings.reject');
    Route::delete('/admin/bookings/{booking}', [BookingController::class, 'destroy'])->name('admin.bookings.destroy');

    // Startup Submissions Management (Admin)
    Route::get('/admin/submissions/{submission}', [AdminStartupController::class, 'getSubmission'])->name('admin.submissions.show');
    Route::put('/admin/submissions/{submission}', [AdminStartupController::class, 'updateSubmission'])->name('admin.submissions.update');
    Route::delete('/admin/submissions/{submission}', [AdminStartupController::class, 'deleteSubmission'])->name('admin.submissions.destroy');

    // Room Issues Management (Admin)
    Route::get('/admin/room-issues/{roomIssue}', [AdminStartupController::class, 'getRoomIssue'])->name('admin.room-issues.show');
    Route::put('/admin/room-issues/{roomIssue}', [AdminStartupController::class, 'updateRoomIssue'])->name('admin.room-issues.update');
    Route::delete('/admin/room-issues/{roomIssue}', [AdminStartupController::class, 'deleteRoomIssue'])->name('admin.room-issues.destroy');
});
