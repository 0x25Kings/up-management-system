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
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\TeamLeaderController;

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

// Public route to get active schools for intern registration dropdown
Route::get('/schools/active', [SchoolController::class, 'getActiveSchools'])->name('schools.active');

// Intern Portal Routes (No Login Required)
Route::get('/intern', [InternController::class, 'index'])->name('intern.portal');
Route::post('/intern/register', [InternController::class, 'register'])->name('intern.register');
Route::post('/intern/access', [InternController::class, 'accessWithCode'])->name('intern.access');
Route::post('/intern/clear-session', [InternController::class, 'clearSession'])->name('intern.clear');
Route::post('/intern/update-profile', [InternController::class, 'updateProfile'])->name('intern.update');
Route::post('/intern/update-profile-picture', [InternController::class, 'updateProfilePicture'])->name('intern.update.picture');
Route::post('/intern/time-in', [InternController::class, 'timeIn'])->name('intern.timein');
Route::post('/intern/time-out', [InternController::class, 'timeOut'])->name('intern.timeout');

// Intern Task Management Routes
Route::get('/intern/tasks/{task}', [InternController::class, 'getTask'])->name('intern.task.show');
Route::put('/intern/tasks/{task}', [InternController::class, 'updateTask'])->name('intern.task.update');
Route::post('/intern/tasks/{task}/complete', [InternController::class, 'completeTask'])->name('intern.task.complete');

// Document Management Routes (Intern)
Route::post('/intern/documents/folder', [DocumentController::class, 'createFolder'])->name('documents.folder.create');
Route::post('/intern/documents/upload', [DocumentController::class, 'uploadDocument'])->name('documents.upload');
Route::get('/intern/documents', [DocumentController::class, 'getFolders'])->name('documents.list');
Route::get('/intern/documents/folder/{folderId}', [DocumentController::class, 'getFolderContents'])->name('documents.folder');
Route::put('/intern/documents/folder/{folderId}', [DocumentController::class, 'updateFolder'])->name('documents.folder.update');
Route::delete('/intern/documents/folder/{folderId}', [DocumentController::class, 'deleteFolder'])->name('documents.folder.delete');
Route::delete('/intern/documents/{documentId}', [DocumentController::class, 'deleteDocument'])->name('documents.delete');
Route::get('/intern/documents/{documentId}/download', [DocumentController::class, 'downloadDocument'])->name('documents.download');

// Event Routes (Intern - Read Only)
Route::get('/intern/events', [EventController::class, 'index'])->name('events.index');

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

// Public route to download task documents
Route::get('/documents/download/{filename}', function ($filename) {
    $path = storage_path('app/public/tasks/documents/' . basename($filename));

    if (!file_exists($path)) {
        abort(404);
    }

    return response()->download($path);
})->name('documents.download');

// Admin Authentication Routes (also used by Team Leaders)
Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login'); // Alias for auth middleware
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

// Protected Admin Routes
Route::middleware(['admin'])->group(function () {
    Route::get('/admin', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard.home');
    Route::post('/admin/attendance/{attendance}/approve-overtime', [AdminDashboardController::class, 'approveOvertime'])->name('admin.attendance.approve-overtime');

    // Task routes
    Route::post('/admin/tasks', [TaskController::class, 'store'])->name('task.store');
    Route::get('/admin/tasks/{task}', [TaskController::class, 'show'])->name('task.show');
    Route::put('/admin/tasks/{task}', [TaskController::class, 'update'])->name('task.update');
    Route::post('/admin/tasks/{task}/complete', [TaskController::class, 'complete'])->name('task.complete');
    Route::delete('/admin/tasks/{task}', [TaskController::class, 'destroy'])->name('task.destroy');
    Route::get('/admin/intern/{intern}/tasks', [TaskController::class, 'getInternTasks'])->name('intern.tasks');
    Route::post('/admin/interns/{intern}/auto-update-tasks', [TaskController::class, 'autoUpdateInternTasks'])->name('intern.auto-update-tasks');

    // Booking management routes (Admin)
    Route::get('/admin/bookings/pending', [BookingController::class, 'pending'])->name('admin.bookings.pending');
    Route::post('/admin/bookings/{booking}/approve', [BookingController::class, 'approve'])->name('admin.bookings.approve');
    Route::post('/admin/bookings/{booking}/send-email', [BookingController::class, 'sendEmailNotification'])->name('admin.bookings.sendEmail');

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

    // School Management Routes (Admin)
    Route::get('/admin/schools', [SchoolController::class, 'index'])->name('admin.schools.index');
    Route::post('/admin/schools', [SchoolController::class, 'store'])->name('admin.schools.store');
    Route::put('/admin/schools/{school}', [SchoolController::class, 'update'])->name('admin.schools.update');
    Route::delete('/admin/schools/{school}', [SchoolController::class, 'destroy'])->name('admin.schools.destroy');
    Route::post('/admin/schools/{school}/toggle-status', [SchoolController::class, 'toggleStatus'])->name('admin.schools.toggleStatus');

    // Intern Approval Routes (Admin)
    Route::get('/admin/interns/pending', [SchoolController::class, 'getPendingInterns'])->name('admin.interns.pending');
    Route::get('/admin/interns/{intern}', [InternController::class, 'show'])->name('admin.interns.show');
    Route::post('/admin/interns/{intern}/approve', [SchoolController::class, 'approveIntern'])->name('admin.interns.approve');
    Route::post('/admin/interns/{intern}/reject', [SchoolController::class, 'rejectIntern'])->name('admin.interns.reject');
    Route::post('/admin/interns/school/{school}/approve-all', [SchoolController::class, 'approveAllBySchool'])->name('admin.interns.approveAllBySchool');

    // Event Management Routes (Admin)
    Route::post('/admin/events', [EventController::class, 'store'])->name('admin.events.store');
    Route::put('/admin/events/{event}', [EventController::class, 'update'])->name('admin.events.update');
    Route::delete('/admin/events/{event}', [EventController::class, 'destroy'])->name('admin.events.destroy');

    // Team Leader Management Routes (Admin)
    Route::get('/admin/team-leaders', [AdminDashboardController::class, 'teamLeaders'])->name('admin.team-leaders.index');
    Route::post('/admin/team-leaders', [AdminDashboardController::class, 'storeTeamLeader'])->name('admin.team-leaders.store');
    Route::put('/admin/team-leaders/{user}', [AdminDashboardController::class, 'updateTeamLeader'])->name('admin.team-leaders.update');
    Route::delete('/admin/team-leaders/{user}', [AdminDashboardController::class, 'deleteTeamLeader'])->name('admin.team-leaders.destroy');
    Route::patch('/admin/team-leaders/{user}/toggle-status', [AdminDashboardController::class, 'toggleTeamLeaderStatus'])->name('admin.team-leaders.toggle-status');

    // Team Leader AJAX endpoints (for inline dashboard)
    Route::get('/admin/api/team-leaders', [AdminDashboardController::class, 'getTeamLeadersData'])->name('admin.api.team-leaders');
    Route::get('/admin/api/team-reports', [AdminDashboardController::class, 'getTeamReportsData'])->name('admin.api.team-reports');
    Route::post('/admin/api/team-leaders', [AdminDashboardController::class, 'storeTeamLeaderAjax'])->name('admin.api.team-leaders.store');
    Route::put('/admin/api/team-leaders/{user}', [AdminDashboardController::class, 'updateTeamLeaderAjax'])->name('admin.api.team-leaders.update');
    Route::patch('/admin/api/team-leaders/{user}/toggle-status', [AdminDashboardController::class, 'toggleTeamLeaderStatusAjax'])->name('admin.api.team-leaders.toggle-status');
    Route::delete('/admin/api/team-leaders/{user}', [AdminDashboardController::class, 'deleteTeamLeaderAjax'])->name('admin.api.team-leaders.destroy');
    Route::post('/admin/api/team-reports/{report}/review', [AdminDashboardController::class, 'reviewTeamLeaderReportAjax'])->name('admin.api.team-reports.review');
    
    // Intern to Team Leader promotion
    Route::get('/admin/api/schools/{school}/interns', [AdminDashboardController::class, 'getInternsBySchool'])->name('admin.api.school-interns');
    Route::post('/admin/api/team-leaders/promote-intern', [AdminDashboardController::class, 'promoteInternToTeamLeader'])->name('admin.api.team-leaders.promote');

    // Team Leader Reports Review (Admin)
    Route::get('/admin/team-reports', [AdminDashboardController::class, 'teamLeaderReports'])->name('admin.team-reports.index');
    Route::get('/admin/team-reports/{report}', [AdminDashboardController::class, 'showTeamLeaderReport'])->name('admin.team-reports.show');
    Route::post('/admin/team-reports/{report}/review', [AdminDashboardController::class, 'reviewTeamLeaderReport'])->name('admin.team-reports.review');
});

// Team Leader Routes
Route::middleware(['team.leader'])->prefix('team-leader')->name('team-leader.')->group(function () {
    // Dashboard
    Route::get('/', [TeamLeaderController::class, 'dashboard'])->name('dashboard');
    Route::get('/dashboard', [TeamLeaderController::class, 'dashboard'])->name('dashboard.home');

    // API endpoints for live updates
    Route::get('/api/tasks', [TeamLeaderController::class, 'getTasksData'])->name('api.tasks');
    Route::get('/api/stats', [TeamLeaderController::class, 'getDashboardStats'])->name('api.stats');

    // Intern Management (View Only)
    Route::get('/interns', [TeamLeaderController::class, 'interns'])->name('interns');
    Route::get('/interns/{intern}', [TeamLeaderController::class, 'showIntern'])->name('interns.show');

    // Task Management (Full CRUD)
    Route::get('/tasks', [TeamLeaderController::class, 'tasks'])->name('tasks');
    Route::get('/tasks/create', [TeamLeaderController::class, 'createTask'])->name('tasks.create');
    Route::post('/tasks', [TeamLeaderController::class, 'storeTask'])->name('tasks.store');
    Route::get('/tasks/{task}/edit', [TeamLeaderController::class, 'editTask'])->name('tasks.edit');
    Route::put('/tasks/{task}', [TeamLeaderController::class, 'updateTask'])->name('tasks.update');
    Route::delete('/tasks/{task}', [TeamLeaderController::class, 'deleteTask'])->name('tasks.destroy');
    Route::patch('/tasks/{task}/status', [TeamLeaderController::class, 'updateTaskStatus'])->name('tasks.status');
    Route::patch('/tasks/{task}/progress', [TeamLeaderController::class, 'updateTaskProgress'])->name('tasks.progress');

    // Reports
    Route::get('/reports', [TeamLeaderController::class, 'reports'])->name('reports');
    Route::get('/reports/create', [TeamLeaderController::class, 'createReport'])->name('reports.create');
    Route::post('/reports', [TeamLeaderController::class, 'storeReport'])->name('reports.store');
    Route::get('/reports/{report}', [TeamLeaderController::class, 'showReport'])->name('reports.show');
    Route::get('/reports/{report}/edit', [TeamLeaderController::class, 'editReport'])->name('reports.edit');
    Route::put('/reports/{report}', [TeamLeaderController::class, 'updateReport'])->name('reports.update');
    Route::delete('/reports/{report}', [TeamLeaderController::class, 'deleteReport'])->name('reports.destroy');

    // Attendance Viewing
    Route::get('/attendance', [TeamLeaderController::class, 'attendance'])->name('attendance');
});
