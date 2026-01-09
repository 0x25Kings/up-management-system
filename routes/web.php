<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\InternController;
use App\Http\Controllers\TaskController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

// Intern Portal Routes (No Login Required)
Route::get('/intern', [InternController::class, 'index'])->name('intern.portal');
Route::post('/intern/register', [InternController::class, 'register'])->name('intern.register');
Route::post('/intern/access', [InternController::class, 'accessWithCode'])->name('intern.access');
Route::post('/intern/clear-session', [InternController::class, 'clearSession'])->name('intern.clear');
Route::post('/intern/update-profile', [InternController::class, 'updateProfile'])->name('intern.update');
Route::post('/intern/time-in', [InternController::class, 'timeIn'])->name('intern.timein');
Route::post('/intern/time-out', [InternController::class, 'timeOut'])->name('intern.timeout');

Route::get('/startup', function () {
    return view('portals.startup');
})->name('startup.portal');

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
});
