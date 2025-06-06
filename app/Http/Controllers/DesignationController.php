<?php

namespace App\Http\Controllers;

use App\Models\Designation;
use App\Models\Department;
use Illuminate\Http\Request;

class DesignationController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $query = Designation::with(['department', 'company']);

        if (!$user->isSuperAdmin()) {
            $query->where('company_id', $user->company_id);
        }

        $designations = $query->get();
        return view('designations.index', compact('designations'));
    }    public function create()
    {
        $user = auth()->user();
        $isSuperAdmin = $user->isSuperAdmin();

        if ($isSuperAdmin) {
            $companies = \App\Models\Company::all();
            $departments = Department::with('company')->get();
        } else {
            $companies = \App\Models\Company::where('id', $user->company_id)->get();
            $departments = Department::where('company_id', $user->company_id)->get();
        }

        return view('designations.create', compact('departments', 'isSuperAdmin', 'companies'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $isSuperAdmin = $user->isSuperAdmin();

        $rules = [
            'title' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id'
        ];

        if ($isSuperAdmin) {
            $rules['company_id'] = 'required|exists:companies,id';
        }

        $validated = $request->validate($rules);

        // For superadmin, verify that the department belongs to the selected company
        if ($isSuperAdmin) {
            $department = Department::findOrFail($request->department_id);
            if ($department->company_id != $request->company_id) {
                return back()->withErrors(['department_id' => 'The selected department must belong to the selected company.'])->withInput();
            }
            $validated['company_id'] = $request->company_id;
        } else {
            $department = Department::findOrFail($request->department_id);
            $validated['company_id'] = $user->company_id;
        }

        Designation::create($validated);

        return redirect()->route('designations.index')->with('success', 'Designation created successfully.');
    }    public function edit(Designation $designation)
    {
        $user = auth()->user();
        $isSuperAdmin = $user->isSuperAdmin();

        if ($isSuperAdmin) {
            $companies = \App\Models\Company::all();
            $departments = Department::with('company')->get();
        } else {
            $companies = \App\Models\Company::where('id', $user->company_id)->get();
            $departments = Department::where('company_id', $user->company_id)->get();
        }

        return view('designations.edit', compact('designation', 'departments', 'isSuperAdmin', 'companies'));
    }

    public function update(Request $request, Designation $designation)
    {
        $user = auth()->user();
        $isSuperAdmin = $user->isSuperAdmin();

        $rules = [
            'title' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id'
        ];

        if ($isSuperAdmin) {
            $rules['company_id'] = 'required|exists:companies,id';
        }

        $validated = $request->validate($rules);

        // For superadmin, verify that the department belongs to the selected company
        if ($isSuperAdmin) {
            $department = Department::findOrFail($request->department_id);
            if ($department->company_id != $request->company_id) {
                return back()->withErrors(['department_id' => 'The selected department must belong to the selected company.'])->withInput();
            }
            $validated['company_id'] = $request->company_id;
        } else {
            $department = Department::findOrFail($request->department_id);
            if ($department->company_id != $user->company_id) {
                return back()->withErrors(['department_id' => 'The selected department must belong to your company.'])->withInput();
            }
            $validated['company_id'] = $user->company_id;
        }

        $designation->update($validated);
        return redirect()->route('designations.index')->with('success', 'Designation updated successfully.');
    }

    public function destroy(Designation $designation)
    {
        $designation->delete();
        return redirect()->route('designations.index')->with('success', 'Designation deleted successfully.');
    }
}
