<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    // Display a listing of the resource.
    public function index()
    {
        $user = auth()->user();
        if ($user->isSuperAdmin()) {
            $attendances = Attendance::all();
        } elseif ($user->isAdmin()) {
            $attendances = Attendance::whereHas('employee', function ($q) use ($user) {
                $q->where('company_id', $user->company_id);
            })->get();
        } elseif ($user->isUser() && $user->employee) {
            // HR: can manage all attendance in their department
            $hrDepartments = \App\Models\Department::where('hr_id', $user->employee->id)->pluck('id');
            if ($hrDepartments->count() > 0) {
                $attendances = Attendance::whereHas('employee', function($q) use ($hrDepartments) {
                    $q->whereIn('department_id', $hrDepartments);
                })->get();
            } else {
                // Normal user: only see self
                $attendances = Attendance::where('employee_id', $user->employee->id)->get();
            }
        } else {
            $attendances = collect();
        }
        return view('attendance.index', compact('attendances'));
    }

    // Show the form for creating a new resource.
    public function create()
    {
        $user = auth()->user();
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
        return view('attendance.create', compact('employees'));
    }

    // Store a newly created resource in storage.
    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'check_in' => 'nullable|date_format:H:i',
            'check_out' => [
                'nullable',
                'date_format:H:i',
                function ($attribute, $value, $fail) use ($request) {
                    if ($value && $request->check_in) {
                        $checkIn = \Carbon\Carbon::createFromFormat('H:i', $request->check_in);
                        $checkOut = \Carbon\Carbon::createFromFormat('H:i', $value);
                        if ($checkOut->lte($checkIn)) {
                            $fail('Check-out time must be after check-in time.');
                        }
                    }
                }
            ],
            'status' => 'required|string|in:Present,Absent,Leave',
        ]);

        // Additional validation: if status is Present, both check_in and check_out are required
        if ($validated['status'] === 'Present' && (!$validated['check_in'] || !$validated['check_out'])) {
            return redirect()->back()
                ->withErrors(['check_in' => 'Both check-in and check-out times are required when status is Present'])
                ->withInput();
        }

        $user = auth()->user();
        if ($user->isSuperAdmin()) {
            $validated['company_id'] = $request->input('company_id');
        } else {
            // Get company_id from the selected employee (for admin/user)
            $employee = \App\Models\Employee::find($validated['employee_id']);
            $validated['company_id'] = $employee ? $employee->company_id : $user->company_id;
        }
        
        Attendance::create($validated);
        return redirect()->route('attendance.index')->with('success', 'Attendance created successfully.');
    }

    // Show the form for editing the specified resource.
    public function edit(Attendance $attendance)
    {
        $user = auth()->user();
        if ($user->isSuperAdmin() || $user->isAdmin()) {
            $employees = $user->isSuperAdmin()
                ? \App\Models\Employee::all()
                : \App\Models\Employee::where('company_id', $user->company_id)->get();
        } elseif ($user->isUser() && $user->employee) {
            $hrDepartments = \App\Models\Department::where('hr_id', $user->employee->id)->pluck('id');
            if ($hrDepartments->count() > 0) {
                $employees = \App\Models\Employee::whereIn('department_id', $hrDepartments)->get();
            } else {
                $employees = collect([\App\Models\Employee::find($user->employee->id)]);
            }
        } else {
            $employees = \App\Models\Employee::where('id', $user->employee_id)->get();
        }
        return view('attendance.edit', compact('attendance', 'employees'));
    }

    // Update the specified resource in storage.
    public function update(Request $request, Attendance $attendance)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'check_in' => 'nullable|date_format:H:i',
            'check_out' => [
                'nullable',
                'date_format:H:i',
                function ($attribute, $value, $fail) use ($request) {
                    if ($value && $request->check_in) {
                        $checkIn = \Carbon\Carbon::createFromFormat('H:i', $request->check_in);
                        $checkOut = \Carbon\Carbon::createFromFormat('H:i', $value);
                        if ($checkOut->lte($checkIn)) {
                            $fail('Check-out time must be after check-in time.');
                        }
                    }
                }
            ],
            'status' => 'required|string|in:Present,Absent,Leave',
        ]);

        // Additional validation: if status is Present, both check_in and check_out are required
        if ($validated['status'] === 'Present' && (!$validated['check_in'] || !$validated['check_out'])) {
            return redirect()->back()
                ->withErrors(['check_in' => 'Both check-in and check-out times are required when status is Present'])
                ->withInput();
        }

        $attendance->update($validated);
        return redirect()->route('attendance.index')->with('success', 'Attendance updated successfully.');
    }

    // Remove the specified resource from storage.
    public function destroy(Attendance $attendance)
    {
        $attendance->delete();
        return redirect()->route('attendance.index')->with('success', 'Attendance deleted successfully.');
    }
}
