<?php

use App\Http\Controllers\StudentController;
use App\Http\Controllers\Auth\AdminLoginController;
use Illuminate\Support\Facades\Route;

// Client Side Routes
Route::get('/', [StudentController::class, 'index'])->name('home');
Route::get('/admission-form', [StudentController::class, 'create'])->name('admission_form');
Route::post('/admission-form', [StudentController::class, 'store'])->name('submit_admission');
Route::get('/status', [StudentController::class, 'status'])->name('status');

// Admin Login Routes
Route::get('/admin/login', [AdminLoginController::class, 'showLoginForm'])->name('admin_login');
Route::post('/admin/login', [AdminLoginController::class, 'login'])->name('admin_login_submit');
Route::post('/admin/logout', [AdminLoginController::class, 'logout'])->name('admin_logout');

// Admin Routes with Middleware Protection
Route::group(['middleware' => 'auth.admin'], function () {
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin_dashboard');

    Route::get('/admin/submissions', [StudentController::class, 'adminIndex'])->name('admin_submissions');
    Route::get('/admin/admissions', [StudentController::class, 'adminAdmissions'])->name('admin_admissions');
    Route::post('/admin/update-status', [StudentController::class, 'updateStatus'])->name('update_status');
});




require __DIR__.'/auth.php';
