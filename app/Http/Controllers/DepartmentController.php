<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if ($user->isSuperAdmin()) {
            $departments = Department::all();
        } else {
            $departments = Department::where('company_id', $user->company_id)->get();
        }
        return view('departments.index', compact('departments'));
    }

    public function create()
    {
        $user = auth()->user();
        $isSuperAdmin = $user->isSuperAdmin();
        $companies = $isSuperAdmin ? \App\Models\Company::all() : null;
        // Provide employees for HR selection
        $employees = collect(); // No employees to select when creating a department
        return view('departments.create', compact('companies', 'isSuperAdmin', 'employees'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $isSuperAdmin = $user->isSuperAdmin();
        $rules = [
            'name' => 'required|string|max:255',
        ];
        if ($isSuperAdmin) {
            $rules['company_id'] = 'required|exists:companies,id';
        }
        $rules['hr_id'] = 'nullable|exists:employees,id';
        $validated = $request->validate($rules);
        if (!$isSuperAdmin) {
            $validated['company_id'] = $user->company_id;
        }
        $validated['hr_id'] = $request->input('hr_id');
        \App\Models\Department::create($validated);
        return redirect()->route('departments.index')->with('success', 'Department created successfully.');
    }

    public function edit(Department $department)
    {
        $user = auth()->user();
        $isSuperAdmin = $user->isSuperAdmin();
        $companies = $isSuperAdmin ? \App\Models\Company::all() : null;
        // Provide employees for HR selection only on edit, not on create
        $employees = ($department->exists)
            ? ($isSuperAdmin
                ? \App\Models\Employee::all()
                : \App\Models\Employee::where('company_id', $user->company_id)->get())
            : collect();
        return view('departments.edit', compact('department', 'companies', 'isSuperAdmin', 'employees'));
    }

    public function update(Request $request, Department $department)
    {
        $user = auth()->user();
        $isSuperAdmin = $user->isSuperAdmin();
        $rules = [
            'name' => 'required|string|max:255',
        ];
        if ($isSuperAdmin) {
            $rules['company_id'] = 'required|exists:companies,id';
        }
        $rules['hr_id'] = 'nullable|exists:employees,id';
        $validated = $request->validate($rules);
        if (!$isSuperAdmin) {
            $validated['company_id'] = $user->company_id;
        }
        $validated['hr_id'] = $request->input('hr_id', $department->hr_id);
        $department->update($validated);
        return redirect()->route('departments.index')->with('success', 'Department updated successfully.');
    }

    public function destroy(Department $department)
    {
        $department->delete();
        return redirect()->route('departments.index')->with('success', 'Department deleted successfully.');
    }
}
