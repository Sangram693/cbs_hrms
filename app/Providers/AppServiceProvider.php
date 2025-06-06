<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Attendance;
use App\Models\Leave;
use App\Observers\AttendanceObserver;
use App\Observers\LeaveObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Attendance::observe(AttendanceObserver::class);
        Leave::observe(LeaveObserver::class);
    }
}
