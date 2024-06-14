<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::post('/store', [StudentController::class, 'store']);
Route::get('/status', [StudentController::class, 'status']);
Route::middleware('auth:admin')->group(function () {
    Route::get('/admin/submissions', [StudentController::class, 'adminIndex']);
    Route::post('/admin/update-status', [StudentController::class, 'updateStatus']);
    Route::get('/admin/admissions', [StudentController::class, 'adminAdmissions']);
});

