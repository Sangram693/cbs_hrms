<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DesignationController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\SalaryController;
use App\Http\Controllers\TrainingController;

// Public login page
Route::get('/login', function () {
    return view('login');
})->name('login')->middleware('guest');

// Handle login POST (session-based)
Route::post('/login', [UserController::class, 'sessionLogin'])->name('login.post')->middleware('guest');

// Logout
Route::post('/logout', function () {
    Auth::logout();
    return redirect()->route('login');
})->name('logout')->middleware('auth');

// Show welcome page with conditional redirect based on auth status
Route::get('/', function () {
    return view('welcome');
});

// Protected routes (session auth)
Route::middleware('auth')->group(function () {
    // Role-based dashboard route
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');

    Route::resource('companies', CompanyController::class);
    Route::resource('employees', EmployeeController::class);
    Route::resource('departments', DepartmentController::class);
    Route::resource('designations', DesignationController::class);
    Route::resource('attendance', AttendanceController::class);
    Route::resource('leaves', LeaveController::class)->parameters(['leaves' => 'leave']);
    Route::resource('salaries', SalaryController::class);
    Route::resource('trainings', TrainingController::class);
    Route::resource('leavetypes', App\Http\Controllers\LeaveTypeController::class);

    // Add this route for changing leave status
    Route::post('/leaves/{leave}/change-status', [App\Http\Controllers\LeaveController::class, 'changeStatus'])->name('leaves.changeStatus');

    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');

   
});



