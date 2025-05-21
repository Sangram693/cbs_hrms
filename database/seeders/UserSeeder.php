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
        User::firstOrCreate(
            ['email' => 'superadmin@example.com'],
            [
                'id' => Str::uuid(),
                'name' => 'Super Admin',
                'password' => 'password',
                'role' => 'super_admin',
                'company_id' => null,
            ]
        );

        // Admin and user for each company
        foreach ($companies as $company) {
            User::firstOrCreate(
                ['email' => 'admin_' . strtolower($company->name) . '@example.com'],
                [
                    'id' => Str::uuid(),
                    'name' => $company->name . ' Admin',
                    'password' => 'password',
                    'role' => 'admin',
                    'company_id' => $company->id,
                ]
            );
            User::firstOrCreate(
                ['email' => 'user_' . strtolower($company->name) . '@example.com'],
                [
                    'id' => Str::uuid(),
                    'name' => $company->name . ' User',
                    'password' => 'password',
                    'role' => 'user',
                    'company_id' => $company->id,
                ]
            );
        }
    }
}
