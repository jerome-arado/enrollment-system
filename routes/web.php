<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DocumentController;
use Illuminate\Support\Facades\Route;

// ── Public ─────────────────────────────────────────────────────
Route::get('/', fn() => redirect()->route('login'));

// Auth
Route::get('/login',    [AuthController::class, 'showLogin'])->name('login');
Route::post('/login',   [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register',[AuthController::class, 'register'])->name('register.post');
Route::post('/logout',  [AuthController::class, 'logout'])->name('logout');

// Forgot / Reset Password
Route::get('/forgot-password',        [ForgotPasswordController::class, 'showForgotForm'])->name('password.forgot');
Route::post('/forgot-password',       [ForgotPasswordController::class, 'sendResetLink'])->name('password.forgot.send');
Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset.form');
Route::post('/reset-password',        [ForgotPasswordController::class, 'resetPassword'])->name('password.reset');

// ── Authenticated ───────────────────────────────────────────────
Route::middleware(['auth'])->group(function () {

    // Profile (all authenticated users)
    Route::get('/profile',          [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile',          [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/password', [ProfileController::class, 'changePasswordForm'])->name('profile.password');
    Route::put('/profile/password', [ProfileController::class, 'changePassword'])->name('profile.password.update');
    Route::post('/profile/picture',     [ProfileController::class, 'updatePicture'])->name('profile.picture.update');
    Route::delete('/profile/picture',   [ProfileController::class, 'removePicture'])->name('profile.picture.remove');

    // ── Shared Document Download ────────────────────────────────
    Route::get('/documents/{document}/download', [DocumentController::class, 'download'])->name('documents.download');

    // ── Student Routes ─────────────────────────────────────────
    Route::middleware(['auth', 'student'])->prefix('student')->name('student.')->group(function () {
        Route::get('/dashboard',   [EnrollmentController::class, 'dashboard'])->name('dashboard');
        Route::get('/enroll',      [EnrollmentController::class, 'create'])->name('enroll');
        Route::post('/enroll',     [EnrollmentController::class, 'store'])->name('enroll.store');
        Route::get('/enroll/edit', [EnrollmentController::class, 'edit'])->name('enroll.edit');
        Route::put('/enroll',      [EnrollmentController::class, 'update'])->name('enroll.update');
        Route::delete('/enroll',   [EnrollmentController::class, 'destroy'])->name('enroll.destroy');
    });

    // ── Admin Routes ───────────────────────────────────────────
    Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
        // Dashboard & Enrollment Management
        Route::get('/dashboard',                      [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/students',                       [AdminController::class, 'students'])->name('students');
        Route::get('/students/{user}',                [AdminController::class, 'showStudent'])->name('students.show');
        Route::delete('/students/{user}',             [AdminController::class, 'destroyStudent'])->name('students.destroy');
        Route::get('/enrollments/{enrollment}',       [AdminController::class, 'show'])->name('enrollment.show');
        Route::put('/enrollments/{enrollment}/status',[AdminController::class, 'updateStatus'])->name('enrollment.status');
        Route::delete('/enrollments/{enrollment}',    [AdminController::class, 'destroy'])->name('enrollment.destroy');

        // Document Status Update (admin only)
        Route::put('/documents/{document}/status', [DocumentController::class, 'updateStatus'])->name('document.status');
    });
});