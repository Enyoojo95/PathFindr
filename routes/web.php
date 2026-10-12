<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Landing page
Route::get('/', function () {
    $testimonials = \App\Models\Testimonial::where('approved', true)
        ->with('user')->latest()->limit(12)->get();
    $registrationCounts = app(\App\Services\PlatformStatisticsService::class)->registrationCounts();

    return view('landing.index', array_merge($registrationCounts, compact('testimonials')));
})->name('home');

// School registration (public)
Route::get('/register-school',  [\App\Http\Controllers\SchoolRegistrationController::class, 'create'])->name('school-registration');
Route::post('/register-school', [\App\Http\Controllers\SchoolRegistrationController::class, 'store'])->name('school-registration.store');

// Dashboard — redirects based on role
Route::get('/dashboard', function () {
    $user = Auth::user();
    if ($user->isSuperAdmin() || $user->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('student.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Student dashboard
Route::get('/student/dashboard', function () {
    return view('student.dashboard');
})->middleware(['auth', 'verified'])->name('student.dashboard');

// Student results & reports
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/student/results', [\App\Http\Controllers\StudentResultsController::class, 'results'])->name('student.results');
    Route::get('/student/reports', [\App\Http\Controllers\StudentResultsController::class, 'reports'])->name('student.reports');
});

// Testimonials
Route::post('/testimonials', [\App\Http\Controllers\TestimonialController::class, 'store'])
    ->middleware(['auth', 'verified'])->name('testimonials.store');
Route::post('/testimonials/{testimonial}/approve', [\App\Http\Controllers\TestimonialController::class, 'approve'])
    ->middleware(['auth', 'verified'])->name('testimonials.approve');
Route::delete('/testimonials/{testimonial}', [\App\Http\Controllers\TestimonialController::class, 'destroy'])
    ->middleware(['auth', 'verified'])->name('testimonials.destroy');

// Exam
Route::get('/exam',        [\App\Http\Controllers\ExamController::class, 'start'])->middleware(['auth', 'verified'])->name('exam.start');
Route::post('/exam',       [\App\Http\Controllers\ExamController::class, 'submit'])->middleware(['auth', 'verified'])->name('exam.submit');
Route::post('/exam/answer', [\App\Http\Controllers\ExamController::class, 'saveAnswer'])->middleware(['auth', 'verified'])->name('exam.answer');
Route::get('/exam/result', [\App\Http\Controllers\ExamController::class, 'result'])->middleware(['auth', 'verified'])->name('exam.result');
Route::get('/exam/result/{id}/pdf', [\App\Http\Controllers\ResultController::class, 'downloadPdf'])->middleware(['auth', 'verified'])->name('exam.result.pdf');
// Exam real-time
Route::post('/exam/heartbeat',           [\App\Http\Controllers\ExamController::class, 'heartbeat'])->middleware(['auth'])->name('exam.heartbeat');
Route::post('/exam/admin/pause/{userId}',[\App\Http\Controllers\ExamController::class, 'adminPause'])->middleware(['auth'])->name('exam.admin.pause');
Route::post('/exam/admin/resume/{userId}',[\App\Http\Controllers\ExamController::class, 'adminResume'])->middleware(['auth'])->name('exam.admin.resume');
Route::get('/exam/admin/active',         [\App\Http\Controllers\ExamController::class, 'activeExams'])->middleware(['auth'])->name('exam.admin.active');

// Admin dashboard
Route::get('/admin/dashboard', [\App\Http\Controllers\AdminDashboardController::class, 'index'])
    ->middleware(['auth', 'verified', 'admin'])
    ->name('admin.dashboard');

// Admin — Students
Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/students',          [\App\Http\Controllers\Admin\StudentController::class, 'index'])->name('students.index');
    Route::get('/students/create',   [\App\Http\Controllers\Admin\StudentController::class, 'create'])->name('students.create');
    Route::post('/students',         [\App\Http\Controllers\Admin\StudentController::class, 'store'])->name('students.store');
    Route::delete('/students/{user}',[\App\Http\Controllers\Admin\StudentController::class, 'destroy'])->name('students.destroy');
    Route::post('/students/{user}/reset-exam', [\App\Http\Controllers\Admin\StudentController::class, 'resetExam'])->name('students.reset-exam');

    // Admin — Questions
    Route::get('/questions',              [\App\Http\Controllers\Admin\QuestionController::class, 'index'])->name('questions.index');
    Route::get('/questions/create',       [\App\Http\Controllers\Admin\QuestionController::class, 'create'])->name('questions.create');
    Route::post('/questions',             [\App\Http\Controllers\Admin\QuestionController::class, 'store'])->name('questions.store');
    Route::get('/questions/{question}/edit', [\App\Http\Controllers\Admin\QuestionController::class, 'edit'])->name('questions.edit');
    Route::put('/questions/{question}',      [\App\Http\Controllers\Admin\QuestionController::class, 'update'])->name('questions.update');
    Route::delete('/questions/{question}',   [\App\Http\Controllers\Admin\QuestionController::class, 'destroy'])->name('questions.destroy');

    // Admin — Export
    Route::get('/export',          [\App\Http\Controllers\Admin\ExportController::class, 'index'])->name('export.index');
    Route::get('/export/download', [\App\Http\Controllers\Admin\ExportController::class, 'download'])->name('export.download');
});

// Profile routes (Breeze)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/avatar', [\App\Http\Controllers\ProfileController::class, 'updateAvatar'])->name('profile.avatar');
    Route::delete('/profile', [\App\Http\Controllers\ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Auth routes (Breeze)
require __DIR__.'/auth.php';

// ── Super Admin ───────────────────────────────────────────────
// Public auth routes
Route::prefix('super-admin')->name('super-admin.')->group(function () {
    Route::get('/login',  [\App\Http\Controllers\SuperAdmin\AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [\App\Http\Controllers\SuperAdmin\AuthController::class, 'login'])->middleware('throttle:5,1')->name('login.post');
    Route::get('/signup',  [\App\Http\Controllers\SuperAdmin\AuthController::class, 'showSignup'])->middleware('throttle:10,1')->name('signup');
    Route::post('/signup', [\App\Http\Controllers\SuperAdmin\AuthController::class, 'signup'])->middleware('throttle:5,1')->name('signup.post');
    Route::post('/logout', [\App\Http\Controllers\SuperAdmin\AuthController::class, 'logout'])->name('logout');

    // Protected routes
    Route::middleware('super_admin')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\SuperAdmin\DashboardController::class, 'index'])->name('dashboard');

        // Schools
        Route::get('/schools', [\App\Http\Controllers\SuperAdmin\SchoolController::class, 'index'])->name('schools.index');

        // Students
        Route::get('/students',        [\App\Http\Controllers\SuperAdmin\StudentController::class, 'index'])->name('students.index');
        Route::get('/students/{user}', [\App\Http\Controllers\SuperAdmin\StudentController::class, 'show'])->name('students.show');

        // Admins
        Route::get('/admins',         [\App\Http\Controllers\SuperAdmin\AdminController::class, 'index'])->name('admins.index');
        Route::post('/admins',        [\App\Http\Controllers\SuperAdmin\AdminController::class, 'store'])->name('admins.store');
        Route::post('/admins/{user}/suspend', [\App\Http\Controllers\SuperAdmin\AdminController::class, 'suspend'])->name('admins.suspend');
        Route::delete('/admins/{user}', [\App\Http\Controllers\SuperAdmin\AdminController::class, 'destroy'])->name('admins.destroy');

        // Questions
        Route::get('/questions',                      [\App\Http\Controllers\SuperAdmin\QuestionController::class, 'index'])->name('questions.index');
        Route::post('/questions',                     [\App\Http\Controllers\SuperAdmin\QuestionController::class, 'store'])->name('questions.store');
        Route::put('/questions/{question}',           [\App\Http\Controllers\SuperAdmin\QuestionController::class, 'update'])->name('questions.update');
        Route::delete('/questions/{question}',        [\App\Http\Controllers\SuperAdmin\QuestionController::class, 'destroy'])->name('questions.destroy');

        // Settings
        Route::get('/settings',       [\App\Http\Controllers\SuperAdmin\SettingsController::class, 'index'])->name('settings.index');
        Route::post('/settings',      [\App\Http\Controllers\SuperAdmin\SettingsController::class, 'update'])->name('settings.update');
        Route::post('/maintenance',   [\App\Http\Controllers\SuperAdmin\SettingsController::class, 'maintenance'])->name('maintenance');

        // Analytics
        Route::get('/analytics',          [\App\Http\Controllers\SuperAdmin\AnalyticsController::class, 'index'])->name('analytics.index');
        Route::get('/analytics/download', [\App\Http\Controllers\SuperAdmin\AnalyticsController::class, 'download'])->name('analytics.download');

        // Profile
        Route::get('/profile',  [\App\Http\Controllers\SuperAdmin\AuthController::class, 'showProfile'])->name('profile');
        Route::post('/profile', [\App\Http\Controllers\SuperAdmin\AuthController::class, 'updateProfile'])->name('profile.update');

        // Logs (simple view)
        Route::get('/logs', function () {
            $logs = \App\Models\User::latest()->limit(50)->get()->map(fn($u) => [
                'user'   => $u->name,
                'role'   => $u->role,
                'action' => 'Registered',
                'time'   => $u->created_at->diffForHumans(),
            ]);
            return view('super-admin.logs.index', compact('logs'));
        })->name('logs.index');

        // Testimonials
        Route::get('/testimonials', [\App\Http\Controllers\SuperAdmin\TestimonialController::class, 'index'])->name('testimonials.index');
        Route::patch('/testimonials/{testimonial}/unapprove', [\App\Http\Controllers\SuperAdmin\TestimonialController::class, 'unapprove'])->name('testimonials.unapprove');
    });
});
