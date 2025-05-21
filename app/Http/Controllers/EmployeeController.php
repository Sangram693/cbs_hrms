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
        } else {
            $employees = Employee::where('company_id', $user->company_id)->get();
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
            'position_id' => 'required|exists:positions,id',
            'department_id' => 'required|exists:departments,id',
            'company_id' => 'required|exists:companies,id',
            // Add other fields as needed
        ]);
        $validated['id'] = \Illuminate\Support\Str::uuid();
        $validated['emp_id'] = 'EMP-' . strtoupper(substr($validated['name'], 0, 3)) . '-' . uniqid();
        $validated['hire_date'] = now()->toDateString();
        $validated['salary'] = 0;
        $validated['user_role'] = 'employee';
        $validated['status'] = 'Active';
        Employee::create($validated);
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
            'position_id' => 'required|exists:positions,id',
            'department_id' => 'required|exists:departments,id',
            'company_id' => 'required|exists:companies,id',
            // Add other fields as needed
        ]);
        $employee->update($validated);
        return redirect()->route('employees.index')->with('success', 'Employee updated successfully.');
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();
        return redirect()->route('employees.index')->with('success', 'Employee deleted successfully.');
    }
}
