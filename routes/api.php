<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;



Route::post('/login', [UserController::class, 'login'])->name('login');
Route::middleware(['auth:sanctum', 'role:super_admin'])->post('/create-company', [SuperAdminController::class, 'createCompany']);
Route::middleware(['auth:sanctum', 'role:admin'])->post('/create-user', [AdminController::class, 'createUser']);
