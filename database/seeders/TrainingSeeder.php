<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Training;
use App\Models\Company;
use Illuminate\Support\Str;

class TrainingSeeder extends Seeder
{
    public function run()
    {
        $companies = \App\Models\Company::all();
        foreach ($companies as $company) {
            $employees = \App\Models\Employee::where('company_id', $company->id)->get();
            foreach ($employees as $employee) {
                Training::create([
                    'id' => Str::uuid(),
                    'employee_id' => $employee->id,
                    'company_id' => $company->id,
                    'training_name' => 'Onboarding',
                    'status' => 'Not Started',
                    'start_date' => '2025-05-23',
                    'end_date' => '2025-05-24',
                ]);
            }
        }
    }
}
