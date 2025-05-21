<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\SalaryController;
use App\Http\Controllers\TrainingController;
use App\Http\Controllers\LoginController;

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

// Protected routes (session auth)
Route::middleware('auth')->group(function () {
    // Role-based dashboard route
    Route::get('/dashboard', function () {
        $user = auth()->user();
        if ($user->role === 'super_admin') {
            // Compute stats for superadmin
            $stats = [
                'companies' => \DB::table('companies')->count(),
                'employees' => \DB::table('users')->where('role', 'user')->where('active', true)->count(),
                'departments' => \DB::table('departments')->count(),
                'positions' => \DB::table('positions')->count(),
                'attendance' => \DB::table('attendances')->count(),
                'leaves' => \DB::table('leaves')->count(),
                'salaries' => \DB::table('salaries')->count(),
                'trainings' => \DB::table('trainings')->count(),
                'logins' => \DB::table('logins')->count(),
            ];
            return view('dashboard_superadmin', compact('stats'));
        } elseif ($user->role === 'admin') {
            $companyId = $user->company_id;
            $stats = [
                'companies' => 1,
                'employees' => \DB::table('users')->where('company_id', $companyId)->where('role', 'user')->where('active', true)->count(),
                'departments' => \DB::table('departments')->where('company_id', $companyId)->count(),
                'positions' => \DB::table('positions')->where('company_id', $companyId)->count(),
                'attendance' => \DB::table('attendances')->where('company_id', $companyId)->count(),
                'leaves' => \DB::table('leaves')->where('company_id', $companyId)->count(),
                'salaries' => \DB::table('salaries')->where('company_id', $companyId)->count(),
                'trainings' => \DB::table('trainings')->where('company_id', $companyId)->count(),
                'logins' => \DB::table('logins')->where('company_id', $companyId)->count(),
            ];
            return view('dashboard_admin', compact('stats'));
        } else {
            return view('dashboard_user');
        }
    })->name('dashboard');

    Route::resource('companies', CompanyController::class);
    Route::resource('employees', EmployeeController::class);
    Route::resource('departments', DepartmentController::class);
    Route::resource('positions', PositionController::class);
    Route::resource('attendance', AttendanceController::class);
    Route::resource('leaves', LeaveController::class);
    Route::resource('salaries', SalaryController::class);
    Route::resource('trainings', TrainingController::class);
    Route::resource('logins', LoginController::class);
});



