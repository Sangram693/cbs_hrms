<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Attendance;
use App\Models\Company;
use Illuminate\Support\Str;

class AttendanceSeeder extends Seeder
{
    public function run()
    {
        $companies = \App\Models\Company::all();
        foreach ($companies as $company) {
            // Get all employees for this company
            $employees = \App\Models\Employee::where('company_id', $company->id)->get();
            foreach ($employees as $employee) {
                Attendance::create([
                    'id' => Str::uuid(),
                    'employee_id' => $employee->id,
                    'company_id' => $company->id,
                    'date' => now()->toDateString(),
                    'status' => 'Absent',
                ]);
            }
        }
    }
}
