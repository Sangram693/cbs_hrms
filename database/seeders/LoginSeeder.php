<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Login;
use App\Models\Company;
use Illuminate\Support\Str;

class LoginSeeder extends Seeder
{
    public function run()
    {
        $companies = \App\Models\Company::all();
        foreach ($companies as $company) {
            $employees = \App\Models\Employee::where('company_id', $company->id)->get();
            foreach ($employees as $employee) {
                Login::create([
                    'id' => Str::uuid(),
                    'employee_id' => $employee->id,
                    'company_id' => $company->id,
                    'username' => strtolower(str_replace(' ', '', $employee->name)),
                    'password_hash' => bcrypt('password'),
                    'role' => 'Admin',
                ]);
            }
        }
    }
}
