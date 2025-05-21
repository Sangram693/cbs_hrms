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
        } else {
            $attendances = Attendance::where('employee_id', $user->employee_id)->get();
        }
        return view('attendance.index', compact('attendances'));
    }

    // Show the form for creating a new resource.
    public function create()
    {
        $user = auth()->user();
        if ($user->isSuperAdmin() || $user->isAdmin()) {
            $employees = $user->isSuperAdmin()
                ? \App\Models\Employee::all()
                : \App\Models\Employee::where('company_id', $user->company_id)->get();
        } else {
            $employees = \App\Models\Employee::where('id', $user->employee_id)->get();
        }
        return view('attendance.create', compact('employees'));
    }

    // Store a newly created resource in storage.
    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'check_in' => 'nullable',
            'check_out' => 'nullable',
            'status' => 'required|string',
        ]);
        $user = auth()->user();
        if ($user->isSuperAdmin()) {
            $validated['company_id'] = $request->input('company_id');
        } else {
            // Get company_id from the selected employee (for admin/user)
            $employee = \App\Models\Employee::find($validated['employee_id']);
            $validated['company_id'] = $employee ? $employee->company_id : $user->company_id;
        }
        $validated['id'] = \Illuminate\Support\Str::uuid();
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
            'check_in' => 'nullable',
            'check_out' => 'nullable',
            'status' => 'required|string',
        ]);
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
