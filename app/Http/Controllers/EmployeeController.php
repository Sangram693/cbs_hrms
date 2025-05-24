<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if ($user->isSuperAdmin()) {
            $employees = Employee::all();
        } elseif ($user->isAdmin()) {
            $employees = Employee::where('company_id', $user->company_id)->get();
        } elseif ($user->isUser() && $user->employee) {
            // HR: can manage all employees in their department
            $hrDepartments = \App\Models\Department::where('hr_id', $user->employee->id)->pluck('id');
            if ($hrDepartments->count() > 0) {
                $employees = Employee::whereIn('department_id', $hrDepartments)->get();
            } else {
                // Normal user: only see self
                $employees = Employee::where('id', $user->employee->id)->get();
            }
        } else {
            $employees = collect();
        }
        return view('employees.index', compact('employees'));
    }

    public function create()
    {
        $user = auth()->user();
        if ($user->isSuperAdmin()) {
            $departments = \App\Models\Department::all();
            $positions = \App\Models\Position::all();
            $companies = \App\Models\Company::all();
        } else {
            $departments = \App\Models\Department::where('company_id', $user->company_id)->get();
            $positions = \App\Models\Position::whereHas('department', function($q) use ($user) {
                $q->where('company_id', $user->company_id);
            })->get();
            $companies = \App\Models\Company::where('id', $user->company_id)->get();
        }
        return view('employees.create', compact('departments', 'positions', 'companies'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email',
            'emp_id' => 'required|string|unique:employees,emp_id',
            'phone' => 'nullable|string|max:255',
            'salary' => 'required|numeric|min:0',
            'position_id' => 'required|exists:positions,id',
            'department_id' => 'required|exists:departments,id',
            'company_id' => 'required|exists:companies,id',
            'user_role' => 'required|in:employee,admin,super_admin',
            'fingerprint_id' => 'required|string|unique:employees,fingerprint_id',
            // Add other fields as needed
        ]);
        $validated['hire_date'] = now()->toDateString();
        $validated['user_role'] = $request->input('user_role', 'employee');
        $validated['status'] = 'Active';
        $employee = Employee::create($validated);
        // Sync to User table
        \App\Models\User::create([
            'id' => $employee->id,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => 'password', // Set a default or generate/send password
            'role' => $validated['user_role'] === 'employee' ? 'user' : $validated['user_role'],
            'company_id' => $validated['company_id'],
            'active' => true,
        ]);
        return redirect()->route('employees.index')->with('success', 'Employee created successfully.');
    }

    public function edit(Employee $employee)
    {
        $user = auth()->user();
        if ($user->isSuperAdmin()) {
            $departments = \App\Models\Department::all();
            $positions = \App\Models\Position::all();
            $companies = \App\Models\Company::all();
        } else {
            $departments = \App\Models\Department::where('company_id', $user->company_id)->get();
            $positions = \App\Models\Position::whereHas('department', function($q) use ($user) {
                $q->where('company_id', $user->company_id);
            })->get();
            $companies = \App\Models\Company::where('id', $user->company_id)->get();
        }
        return view('employees.edit', compact('employee', 'departments', 'positions', 'companies'));
    }

    public function update(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email,' . $employee->id,
            'emp_id' => 'required|string|unique:employees,emp_id,' . $employee->id,
            'phone' => 'nullable|string|max:255',
            'salary' => 'required|numeric|min:0',
            'position_id' => 'required|exists:positions,id',
            'department_id' => 'required|exists:departments,id',
            'company_id' => 'required|exists:companies,id',
            'user_role' => 'required|in:employee,admin,super_admin',
            'fingerprint_id' => 'nullable|string|max:255|unique:employees,fingerprint_id,' . $employee->id,
            // Add other fields as needed
        ]);
        $validated['user_role'] = $request->input('user_role', $employee->user_role ?? 'employee');
        $employee->update($validated);
        // Sync to User table
        $user = \App\Models\User::find($employee->id);
        if ($user) {
            $user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'role' => $validated['user_role'] === 'employee' ? 'user' : $validated['user_role'],
                'company_id' => $validated['company_id'],
            ]);
        }
        return redirect()->route('employees.index')->with('success', 'Employee updated successfully.');
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();
        return redirect()->route('employees.index')->with('success', 'Employee deleted successfully.');
    }
}
