<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CompanySeeder extends Seeder
{
    public function run(): void
    {
        // Create two sample companies
        \App\Models\Company::create([
            'id' => Str::uuid(),
            'name' => 'Acme Corp',
            'address' => '123 Acme Street',
            'phone' => '123-456-7890',
        ]);
        \App\Models\Company::create([
            'id' => Str::uuid(),
            'name' => 'CBS',
            'address' => 'Kolkata',
            'phone' => '987-654-3210',
        ]);
    }
}
