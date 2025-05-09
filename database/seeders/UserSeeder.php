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
        $company = Company::first(); // Use first seeded company

        if (!$company) {
            throw new \Exception('No company found. Make sure CompanySeeder runs before UserSeeder.');
        }
       // Super Admin
User::firstOrCreate(
    ['email' => 'superadmin@example.com'], // Check for existing email
    [
        'id' => Str::uuid(),
        'name' => 'Super Admin',
        'password' => 'password',
        'role' => 'super_admin',
        'company_id' => null,
    ]
);

// Admin
User::firstOrCreate(
    ['email' => 'admin@example.com'],
    [
        'id' => Str::uuid(),
        'name' => 'Admin User',
        'password' => 'password',
        'role' => 'admin',
        'company_id' => $company->id,
    ]
);

// Regular user
User::firstOrCreate(
    ['email' => 'user@example.com'],
    [
        'id' => Str::uuid(),
        'name' => 'Employee User',
        'password' => 'password',
        'role' => 'user',
        'company_id' => $company->id,
    ]
);

    }
}
