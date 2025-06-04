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
    }

    public function create()
    {
        $user = auth()->user();
        $departments = $user->isSuperAdmin() 
            ? Department::all() 
            : Department::where('company_id', $user->company_id)->get();
        return view('designations.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id'
        ]);

        $department = Department::findOrFail($request->department_id);
        $validated['company_id'] = $department->company_id;

        Designation::create($validated);

        return redirect()->route('designations.index')->with('success', 'Designation created successfully.');
    }

    public function edit(Designation $designation)
    {
        $user = auth()->user();
        $departments = $user->isSuperAdmin() 
            ? Department::all() 
            : Department::where('company_id', $user->company_id)->get();
        return view('designations.edit', compact('designation', 'departments'));
    }

    public function update(Request $request, Designation $designation)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id'
        ]);

        $department = Department::findOrFail($request->department_id);
        $validated['company_id'] = $department->company_id;

        $designation->update($validated);

        return redirect()->route('designations.index')->with('success', 'Designation updated successfully.');
    }

    public function destroy(Designation $designation)
    {
        $designation->delete();
        return redirect()->route('designations.index')->with('success', 'Designation deleted successfully.');
    }
}
