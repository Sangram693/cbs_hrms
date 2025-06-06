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
    }    public function create()
    {
        $user = auth()->user();
        $isSuperAdmin = $user->isSuperAdmin();

        if ($isSuperAdmin) {
            $companies = \App\Models\Company::all();
            // Get all active employees for superadmin
            $employees = \App\Models\Employee::where('status', 'Active')
                ->with('company')
                ->get();
        } else {
            $companies = \App\Models\Company::where('id', $user->company_id)->get();
            // Get only employees from the admin's company
            $employees = \App\Models\Employee::where('company_id', $user->company_id)
                ->where('status', 'Active')
                ->get();
        }

        return view('departments.create', compact('companies', 'employees', 'isSuperAdmin'));
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
        if ($request->has('hr_id')) {
            $rules['hr_id'] = 'nullable|exists:employees,id';
            // Validate that the HR belongs to the same company
            if ($isSuperAdmin && $request->filled('hr_id') && $request->filled('company_id')) {
                $employee = \App\Models\Employee::find($request->hr_id);
                if ($employee && $employee->company_id != $request->company_id) {
                    return back()->withErrors(['hr_id' => 'The selected HR must belong to the selected company.'])->withInput();
                }
            }
        }
        
        $validated = $request->validate($rules);
        if (!$isSuperAdmin) {
            $validated['company_id'] = $user->company_id;
        }
        
        \App\Models\Department::create($validated);
        return redirect()->route('departments.index')->with('success', 'Department created successfully.');
    }    public function edit(Department $department)
    {
        $user = auth()->user();
        $isSuperAdmin = $user->isSuperAdmin();
        $companies = $isSuperAdmin ? \App\Models\Company::all() : null;

        if ($department->exists) {
            if ($isSuperAdmin) {
                $employees = \App\Models\Employee::where('status', 'Active')
                    ->with('company')
                    ->get();
            } else {
                $employees = \App\Models\Employee::where('company_id', $user->company_id)
                    ->where('status', 'Active')
                    ->get();
            }
        } else {
            $employees = collect();
        }

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
