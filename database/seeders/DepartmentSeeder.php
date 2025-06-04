<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;
use App\Models\Company;
use App\Models\Designation;
use Illuminate\Support\Str;

class DepartmentSeeder extends Seeder
{
    public function run()
    {
        $departments = [
            'Production',
            'Office Assistant',
            'Projects',
            'Business Development',
            'Stores',
            'Logistics',
            'Tech.Cell',
            'Account',
        ];
        $designationMap = [
            'Production' => ['Technician', 'Asst.Engineer-Production'],
            'Office Assistant' => ['Office Assistant'],
            'Projects' => ['Site Engineer', 'DGM', 'Sr.Engineer', 'Asst Manager', 'Engineer', 'Senior Engineer', 'Supervisor', 'Senior Technician', 'Helper'],
            'Business Development' => ['Executive- Business Development'],
            'Stores' => ['Stores Assistant'],
            'Logistics' => ['Asst. Manager'],
            'Tech.Cell' => ['Asst Manager'],
            'Account' => ['Sr.Accounts Officer'],
        ];
        $companies = \App\Models\Company::all();
        foreach ($companies as $company) {
            foreach ($departments as $dept) {
                $department = Department::create([
                    'id' => Str::uuid(),
                    'name' => $dept,
                    'company_id' => $company->id,
                ]);
                if (isset($designationMap[$dept])) {
                    foreach ($designationMap[$dept] as $title) {
                        \App\Models\Designation::create([
                            'id' => Str::uuid(),
                            'title' => $title,
                            'department_id' => $department->id,
                            'company_id' => $company->id,
                        ]);
                    }
                }
            }
        }

    }
}
