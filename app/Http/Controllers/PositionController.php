<?php

namespace App\Http\Controllers;

use App\Models\Position;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if ($user->isSuperAdmin()) {
            $positions = Position::all();
        } elseif ($user->isAdmin()) {
            $positions = Position::where('company_id', $user->company_id)->get();
        } elseif ($user->isUser() && $user->employee && \App\Models\Department::where('hr_id', $user->employee->id)->exists()) {
            // HR: can see/manage positions in their department(s)
            $departmentIds = \App\Models\Department::where('hr_id', $user->employee->id)->pluck('id');
            $positions = Position::whereIn('department_id', $departmentIds)->get();
        } else {
            $positions = collect();
        }
        return view('positions.index', compact('positions'));
    }

    public function create()
    {
        $user = auth()->user();
        $isSuperAdmin = $user->isSuperAdmin();
        $isHr = $user->isHr();
        $companies = $isSuperAdmin ? \App\Models\Company::all() : null;
        if ($isSuperAdmin) {
            $departments = \App\Models\Department::all();
        } elseif ($user->isAdmin()) {
            $departments = \App\Models\Department::where('company_id', $user->company_id)->get();
        } elseif ($isHr) {
            $departmentIds = \App\Models\Department::where('hr_id', $user->employee->id)->pluck('id');
            $departments = \App\Models\Department::whereIn('id', $departmentIds)->get();
        } else {
            $departments = collect();
        }
        // HRs cannot add positions if not assigned to any department
        if ($isHr && $departments->isEmpty()) {
            abort(403, 'HRs are not assigned to any department.');
        }
        return view('positions.create', compact('departments', 'companies', 'isSuperAdmin'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $isSuperAdmin = $user->isSuperAdmin();
        $isHr = $user->isHr();
        $rules = [
            'title' => 'required|string|max:255',
            'level' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
        ];
        if ($isSuperAdmin) {
            $rules['company_id'] = 'required|exists:companies,id';
        }
        $validated = $request->validate($rules);
        if ($isSuperAdmin) {
            // ...existing code...
        } elseif ($user->isAdmin()) {
            $validated['company_id'] = $user->company_id;
        } elseif ($isHr) {
            $departmentIds = \App\Models\Department::where('hr_id', $user->employee->id)->pluck('id');
            if (!$departmentIds->contains($validated['department_id'])) {
                abort(403, 'HRs can only add positions to their own department(s).');
            }
            $validated['company_id'] = $user->company_id;
        }
        \App\Models\Position::create($validated);
        return redirect()->route('positions.index')->with('success', 'Position created successfully.');
    }

    public function edit(Position $position)
    {
        $user = auth()->user();
        $isSuperAdmin = $user->isSuperAdmin();
        $isHr = $user->isHr();
        $companies = $isSuperAdmin ? \App\Models\Company::all() : null;
        if ($isSuperAdmin) {
            $departments = \App\Models\Department::all();
        } elseif ($user->isAdmin()) {
            $departments = \App\Models\Department::where('company_id', $user->company_id)->get();
        } elseif ($isHr) {
            $departmentIds = \App\Models\Department::where('hr_id', $user->employee->id)->pluck('id');
            if (!$departmentIds->contains($position->department_id)) {
                abort(403, 'HRs can only edit positions in their own department(s).');
            }
            $departments = \App\Models\Department::whereIn('id', $departmentIds)->get();
        } else {
            $departments = collect();
        }
        return view('positions.edit', compact('position', 'departments', 'companies', 'isSuperAdmin'));
    }

    public function update(Request $request, Position $position)
    {
        $user = auth()->user();
        $isSuperAdmin = $user->isSuperAdmin();
        $isHr = $user->isHr();
        $rules = [
            'title' => 'required|string|max:255',
            'level' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
        ];
        if ($isSuperAdmin) {
            $rules['company_id'] = 'required|exists:companies,id';
        }
        $validated = $request->validate($rules);
        if ($isSuperAdmin) {
            // ...existing code...
        } elseif ($user->isAdmin()) {
            $validated['company_id'] = $user->company_id;
        } elseif ($isHr) {
            $departmentIds = \App\Models\Department::where('hr_id', $user->employee->id)->pluck('id');
            if (!$departmentIds->contains($position->department_id) || !$departmentIds->contains($validated['department_id'])) {
                abort(403, 'HRs can only update positions in their own department(s).');
            }
            $validated['company_id'] = $user->company_id;
        }
        $position->update($validated);
        return redirect()->route('positions.index')->with('success', 'Position updated successfully.');
    }

    public function destroy(Position $position)
    {
        $user = auth()->user();
        $isSuperAdmin = $user->isSuperAdmin();
        $isHr = $user->isHr();
        if ($isSuperAdmin) {
            $position->delete();
        } elseif ($user->isAdmin()) {
            $position->delete();
        } elseif ($isHr) {
            $departmentIds = \App\Models\Department::where('hr_id', $user->employee->id)->pluck('id');
            if (!$departmentIds->contains($position->department_id)) {
                abort(403, 'HRs can only delete positions in their own department(s).');
            }
            $position->delete();
        }
        return redirect()->route('positions.index')->with('success', 'Position deleted successfully.');
    }
}
