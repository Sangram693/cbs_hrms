<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Position;
use App\Models\Company;
use Illuminate\Support\Str;

class PositionSeeder extends Seeder
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
            Position::firstOrCreate([
                'id' => \Illuminate\Support\Str::uuid(),
                'title' => 'Manager for ' . $company->name,
                'level' => 'Senior',
                'department_id' => $department->id,
                'company_id' => $company->id,
            ]);
        }
    }
}
