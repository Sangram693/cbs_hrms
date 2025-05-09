<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\UserController;
// use App\Http\Middleware\Cors;
// use App\Http\Middleware\RoleMiddleware;
use Illuminate\Support\Facades\Route;

Route::middleware('cors')->group(function () {
    // Public Routes
Route::post('/login', [UserController::class, 'login'])->name('login');

// Routes for Super Admin
Route::middleware(['auth:sanctum', 'role:super_admin'])->group(function () {
    Route::post('/create-company', [SuperAdminController::class, 'createCompany']);
    Route::get('/company/{companyId}', [SuperAdminController::class, 'readCompany']);
    Route::get('/companies', [SuperAdminController::class, 'readAllCompanies']);
});

// Routes for Admin
Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::post('/create-user', [AdminController::class, 'createUser']);
    Route::get('/my-company', [AdminController::class, 'myCompany']);
});

});