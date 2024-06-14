<?php

// web.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\Auth\AdminLoginController;

Route::get('/', [StudentController::class, 'index'])->name('home');
Route::get('/admission-form', [StudentController::class, 'create'])->name('admission_form');
Route::post('/admission-form', [StudentController::class, 'store'])->name('admission_form.submit');
Route::get('/status', [StudentController::class, 'status'])->name('status');

Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminLoginController::class, 'showLoginForm'])->name('admin_login');
    Route::post('/login', [AdminLoginController::class, 'login'])->name('admin_login_submit');
    Route::post('/logout', [AdminLoginController::class, 'logout'])->name('admin_logout');

    Route::middleware(['auth:admin'])->group(function () {
        Route::get('/submissions', [StudentController::class, 'adminIndex'])->name('admin_submissions');
        Route::get('/admissions', [StudentController::class, 'adminAdmissions'])->name('admin_admissions');
        Route::post('/update-status', [StudentController::class, 'updateStatus'])->name('admin_update_status');
    });
});

require __DIR__.'/auth.php';
