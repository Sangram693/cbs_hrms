<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee;
use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class EmployeeSeeder extends Seeder
{
    public function run()
    {
        $companies = \App\Models\Company::all();
        foreach ($companies as $company) {            // Get department and designation for this company
            $department = \App\Models\Department::where('company_id', $company->id)->first();
            $designation = \App\Models\Designation::where('company_id', $company->id)->first();

           
                // Create user first
                

                // Then create employee linked to the user
                $employee = Employee::create([
                    'emp_id' => 'EMP-' . strtoupper(substr($company->name, 0, 3)) . '-' . uniqid(),
                    'name' => 'John Doe for ' . $company->name,
                    'email' => 'john.' . strtolower(str_replace(' ', '', $company->name)) . '@example.com', 
                    'phone' => '9999999999',                    'department_id' => $department->id ?? null,
                    'designation_id' => $designation->id ?? null,
                    'hire_date' => '2025-05-20',
                    'salary' => 50000,
                    'user_role' => 'employee',
                    'status' => 'Active',
                    'company_id' => $company->id,
                ]);

                 User::create([
                    'id' => $employee->id,
                    'name' => $employee->name,
                    'email' => $employee->email,
                    'password' => 'password',
                    'role' => 'user'
                ]);
            }
        }
    }

