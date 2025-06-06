<?php

namespace App\Http\Controllers;

use App\Models\Salary;
use Illuminate\Http\Request;

class SalaryController extends Controller
{
    // Display a listing of the resource.
    public function index()
    {
        $user = auth()->user();
        if ($user->isSuperAdmin()) {
            $salaries = Salary::all();
        } elseif ($user->isAdmin()) {
            $salaries = Salary::where('company_id', $user->company_id)->get();
        } elseif ($user->isUser() && $user->employee) {
            $hrDepartments = \App\Models\Department::where('hr_id', $user->employee->id)->pluck('id');
            if ($hrDepartments->count() > 0) {
                $salaries = Salary::whereHas('employee', function($q) use ($hrDepartments) {
                    $q->whereIn('department_id', $hrDepartments);
                })->get();
            } else {
                $salaries = Salary::where('employee_id', $user->employee->id)->get();
            }
        } else {
            $salaries = collect();
        }
        return view('salaries.index', compact('salaries'));
    }

    // Show the form for creating a new resource.
    public function create()
    {
        $user = auth()->user();
        $companies = null;
        $companyId = null;
        $employees = collect();
        
        if ($user->isSuperAdmin()) {
            $companies = \App\Models\Company::all();
            $companyId = request()->input('company_id');
            if ($companyId) {
                $employees = \App\Models\Employee::where('company_id', $companyId)->get();
            }
        } elseif ($user->isAdmin()) {
            $employees = \App\Models\Employee::where('company_id', $user->company_id)->get();
        } elseif ($user->isUser() && $user->employee) {
            $hrDepartments = \App\Models\Department::where('hr_id', $user->employee->id)->pluck('id');
            if ($hrDepartments->count() > 0) {
                $employees = \App\Models\Employee::whereIn('department_id', $hrDepartments)->get();
            } else {
                $employees = collect([\App\Models\Employee::find($user->employee->id)]);
            }
        }
        
        return view('salaries.create', compact('employees', 'companies', 'companyId'));
    }    // Store a newly created resource in storage.
    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'salary_month' => 'required',
            'base_salary' => 'required|numeric',
            'bonus' => 'nullable|numeric',
            'deductions' => 'nullable|numeric',
            'paid_on' => 'nullable|date',
        ], [
            'employee_id.required' => 'Please select an employee',
            'employee_id.exists' => 'Selected employee does not exist',
            'salary_month.required' => 'Please select a salary month',
            'base_salary.required' => 'Base salary is required',
            'base_salary.numeric' => 'Base salary must be a valid number',
            'bonus.numeric' => 'Bonus must be a valid number',
            'deductions.numeric' => 'Deductions must be a valid number',
            'paid_on.date' => 'Please enter a valid payment date'
        ]);
        // Convert salary_month (YYYY-MM) to YYYY-MM-01 for PostgreSQL date
        if (isset($validated['salary_month']) && strlen($validated['salary_month']) === 7) {
            $validated['salary_month'] .= '-01';
        }
        $validated['bonus'] = $validated['bonus'] ?? 0;
        $validated['deductions'] = $validated['deductions'] ?? 0;
        $validated['net_salary'] = ($validated['base_salary'] ?? 0) + ($validated['bonus'] ?? 0) - ($validated['deductions'] ?? 0);
        $validated['company_id'] = auth()->user()->isSuperAdmin()
            ? ($request->company_id ?? null)
            : auth()->user()->company_id;
        Salary::create($validated);
        return redirect()->route('salaries.index')->with('success', 'Salary created successfully.');
    }

    // Show the form for editing the specified resource.
    public function edit(Salary $salary)
    {
        $user = auth()->user();
        $companies = null;
        $companyId = null;
        $employees = collect();

        if ($user->isSuperAdmin()) {
            $companies = \App\Models\Company::all();
            $companyId = request()->input('company_id', $salary->company_id);
            $employees = \App\Models\Employee::where('company_id', $companyId)->get();
        } elseif ($user->isAdmin()) {
            $employees = \App\Models\Employee::where('company_id', $user->company_id)->get();
        } elseif ($user->isUser() && $user->employee) {
            $hrDepartments = \App\Models\Department::where('hr_id', $user->employee->id)->pluck('id');
            if ($hrDepartments->count() > 0) {
                $employees = \App\Models\Employee::whereIn('department_id', $hrDepartments)->get();
            } else {
                $employees = collect([\App\Models\Employee::find($user->employee->id)]);
            }
        }

        return view('salaries.edit', compact('salary', 'employees', 'companies', 'companyId'));
    }

    // Update the specified resource in storage.    
    public function update(Request $request, Salary $salary)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'salary_month' => 'required',
            'base_salary' => 'required|numeric',
            'bonus' => 'nullable|numeric',
            'deductions' => 'nullable|numeric',
            'paid_on' => 'nullable|date',
        ], [
            'employee_id.required' => 'Please select an employee',
            'employee_id.exists' => 'Selected employee does not exist',
            'salary_month.required' => 'Please select a salary month',
            'base_salary.required' => 'Base salary is required',
            'base_salary.numeric' => 'Base salary must be a valid number',
            'bonus.numeric' => 'Bonus must be a valid number',
            'deductions.numeric' => 'Deductions must be a valid number',
            'paid_on.date' => 'Please enter a valid payment date'
        ]);
        if (isset($validated['salary_month']) && strlen($validated['salary_month']) === 7) {
            $validated['salary_month'] .= '-01';
        }
        $validated['deductions'] = $validated['deductions'] ?? 0;
        $validated['net_salary'] = ($validated['base_salary'] ?? 0) + ($validated['bonus'] ?? 0) - ($validated['deductions'] ?? 0);
        $validated['company_id'] = $salary->company_id; // keep company_id unchanged
        $salary->update($validated);
        return redirect()->route('salaries.index')->with('success', 'Salary updated successfully.');
    }

    // Remove the specified resource from storage.
    public function destroy(Salary $salary)
    {
        $salary->delete();
        return redirect()->route('salaries.index')->with('success', 'Salary deleted successfully.');
    }
}
