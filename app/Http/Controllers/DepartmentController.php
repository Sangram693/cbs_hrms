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
        return view('departments.create', compact('companies', 'isSuperAdmin'));
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
        $validated = $request->validate($rules);
        $validated['id'] = \Illuminate\Support\Str::uuid();
        if (!$isSuperAdmin) {
            $validated['company_id'] = $user->company_id;
        }
        \App\Models\Department::create($validated);
        return redirect()->route('departments.index')->with('success', 'Department created successfully.');
    }

    public function edit(Department $department)
    {
        $user = auth()->user();
        $isSuperAdmin = $user->isSuperAdmin();
        $companies = $isSuperAdmin ? \App\Models\Company::all() : null;
        return view('departments.edit', compact('department', 'companies', 'isSuperAdmin'));
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
        $validated = $request->validate($rules);
        if (!$isSuperAdmin) {
            $validated['company_id'] = $user->company_id;
        }
        $department->update($validated);
        return redirect()->route('departments.index')->with('success', 'Department updated successfully.');
    }

    public function destroy(Department $department)
    {
        $department->delete();
        return redirect()->route('departments.index')->with('success', 'Department deleted successfully.');
    }
}
