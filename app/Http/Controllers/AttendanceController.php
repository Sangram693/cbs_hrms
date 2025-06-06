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
            $employees = \App\Models\Employee::with('company')->get();
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
        return view('attendance.create', compact('employees'));    }

    // Store a newly created resource in storage.
    public function store(Request $request)
    {
        $status = $request->input('status');
        
        // Base validation rules
        $rules = [
            'company_id' => 'required|exists:companies,id',
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'status' => 'required|string|in:Present,Absent,Leave',
        ];        // Validation messages
        $messages = [
            'company_id.required' => 'Please select a company.',
            'company_id.exists' => 'The selected company does not exist in our records.',
            'employee_id.required' => 'Please select an employee.',
            'employee_id.exists' => 'The selected employee does not exist in our records.',
            'date.required' => 'Please select the attendance date.',
            'date.date' => 'The date format is invalid. Please use a valid date format.',
            'status.required' => 'Please select the attendance status.',
            'status.in' => 'Invalid status selected. Status must be Present, Absent, or Leave.',
            'check_in.required' => 'For present status, check-in time is mandatory.',
            'check_in.date_format' => 'Check-in time must be in 24-hour format (HH:MM).',
            'check_out.required' => 'For present status, check-out time is mandatory.',
            'check_out.date_format' => 'Check-out time must be in 24-hour format (HH:MM).',
        ];

        // Add conditional validation based on status
        if ($status === 'Present') {            $rules['check_in'] = [
                'required',
                'date_format:H:i:s',
                function ($attribute, $value, $fail) {
                    try {
                        if ($value) {
                            \Carbon\Carbon::createFromFormat('H:i:s', $value);
                        }
                    } catch (\Exception $e) {
                        \Carbon\Carbon::createFromFormat('H:i', $value);
                    }
                }
            ];
            $rules['check_out'] = [
                'required',
                'date_format:H:i:s',
                function ($attribute, $value, $fail) use ($request) {
                    if ($value && $request->check_in) {
                        try {
                            // Try with seconds first
                            $checkIn = \Carbon\Carbon::createFromFormat('H:i:s', $request->check_in);
                            $checkOut = \Carbon\Carbon::createFromFormat('H:i:s', $value);
                        } catch (\Exception $e) {
                            // Fallback to hours and minutes only
                            $checkIn = \Carbon\Carbon::createFromFormat('H:i', $request->check_in);
                            $checkOut = \Carbon\Carbon::createFromFormat('H:i', $value);
                        }
                        
                        if ($checkOut->lte($checkIn)) {
                            $fail('Check-out time must be after check-in time.');
                        }
                    }
                }
            ];
        } else {
            $rules['check_in'] = 'nullable|date_format:H:i';
            $rules['check_out'] = 'nullable|date_format:H:i';
        }        $validated = $request->validate($rules, $messages);

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
    }    // Show the form for editing the specified resource.
    public function edit(Attendance $attendance)
    {
        $user = auth()->user();
        if ($user->isSuperAdmin()) {
            $employees = \App\Models\Employee::with('company')->get();
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
            $employees = \App\Models\Employee::where('id', $user->employee_id)->get();
        }
        
        return view('attendance.edit', compact('attendance', 'employees'));
    }    // Update the specified resource in storage.
    public function update(Request $request, Attendance $attendance)
    {
        $status = $request->input('status');
        
        $rules = [
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'status' => 'required|string|in:Present,Absent,Leave',
        ];        if ($status === 'Present') {
            $rules['check_in'] = [
                'required',
                'date_format:H:i:s',
                function ($attribute, $value, $fail) {
                    try {
                        \Carbon\Carbon::createFromFormat('H:i:s', $value);
                    } catch (\Exception $e) {
                        // If the time is in H:i format, append :00 for seconds
                        if (preg_match('/^([0-1][0-9]|2[0-3]):[0-5][0-9]$/', $value)) {
                            $value .= ':00';
                        } else {
                            $fail('Please enter check-in time in 24-hour format (HH:MM or HH:MM:SS).');
                        }
                    }
                }
            ];
            $rules['check_out'] = [
                'required',
                'date_format:H:i:s',
                function ($attribute, $value, $fail) use ($request) {
                    try {
                        $checkIn = $request->check_in;
                        $checkOut = $value;
                        
                        // If times are in H:i format, append :00 for seconds
                        if (strlen($checkIn) === 5) $checkIn .= ':00';
                        if (strlen($value) === 5) $checkOut .= ':00';
                        
                        $checkInTime = \Carbon\Carbon::createFromFormat('H:i:s', $checkIn);
                        $checkOutTime = \Carbon\Carbon::createFromFormat('H:i:s', $checkOut);
                        
                        if ($checkOutTime->lte($checkInTime)) {
                            $fail('Check-out time must be after check-in time.');
                        }
                    } catch (\Exception $e) {
                        $fail('Please enter check-out time in 24-hour format (HH:MM or HH:MM:SS).');
                    }
                }
            ];
        } else {
            $rules['check_in'] = 'nullable|date_format:H:i';
            $rules['check_out'] = 'nullable|date_format:H:i';
        }

        $messages = [
            'employee_id.required' => 'Please select an employee.',
            'employee_id.exists' => 'The selected employee does not exist in our records.',
            'date.required' => 'Please select the attendance date.',
            'date.date' => 'The date format is invalid. Please use a valid date format.',
            'check_in.required' => 'For present status, check-in time is mandatory.',
            'check_in.date_format' => 'Check-in time must be in 24-hour format (HH:MM).',
            'check_out.required' => 'For present status, check-out time is mandatory.',
            'check_out.date_format' => 'Check-out time must be in 24-hour format (HH:MM).',
            'status.required' => 'Please select the attendance status.',
            'status.in' => 'Invalid status selected. Status must be Present, Absent, or Leave.',
        ];

        $validated = $request->validate($rules, $messages);

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
