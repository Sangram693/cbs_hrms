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
            $employees = Employee::with(['department', 'designation', 'company'])->get();
        } elseif ($user->isAdmin()) {
            $employees = Employee::with(['department', 'designation', 'company'])
                ->where('company_id', $user->company_id)
                ->get();
        } elseif ($user->isUser() && $user->employee) {
            // HR: can manage all employees in their department
            $hrDepartments = \App\Models\Department::where('hr_id', $user->employee->id)->pluck('id');
            if ($hrDepartments->count() > 0) {
                $employees = Employee::with(['department', 'designation', 'company'])
                    ->whereIn('department_id', $hrDepartments)
                    ->get();
            } else {
                // Normal user: only see self
                $employees = Employee::with(['department', 'designation', 'company'])
                    ->where('id', $user->employee->id)
                    ->get();
            }
        } else {
            $employees = collect();
        }
        return view('employees.index', compact('employees'));
    }    public function create()
    {
        $user = auth()->user();
        if ($user->isSuperAdmin()) {
            $departments = \App\Models\Department::all();
            $designations = \App\Models\Designation::all();
            $companies = \App\Models\Company::all();
        } elseif ($user->isAdmin()) {
            // Company admin can see all departments and designations in their company
            $departments = \App\Models\Department::where('company_id', $user->company_id)->get();
            $designations = \App\Models\Designation::whereHas('department', function($q) use ($user) {
                $q->where('company_id', $user->company_id);
            })->get();
            $companies = \App\Models\Company::where('id', $user->company_id)->get();
        } elseif ($user->isUser() && $user->employee) {
            // HR can only create employees in their assigned departments
            $hrDepartments = \App\Models\Department::where('hr_id', $user->employee->id)->pluck('id');
            if ($hrDepartments->count() === 0) {
                abort(403, 'You do not have HR permissions in any department.');
            }
            
            $departments = \App\Models\Department::whereIn('id', $hrDepartments)->get();
            $designations = \App\Models\Designation::whereIn('department_id', $hrDepartments)->get();
            $companies = \App\Models\Company::where('id', $user->company_id)->get();
        }
        return view('employees.create', compact('departments', 'designations', 'companies'));
    }    public function store(Request $request)
    {
        $user = auth()->user();
        
        // Set allowed roles based on user type
        if ($user->isSuperAdmin()) {
            $allowedRoles = ['employee', 'admin', 'super_admin'];
        } elseif ($user->isAdmin()) {
            $allowedRoles = ['employee', 'admin'];
        } else {
            // HR can only create regular employees
            $allowedRoles = ['employee'];
            
            // Verify HR has permission for the selected department
            $hrDepartments = \App\Models\Department::where('hr_id', $user->employee->id)->pluck('id');
            if (!$hrDepartments->contains($request->department_id)) {
                abort(403, 'You do not have HR permissions for this department.');
            }
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email',            'emp_id' => 'nullable|string|unique:employees,emp_id',
            'phone' => 'nullable|string|max:255',
            'salary' => 'nullable|numeric|min:0',
            'designation_id' => 'nullable|exists:designations,id',
            'department_id' => 'nullable|exists:departments,id',
            'company_id' => 'required|exists:companies,id',
            'user_role' => ['required', 'in:' . implode(',', $allowedRoles)],
            'fingerprint_id' => 'nullable|string|unique:employees,fingerprint_id',
            'hire_date' => 'nullable|date',
            // Add other fields as needed
        ]);
        $validated['status'] = 'Active';
        $employee = Employee::create($validated);        // Sync to User table
        \App\Models\User::create([
            'id' => $employee->id,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => 'password', // Password will be automatically hashed by the model
            'role' => $validated['user_role'] === 'employee' ? 'user' : $validated['user_role'],
            'company_id' => $validated['company_id'],
            'active' => true,
        ]);
        return redirect()->route('employees.index')->with('success', 'Employee created successfully.');
    }    public function edit(Employee $employee)
    {
        $user = auth()->user();
        if ($user->isSuperAdmin()) {
            $departments = \App\Models\Department::all();
            $designations = \App\Models\Designation::all();
            $companies = \App\Models\Company::all();
        } else {
            $departments = \App\Models\Department::where('company_id', $user->company_id)->get();
            $designations = \App\Models\Designation::whereHas('department', function($q) use ($user) {
                $q->where('company_id', $user->company_id);
            })->get();            $companies = \App\Models\Company::where('id', $user->company_id)->get();
        }
        return view('employees.edit', compact('employee', 'departments', 'designations', 'companies'));
    }    public function update(Request $request, Employee $employee)
    {
        $user = auth()->user();
        
        // Set allowed roles and permissions based on user type
        if ($user->isSuperAdmin()) {
            $allowedRoles = ['employee', 'admin', 'super_admin'];
        } elseif ($user->isAdmin()) {
            $allowedRoles = ['employee', 'admin'];
            
            // Prevent admin from modifying employees from other companies
            if ($employee->company_id !== $user->company_id) {
                abort(403, 'You can only modify employees from your own company.');
            }
        } else {
            // HR permissions
            $hrDepartments = \App\Models\Department::where('hr_id', $user->employee->id)->pluck('id');
            
            // Check if HR has permission for the employee's current and new department
            if (!$hrDepartments->contains($employee->department_id) || 
                ($request->filled('department_id') && !$hrDepartments->contains($request->department_id))) {
                abort(403, 'You do not have HR permissions for this department.');
            }
            
            // HR can only edit regular employees
            $allowedRoles = ['employee'];
            if ($employee->user_role !== 'employee') {
                abort(403, 'You can only modify regular employees.');
            }
        }

        // Prevent non-superadmin users from modifying super_admin employees
        if (!$user->isSuperAdmin() && $employee->user_role === 'super_admin') {
            abort(403, 'You are not authorized to modify a super admin employee.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email,' . $employee->id,            'emp_id' => 'nullable|string|unique:employees,emp_id,' . $employee->id,
            'phone' => 'nullable|string|max:255',
            'salary' => 'nullable|numeric|min:0',
            'designation_id' => 'nullable|exists:designations,id',
            'department_id' => 'nullable|exists:departments,id',
            'company_id' => 'required|exists:companies,id',
            'user_role' => ['required', 'in:' . implode(',', $allowedRoles)],
            'fingerprint_id' => 'nullable|string|unique:employees,fingerprint_id,' . $employee->id,
            'hire_date' => 'nullable|date',
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
    }    public function destroy(Employee $employee)
    {
        // Delete associated user first (due to foreign key constraints)
        if ($user = \App\Models\User::find($employee->id)) {
            $user->delete();
        }
        $employee->delete();
        return redirect()->route('employees.index')->with('success', 'Employee deleted successfully.');
    }
}
