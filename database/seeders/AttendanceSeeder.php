<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Attendance;
use App\Models\Company;
use Carbon\Carbon;
use Illuminate\Support\Str;

class AttendanceSeeder extends Seeder
{
    public function run()
    {
        $companies = \App\Models\Company::all();

        // Define attendance patterns for more realistic data
        $patterns = [
            // Regular attendance (70% probability)
            'regular' => [
                'probability' => 70,
                'check_in_range' => ['08:45', '09:15'],
                'check_out_range' => ['17:00', '17:30'],
                'status' => 'Present'
            ],
            // Late arrival (15% probability)
            'late' => [
                'probability' => 15,
                'check_in_range' => ['09:30', '10:30'],
                'check_out_range' => ['17:30', '18:30'],
                'status' => 'Present'
            ],
            // Leave (10% probability)
            'leave' => [
                'probability' => 10,
                'status' => 'Leave'
            ],
            // Absent (5% probability)
            'absent' => [
                'probability' => 5,
                'status' => 'Absent'
            ],
        ];

        foreach ($companies as $company) {
            $employees = \App\Models\Employee::where('company_id', $company->id)->get();
            
            foreach ($employees as $employee) {
                // Create attendance for last 30 days
                for ($i = 0; $i < 30; $i++) {
                    $date = now()->subDays($i);
                    
                    // Skip weekends
                    if ($date->isSunday()) {
                        continue;
                    }

                    if ($date->isSaturday()) {
                        $weekOfMonth = $date->weekOfMonth;
                        if ($weekOfMonth === 2 || $weekOfMonth === 4) {
                            continue; // Skip 2nd and 4th Saturday
                            }
                        }

                    // Randomly select a pattern based on probabilities
                    $rand = rand(1, 100);
                    $cumulative = 0;
                    $selectedPattern = null;

                    foreach ($patterns as $pattern) {
                        $cumulative += $pattern['probability'];
                        if ($rand <= $cumulative) {
                            $selectedPattern = $pattern;
                            break;
                        }
                    }

                    $attendanceData = [
                        'id' => Str::uuid(),
                        'employee_id' => $employee->id,
                        'company_id' => $company->id,
                        'date' => $date->toDateString(),
                        'status' => $selectedPattern['status']
                    ];

                    // Set check-in and check-out times for present status
                    if ($selectedPattern['status'] === 'Present') {
                        // Parse time ranges
                        $checkInStart = Carbon::parse($selectedPattern['check_in_range'][0]);
                        $checkInEnd = Carbon::parse($selectedPattern['check_in_range'][1]);
                        $checkOutStart = Carbon::parse($selectedPattern['check_out_range'][0]);
                        $checkOutEnd = Carbon::parse($selectedPattern['check_out_range'][1]);

                        // Generate random times within ranges
                        $checkInMinutes = rand(
                            $checkInStart->hour * 60 + $checkInStart->minute,
                            $checkInEnd->hour * 60 + $checkInEnd->minute
                        );
                        $checkOutMinutes = rand(
                            $checkOutStart->hour * 60 + $checkOutStart->minute,
                            $checkOutEnd->hour * 60 + $checkOutEnd->minute
                        );

                        $checkIn = Carbon::today()
                            ->addMinutes($checkInMinutes)
                            ->format('H:i:s');
                        $checkOut = Carbon::today()
                            ->addMinutes($checkOutMinutes)
                            ->format('H:i:s');

                        $attendanceData['check_in'] = $checkIn;
                        $attendanceData['check_out'] = $checkOut;
                    } else {
                        $attendanceData['check_in'] = null;
                        $attendanceData['check_out'] = null;
                    }

                    Attendance::create($attendanceData);
                }
            }
        }
    }
}
