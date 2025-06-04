<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Company;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $companies = Company::all();

        // Super Admin (global, not tied to a company)
        $superAdmin = User::firstOrCreate(
            ['email' => 'sneider@cbsiot.live'],
            [
                'id' => Str::uuid(),
                'name' => 'Super Admin',
                'password' => 'Sneider@143#',
                'role' => 'super_admin',
                'company_id' => null,
            ]
        );

        // Admin and user for each company
        foreach ($companies as $company) {
            // Create admin employee first
            $adminEmployee = \App\Models\Employee::create([
                'name' => $company->name . ' Admin',
                'email' => 'admin_' . strtolower($company->name) . '@example.com',
                'company_id' => $company->id,
                'user_role' => 'admin',
                'status' => 'Active',
            ]);


            // Create admin user with employee's ID
            User::create([
                'id' => $adminEmployee->id,
                'name' => $adminEmployee->name,
                'email' => $adminEmployee->email,
                'password' =>  strtolower(str_replace(' ', '', $company->name)) . '@123#',
                'role' => 'admin',
                'company_id' => $company->id,
            ]);

        }
    }
}
