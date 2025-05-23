<?php

namespace App\Http\Controllers;

use App\Models\Leave;
use Illuminate\Http\Request;

class LeaveController extends Controller
{
    public function index(Request $request)
    {
        $query = Leave::with('employee');
        $user = $request->user();
        if ($user->isSuperAdmin()) {
            $leaves = $query->get();
        } elseif ($user->isAdmin()) {
            $leaves = $query->where('company_id', $user->company_id)->get();
        } elseif ($user->isUser() && $user->employee) {
            // HR: can manage all leaves in their department
            $hrDepartments = \App\Models\Department::where('hr_id', $user->employee->id)->pluck('id');
            if ($hrDepartments->count() > 0) {
                $leaves = $query->whereHas('employee', function($q) use ($hrDepartments) {
                    $q->whereIn('department_id', $hrDepartments);
                })->get();
            } else {
                // Normal user: only see self
                $leaves = $query->where('employee_id', $user->employee->id)->get();
            }
        } else {
            $leaves = collect();
        }
        return view('leaves.index', compact('leaves'));
    }

    public function create(Request $request)
    {
        $user = $request->user();
        if ($user->isSuperAdmin()) {
            $employees = \App\Models\Employee::all();
        } elseif ($user->isAdmin()) {
            $employees = \App\Models\Employee::where('company_id', $user->company_id)->get();
        } elseif ($user->isUser() && $user->employee) {
            $hrDepartments = \App\Models\Department::where('hr_id', $user->employee->id)->pluck('id');
            if ($hrDepartments->count() > 0) {
                $employees = \App\Models\Employee::whereIn('department_id', $hrDepartments)->get();
            } else {
                $employees = collect([\App\Models\Employee::find($user->employee->id)]);
            }
        } else {
            $employees = collect();
        }
        return view('leaves.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|integer|exists:employees,id',
            'leave_type' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'nullable|string',
            'status' => 'required|string',
            'approved_by' => 'nullable|integer|exists:employees,id',
        ]);
        Leave::create(array_merge(
            $request->only(['employee_id','leave_type','start_date','end_date','reason','status','approved_by']),
            ['company_id' => $request->user()->company_id]
        ));
        return redirect()->route('leaves.index')->with('success', 'Leave created successfully.');
    }

    public function show($id, Request $request)
    {
        $leave = Leave::where('company_id', $request->user()->company_id)->findOrFail($id);
        return response()->json($leave);
    }

    public function edit(Leave $leave, Request $request)
    {
        $user = $request->user();
        if ($user->isSuperAdmin()) {
            $employees = \App\Models\Employee::all();
        } elseif ($user->isAdmin()) {
            $employees = \App\Models\Employee::where('company_id', $user->company_id)->get();
        } elseif ($user->isUser() && $user->employee) {
            $hrDepartments = \App\Models\Department::where('hr_id', $user->employee->id)->pluck('id');
            if ($hrDepartments->count() > 0) {
                $employees = \App\Models\Employee::whereIn('department_id', $hrDepartments)->get();
            } else {
                $employees = collect([\App\Models\Employee::find($user->employee->id)]);
            }
        } else {
            $employees = collect();
        }
        return view('leaves.edit', compact('leave', 'employees'));
    }

    public function update(Request $request, Leave $leave)
    {
        $user = $request->user();
        if ($user->isSuperAdmin()) {
            $employees = \App\Models\Employee::all();
        } elseif ($user->isAdmin()) {
            $employees = \App\Models\Employee::where('company_id', $user->company_id)->get();
        } elseif ($user->isUser() && $user->employee) {
            $hrDepartments = \App\Models\Department::where('hr_id', $user->employee->id)->pluck('id');
            if ($hrDepartments->count() > 0) {
                $employees = \App\Models\Employee::whereIn('department_id', $hrDepartments)->get();
            } else {
                $employees = collect([\App\Models\Employee::find($user->employee->id)]);
            }
        } else {
            $employees = collect();
        }
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'company_id' => 'required|exists:companies,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'type' => 'required|string|max:255',
            'status' => 'required|string|max:255',
        ]);
        $leave->update($validated);
        return view('leaves.edit', compact('leave', 'employees'))->with('success', 'Leave updated successfully.');
    }

    public function destroy($id, Request $request)
    {
        $leave = Leave::where('company_id', $request->user()->company_id)->findOrFail($id);
        $leave->delete();
        return redirect()->route('leaves.index')->with('success', 'Leave deleted successfully.');
    }
}
