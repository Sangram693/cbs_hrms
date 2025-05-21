<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Salary;
use App\Models\Company;
use Illuminate\Support\Str;

class SalarySeeder extends Seeder
{
    public function run()
    {
        $companies = \App\Models\Company::all();
        foreach ($companies as $company) {
            $employees = \App\Models\Employee::where('company_id', $company->id)->get();
            foreach ($employees as $employee) {
                Salary::create([
                    'id' => Str::uuid(),
                    'employee_id' => $employee->id,
                    'company_id' => $company->id,
                    'salary_month' => '2025-05-01',
                    'base_salary' => 50000,
                    'bonus' => 2000,
                    'deductions' => 500,
                    'net_salary' => 51500,
                ]);
            }
        }
    }
}
