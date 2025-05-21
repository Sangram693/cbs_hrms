<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee;
use App\Models\Company;
use Illuminate\Support\Str;

class EmployeeSeeder extends Seeder
{
    public function run()
    {
        $companies = \App\Models\Company::all();
        foreach ($companies as $company) {
            // Get department and position for this company
            $department = \App\Models\Department::where('company_id', $company->id)->first();
            $position = \App\Models\Position::where('company_id', $company->id)->first();

            if ($department && $position) {
                Employee::create([
                    'id' => Str::uuid(),
                    'emp_id' => 'EMP-' . strtoupper(substr($company->name, 0, 3)) . '-' . uniqid(),
                    'name' => 'John Doe for ' . $company->name,
                    'email' => 'john.' . strtolower(str_replace(' ', '', $company->name)) . '@example.com',
                    'phone' => '9999999999',
                    'department_id' => $department->id,
                    'position_id' => $position->id,
                    'hire_date' => '2025-05-20',
                    'salary' => 50000,
                    'user_role' => 'employee',
                    'status' => 'Active',
                    'company_id' => $company->id,
                ]);
            }
        }
    }
}
