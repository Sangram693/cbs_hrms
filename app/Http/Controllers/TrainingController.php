<?php

namespace App\Http\Controllers;

use App\Models\Training;
use Illuminate\Http\Request;

class TrainingController extends Controller
{
    // Display a listing of the resource.
    public function index()
    {
        $user = auth()->user();
        if ($user->isSuperAdmin()) {
            $trainings = Training::all();
        } elseif ($user->isAdmin()) {
            $trainings = Training::where('company_id', $user->company_id)->get();
        } elseif ($user->isUser() && $user->employee) {
            $hrDepartments = \App\Models\Department::where('hr_id', $user->employee->id)->pluck('id');
            if ($hrDepartments->count() > 0) {
                $trainings = Training::whereHas('employee', function($q) use ($hrDepartments) {
                    $q->whereIn('department_id', $hrDepartments);
                })->get();
            } else {
                $trainings = Training::where('employee_id', $user->employee->id)->get();
            }
        } else {
            $trainings = collect();
        }
        return view('trainings.index', compact('trainings'));
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
        return view('trainings.create', compact('employees'));
    }    // Store a newly created resource in storage.
    public function store(Request $request)
    {        $status = $request->input('status');
        $rules = [
            'training_name' => 'required|string|max:255',
            'employee_id' => 'required|exists:employees,id',
            'status' => 'required|in:Not Started,Ongoing,Completed',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ];

        // Add conditional validation based on status
        if ($status === 'Ongoing') {
            $rules['start_date'] = 'required|date';
        } elseif ($status === 'Completed') {
            $rules['start_date'] = 'required|date';
            $rules['end_date'] = 'required|date|after_or_equal:start_date';
        }

        $validated = $request->validate($rules);

        $user = auth()->user();
        if ($user->isSuperAdmin()) {
            $employee = \App\Models\Employee::findOrFail($validated['employee_id']);
            $validated['company_id'] = $employee->company_id;
        } else {
            $validated['company_id'] = $user->employee->company_id;
        }

        Training::create($validated);
        return redirect()->route('trainings.index')->with('success', 'Training created successfully.');
    }

    // Show the form for editing the specified resource.
    public function edit(Training $training)
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
        return view('trainings.edit', compact('training', 'employees'));
    }    // Update the specified resource in storage.
    public function update(Request $request, Training $training)
    {        $status = $request->input('status');
        $rules = [
            'training_name' => 'required|string|max:255',
            'employee_id' => 'required|exists:employees,id',
            'status' => 'required|in:Not Started,Ongoing,Completed',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ];

        // Add conditional validation based on status
        if ($status === 'Ongoing') {
            $rules['start_date'] = 'required|date';
        } elseif ($status === 'Completed') {
            $rules['start_date'] = 'required|date';
            $rules['end_date'] = 'required|date|after_or_equal:start_date';
        }

        $validated = $request->validate($rules);

        $user = auth()->user();
        if ($user->isSuperAdmin()) {
            $employee = \App\Models\Employee::findOrFail($validated['employee_id']);
            $validated['company_id'] = $employee->company_id;
        } else {
            $validated['company_id'] = $user->employee->company_id;
        }

        $training->update($validated);
        return redirect()->route('trainings.index')->with('success', 'Training updated successfully.');
    }

    // Remove the specified resource from storage.
    public function destroy(Training $training)
    {
        $training->delete();
        return redirect()->route('trainings.index')->with('success', 'Training deleted successfully.');
    }
}
