<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;
use App\Models\Company;
use Illuminate\Support\Str;

class DepartmentSeeder extends Seeder
{
    public function run()
    {
        $companies = \App\Models\Company::all();
        foreach ($companies as $company) {
            Department::create([
                'id' => Str::uuid(),
                'name' => 'HR for ' . $company->name,
                'company_id' => $company->id,
            ]);
        }
    }
}
