<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Leave;
use App\Models\Company;
use Illuminate\Support\Str;

class LeaveSeeder extends Seeder
{
    public function run()
    {
        $companies = \App\Models\Company::all();
        foreach ($companies as $company) {
            $employees = \App\Models\Employee::where('company_id', $company->id)->get();
            foreach ($employees as $employee) {
                Leave::create([
                    'id' => Str::uuid(),
                    'employee_id' => $employee->id,
                    'company_id' => $company->id,
                    'start_date' => '2025-05-21',
                    'end_date' => '2025-05-22',
                    'leave_type' => 'Sick',
                    'status' => 'Pending',
                ]);
            }
        }
    }
}
