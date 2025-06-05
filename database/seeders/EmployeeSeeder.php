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
        $employees = [
            ['ref_no' => 'HE102', 'name' => 'SEEMA S KULKARNI', 'doj' => '2021-01-21', 'designation' => 'Technician', 'department' => 'Production', 'employee_type' => 'Permanent', 'role' => 'employee'],
            ['ref_no' => 'HE104', 'name' => 'PADMA', 'doj' => '2021-12-01', 'designation' => 'Office Assistant', 'department' => 'Office Assistant', 'employee_type' => 'Permanent', 'role' => 'employee'],
            ['ref_no' => 'HE117', 'name' => 'GOPI K', 'doj' => '2022-09-14', 'designation' => 'Asst.Engineer-Production', 'department' => 'Production', 'employee_type' => 'Permanent', 'role' => 'employee'],
            ['ref_no' => 'HE130', 'name' => 'MANABENDRA SAHOO', 'doj' => '2023-10-18', 'designation' => 'Site Engineer', 'department' => 'Projects', 'employee_type' => 'Permanent', 'role' => 'employee'],
            ['ref_no' => 'HE16', 'name' => 'SANDIP MAITI', 'doj' => '2012-04-01', 'designation' => 'DGM', 'department' => 'Projects', 'employee_type' => 'Permanent', 'role' => 'employee'],
            ['ref_no' => 'HE25', 'name' => 'MUTHANNA S.S', 'doj' => '2012-04-01', 'designation' => 'Sr.Engineer', 'department' => 'Projects', 'employee_type' => 'Permanent', 'role' => 'employee'],
            ['ref_no' => 'HE29', 'name' => 'JAGAN KUMAR SATHUA', 'doj' => '2018-06-01', 'designation' => 'Asst Manager', 'department' => 'Projects', 'employee_type' => 'Permanent', 'role' => 'employee'],
            ['ref_no' => 'HE36', 'name' => 'SHARATH KUMAR R', 'doj' => '2016-12-16', 'designation' => 'Asst Manager', 'department' => 'Projects', 'employee_type' => 'Permanent', 'role' => 'employee'],
            ['ref_no' => 'HE42', 'name' => 'MAHADEVASWAMY M', 'doj' => '2016-04-18', 'designation' => 'Technician', 'department' => 'Projects', 'employee_type' => 'Permanent', 'role' => 'employee'],
            ['ref_no' => 'HE45', 'name' => 'SUKHEN SABUD', 'doj' => '2016-11-10', 'designation' => 'Sr.Engineer', 'department' => 'Projects', 'employee_type' => 'Permanent', 'role' => 'employee'],
            ['ref_no' => 'HE475', 'name' => 'M VINOD', 'doj' => '2024-05-18', 'designation' => 'Executive- Business Development', 'department' => 'Business Development', 'employee_type' => 'Permanent', 'role' => 'employee'],
            ['ref_no' => 'HE-476', 'name' => 'H M SANDHYA JADHAV', 'doj' => '2024-07-01', 'designation' => 'Executive- Business Development', 'department' => 'Business Development', 'employee_type' => 'Permanent', 'role' => 'employee'],
            ['ref_no' => 'HE-477', 'name' => 'PRABHAT KUMAR PRABHAT', 'doj' => '2024-08-01', 'designation' => 'Senior Engineer', 'department' => 'Projects', 'employee_type' => 'Permanent', 'role' => 'employee'],
            ['ref_no' => 'HE-478', 'name' => 'BADEPPAGARI SOUJANYA', 'doj' => '2024-08-01', 'designation' => 'Stores Assistant', 'department' => 'Stores', 'employee_type' => 'Permanent', 'role' => 'employee'],
            ['ref_no' => 'HE61', 'name' => 'BINDU H S', 'doj' => '2011-09-19', 'designation' => 'Asst. Manager', 'department' => 'Logistics', 'employee_type' => 'Permanent', 'role' => 'employee'],
            ['ref_no' => 'HE63', 'name' => 'DINAKAR K', 'doj' => '2012-04-01', 'designation' => 'Asst Manager', 'department' => 'Tech.Cell', 'employee_type' => 'Permanent', 'role' => 'employee'],
            ['ref_no' => 'HE64', 'name' => 'RAJESH B', 'doj' => '2012-04-01', 'designation' => 'Stores Assistant', 'department' => 'Stores', 'employee_type' => 'Permanent', 'role' => 'employee'],
            ['ref_no' => 'HE72', 'name' => 'POOJA RAMAKRISHNA HEGDE', 'doj' => '2019-04-01', 'designation' => 'Sr.Accounts Officer', 'department' => 'Account', 'employee_type' => 'Permanent', 'role' => 'employee'],
            ['ref_no' => 'HE82', 'name' => 'DILLIP KUMAR SAHU', 'doj' => '2012-04-01', 'designation' => 'Engineer', 'department' => 'Projects', 'employee_type' => 'Permanent', 'role' => 'employee'],
            ['ref_no' => 'HE85', 'name' => 'VIIRUPAKSHAIAH D S', 'doj' => '2020-08-01', 'designation' => 'Sr.Engineer', 'department' => 'Projects', 'employee_type' => 'Permanent', 'role' => 'employee'],
            ['ref_no' => 'HE-482', 'name' => 'Sumithra B N', 'doj' => '2024-04-02', 'designation' => 'Technician', 'department' => 'Production', 'employee_type' => 'Permanent', 'role' => 'employee'],
            ['ref_no' => 'HE-487', 'name' => 'Soumyajit Paul', 'doj' => '2025-04-24', 'designation' => 'Site Engineer', 'department' => 'Projects', 'employee_type' => 'Permanent', 'role' => 'employee'],
            // New Contractual Employees
            ['ref_no' => 'HE-483', 'name' => 'Tirtha Mukherjee', 'doj' => '2025-04-10', 'designation' => 'Supervisor', 'department' => 'Projects', 'employee_type' => 'Contractual', 'role' => 'employee'],
            ['ref_no' => 'HE-486', 'name' => 'Provakar Saren', 'doj' => '2025-04-10', 'designation' => 'Senior Technician', 'department' => 'Projects', 'employee_type' => 'Contractual', 'role' => 'employee'],
            ['ref_no' => 'HE-490', 'name' => 'Arabindu Ghosh', 'doj' => '2025-04-23', 'designation' => 'Helper', 'department' => 'Projects', 'employee_type' => 'Contractual', 'role' => 'employee'],
            ['ref_no' => 'HE-488', 'name' => 'Bani Israil Sk', 'doj' => '2025-04-28', 'designation' => 'Helper', 'department' => 'Projects', 'employee_type' => 'Contractual', 'role' => 'employee'],
            ['ref_no' => 'HE-489', 'name' => 'Minhajul Biswas', 'doj' => '2025-04-28', 'designation' => 'Helper', 'department' => 'Projects', 'employee_type' => 'Contractual', 'role' => 'employee'],
        ];

        $company = Company::first();

        foreach ($employees as $emp) {
             $email = strtolower(str_replace(' ', '', $emp['name'])) . '@example.com';
            $department = \App\Models\Department::where('name', $emp['department'])->where('company_id', $company->id)->first();
            $designation = \App\Models\Designation::where('title', $emp['designation'])->where('department_id', $department->id)->first();
            $employee = Employee::create([
                'id' => Str::uuid(),
                'emp_id' => $emp['ref_no'],
                'name' => $emp['name'],
                'email' => $email, 
                'hire_date' => $emp['doj'],
                'designation_id' => $designation ? $designation->id : null,
                'department_id' => $department ? $department->id : null,
                'company_id' => $company->id,
                'status' => 'Active',
                'employee_type' => $emp['employee_type'],
                'user_role' => $emp['role']
            ]);
            
            // Create a user for each employee
           
            User::create([
                'id' => $employee->id,
                'name' => $employee->name,
                'email' => $email,               
                'password' => strtolower(str_replace(' ', '', $company->name)) . '@123#',
                'role' => $emp['role'] === 'employee' ? 'user' : $emp['role'], // If employee role is 'employee', user role is 'user', otherwise use the employee role
                'company_id' => $company->id,
            ]);
        }
    }
}

