<?php

namespace App\Http\Controllers;

use App\Models\Salary;
use App\Models\EmployeeBill;
use Illuminate\Http\Request;

class SalaryController extends Controller
{
    // Display a listing of the resource.   
     public function index()
    {
        $user = auth()->user();
        $query = Salary::with(['employee']);
        
        if ($user->isSuperAdmin()) {
            $salaries = $query->get();
        } elseif ($user->isAdmin()) {
            $salaries = $query->where('company_id', $user->company_id)->get();
        } elseif ($user->isUser() && $user->employee) {
            $hrDepartments = \App\Models\Department::where('hr_id', $user->employee->id)->pluck('id');
            if ($hrDepartments->count() > 0) {
                $salaries = $query->whereHas('employee', function($q) use ($hrDepartments) {
                    $q->whereIn('department_id', $hrDepartments);
                })->get();
            } else {
                $salaries = $query->where('employee_id', $user->employee->id)->get();
                // Get bills for the current user
                $bills = EmployeeBill::where('employee_id', $user->employee->id)
                    ->orderBy('created_at', 'desc')
                    ->get();
                return view('salaries.index', compact('salaries', 'bills'));
            }
        } else {
            $salaries = collect();
        }

        // Get pending bills for review (for admins and HR)
        if ($user->isAdmin() || $user->isSuperAdmin() || 
            ($user->isUser() && $user->employee && \App\Models\Department::where('hr_id', $user->employee->id)->exists())) {
            $pendingBills = EmployeeBill::with(['employee'])
                ->where('status', 'pending')
                ->when(!$user->isSuperAdmin(), function($query) use ($user) {
                    if ($user->isAdmin()) {
                        return $query->where('company_id', $user->company_id);
                    } else {
                        $hrDepartments = \App\Models\Department::where('hr_id', $user->employee->id)->pluck('id');
                        return $query->whereHas('employee', function($q) use ($hrDepartments) {
                            $q->whereIn('department_id', $hrDepartments);
                        });
                    }
                })
                ->get();
            return view('salaries.index', compact('salaries', 'pendingBills'));
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
        $selectedMonth = request()->input('salary_month', date('Y-m'));
        $bills = collect();
        $baseSalary = request()->input('base_salary');
        $selectedEmployeeId = request()->input('employee_id');
        
        if ($user->isSuperAdmin()) {
            $companies = \App\Models\Company::all();
            $companyId = request()->input('company_id');
            if ($companyId) {
                $employees = \App\Models\Employee::where('company_id', $companyId)->get();
            }
        } elseif ($user->isAdmin()) {
            $companyId = $user->company_id;
            $employees = \App\Models\Employee::where('company_id', $user->company_id)->get();
        } elseif ($user->isUser() && $user->employee) {
            $hrDepartments = \App\Models\Department::where('hr_id', $user->employee->id)->pluck('id');
            if ($hrDepartments->count() > 0) {
                $employees = \App\Models\Employee::whereIn('department_id', $hrDepartments)->get();
            } else {
                $employees = collect([\App\Models\Employee::find($user->employee->id)]);
            }
        }
        
        // Get approved bills for the selected employee and month
        if ($selectedEmployeeId && $selectedMonth) {
            $bills = \App\Models\EmployeeBill::where('employee_id', $selectedEmployeeId)
                ->where('status', 'approved')
                ->whereMonth('bill_date', date('m', strtotime($selectedMonth)))
                ->whereYear('bill_date', date('Y', strtotime($selectedMonth)))
                ->get();
        }
        
        return view('salaries.create', compact(
            'employees', 
            'companies', 
            'companyId', 
            'bills', 
            'selectedMonth', 
            'baseSalary', 
            'selectedEmployeeId'
        ));
    }    // Store a newly created resource in storage.
    public function store(Request $request)
    {        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'salary_month' => 'required|date_format:Y-m',
            'base_salary' => 'required|numeric',
            'bonus' => 'nullable|numeric',
            'deductions' => 'nullable|numeric',
            'paid_on' => 'required|date',
            'bill_amount' => 'nullable|numeric',
        ], [
            'employee_id.required' => 'Please select an employee',
            'employee_id.exists' => 'Selected employee does not exist',
            'salary_month.required' => 'Please select a salary month',
            'salary_month.date_format' => 'The salary month must be in YYYY-MM format',
            'base_salary.required' => 'Base salary is required',
            'base_salary.numeric' => 'Base salary must be a valid number',
            'bonus.numeric' => 'Bonus must be a valid number',
            'deductions.numeric' => 'Deductions must be a valid number',
            'paid_on.date' => 'Please enter a valid payment date',
            'paid_on.required' => 'Payment date is required',
            'bill_amount.numeric' => 'Bill amount must be a valid number'
        ]);        // Ensure salary_month is formatted with day
        $validated['salary_month'] = $validated['salary_month'] . '-01';

        // Get approved bills total
        $billAmount = EmployeeBill::where('employee_id', $validated['employee_id'])
            ->where('status', 'approved')
            ->whereMonth('bill_date', date('m', strtotime($validated['salary_month'])))
            ->whereYear('bill_date', date('Y', strtotime($validated['salary_month'])))
            ->sum('amount') ?? 0;

        $validated['bonus'] = $validated['bonus'] ?? 0;
        $validated['deductions'] = $validated['deductions'] ?? 0;        // Ensure all required fields are set
        $validated['base_salary'] = $validated['base_salary'] ?? 0;
        $validated['bonus'] = $validated['bonus'] ?? 0;
        $validated['deductions'] = $validated['deductions'] ?? 0;
        $validated['net_salary'] = $validated['base_salary'] + $validated['bonus'] + $billAmount - $validated['deductions'];
        $validated['company_id'] = auth()->user()->isSuperAdmin()
            ? ($request->company_id ?? null)
            : auth()->user()->company_id;

        // Create salary record with all fields
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
