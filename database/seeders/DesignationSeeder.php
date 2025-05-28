<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Designation;
use App\Models\Company;
use Illuminate\Support\Str;

class DesignationSeeder extends Seeder
{
    public function run()
    {
        $companies = \App\Models\Company::all();
        foreach ($companies as $company) {
            // Ensure a department exists for this company
            $department = \App\Models\Department::where('company_id', $company->id)->first();
            if (!$department) {
                $department = \App\Models\Department::create([
                    'id' => \Illuminate\Support\Str::uuid(),
                    'name' => 'General Department',
                    'company_id' => $company->id,
                ]);
            }

            // Create some common designations
            $designations = [
                ['title' => 'Manager', 'level' => 'Senior'],
                ['title' => 'Team Lead', 'level' => 'Mid'],
                ['title' => 'Developer', 'level' => 'Junior'],
                ['title' => 'HR Manager', 'level' => 'Senior'],
                ['title' => 'HR Executive', 'level' => 'Mid'],
            ];

            foreach ($designations as $designation) {
                Designation::firstOrCreate([
                    'id' => Str::uuid(),
                    'title' => $designation['title'],
                    'level' => $designation['level'],
                    'department_id' => $department->id,
                    'company_id' => $company->id,
                ]);
            }
        }
    }
}
