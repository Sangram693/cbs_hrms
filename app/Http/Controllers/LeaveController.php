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
    }      public function create(Request $request)
    {
        $user = $request->user();
        $companyId = null;
        $companies = null;
        $employees = collect();
        $leaveTypes = collect();
        
        if ($user->isSuperAdmin()) {
            $companies = \App\Models\Company::all();
            $companyId = $request->input('company_id');
            if ($companyId) {
                $employees = \App\Models\Employee::where('company_id', $companyId)->get();
                $leaveTypes = \App\Models\LeaveType::where('company_id', $companyId)->get();
            }
        } else {
            $companyId = $user->employee ? $user->employee->company_id : null;
            if ($companyId) {
                $employees = $user->isAdmin() || $user->isHr()
                    ? \App\Models\Employee::where('company_id', $companyId)->get()
                    : collect([$user->employee]);
                $leaveTypes = \App\Models\LeaveType::where('company_id', $companyId)->get();
            }
        }
        
        return view('leaves.create', compact('employees', 'leaveTypes', 'companies', 'companyId'));
    }

    public function store(Request $request)
    {        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'leave_type' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string',
            'approved_by' => 'nullable|integer|exists:employees,id',
        ]);
        
        // Get employee and their company ID
        $employee = \App\Models\Employee::findOrFail($validated['employee_id']);
        $validated['company_id'] = $employee->company_id;
        $validated['status'] = 'Pending';
        Leave::create($validated);
        return redirect()->route('leaves.index')->with('success', 'Leave created successfully.');
    }

    public function show($id, Request $request)
    {
        $user = $request->user();
        $query = Leave::query();
        
        // Super admin can view all leaves
        if (!$user->isSuperAdmin()) {
            $query->where('company_id', $user->company_id);
        }
        
        $leave = $query->with('employee')->findOrFail($id);
        return response()->json($leave);
    }    public function edit(Leave $leave, Request $request)
    {
        $user = $request->user();
        
        if ($user->isSuperAdmin()) {
            $companies = \App\Models\Company::all();
            $companyId = $request->input('company_id', $leave->company_id);
            $employees = \App\Models\Employee::where('company_id', $companyId)->get();
        } else {
            $companies = null;
            $companyId = $user->employee ? $user->employee->company_id : null;
            $employees = $user->isAdmin() || $user->isHr()
                ? \App\Models\Employee::where('company_id', $companyId)->get()
                : collect([$user->employee]);
        }
        
        // Get leave types based on company ID
        $leaveTypes = \App\Models\LeaveType::where('company_id', $companyId)->get();
        
        return view('leaves.edit', compact('leave', 'employees', 'leaveTypes', 'companies', 'companyId'));
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
            'leave_type' => 'required|string|max:255',
            'status' => 'required|string|max:255',
            'reason' => 'required|string',
        ]);
        $leave->update($validated);
        return redirect()->route('leaves.index')->with('success', 'Leave updated successfully.');
    }

    public function destroy($id, Request $request)
    {
        $leave = Leave::where('company_id', $request->user()->company_id)->findOrFail($id);
        $leave->delete();
        return redirect()->route('leaves.index')->with('success', 'Leave deleted successfully.');
    }

    // Add this method to allow admin and HR to change leave status
    public function changeStatus(Request $request, Leave $leave)
    {
        $user = $request->user();
        if (!($user->isSuperAdmin() || $user->isAdmin() || $user->isHR())) {
            abort(403, 'Unauthorized');
        }
        $request->validate([
            'status' => 'required|in:Pending,Approved,Rejected',
        ]);
        $leave->status = $request->status;
        $leave->save();
        return redirect()->route('leaves.index')->with('success', 'Leave status updated successfully.');
    }
}
