<?php

namespace App\Http\Controllers;

use App\Models\EmployeeBill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EmployeeBillController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $query = EmployeeBill::with(['employee', 'approver']);

        if ($user->isSuperAdmin()) {
            $bills = $query->get();
        } elseif ($user->isAdmin()) {
            $bills = $query->where('company_id', $user->company_id)->get();
        } elseif ($user->isUser() && $user->employee) {
            // HR: can view all bills in their department
            $hrDepartments = \App\Models\Department::where('hr_id', $user->employee->id)->pluck('id');
            if ($hrDepartments->count() > 0) {
                $bills = $query->whereHas('employee', function($q) use ($hrDepartments) {
                    $q->whereIn('department_id', $hrDepartments);
                })->get();
            } else {
                // Normal user: only see their own bills
                $bills = $query->where('employee_id', $user->employee->id)->get();
            }
        } else {
            $bills = collect();
        }

        return view('bills.index', compact('bills'));
    }

    public function create()
    {
        return view('bills.upload');
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        
        $validated = $request->validate([
            'bill_type' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:255',
            'bill_date' => 'required|date',
            'bill_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048'
        ]);

        $file = $request->file('bill_file');
        $path = $file->store('bills', 'public');

        EmployeeBill::create([
            'employee_id' => $user->employee->id,
            'bill_type' => $validated['bill_type'],
            'amount' => $validated['amount'],
            'description' => $validated['description'],
            'file_path' => $path,
            'bill_date' => $validated['bill_date'],           
            'company_id' => $user->employee->company_id,
        ]);

        return redirect()->route('salaries.index')->with('success', 'Bill uploaded successfully.');
    }    public function updateStatus(Request $request, EmployeeBill $bill)
    {
        $validated = $request->validate([
            'status' => 'required|in:approved,rejected',
            'rejection_reason' => 'required_if:status,rejected|nullable|string|max:255'
        ]);

        $bill->update([
            'status' => $validated['status'],
            'rejection_reason' => $request->status === 'rejected' ? $validated['rejection_reason'] : null,
            'approved_by' => auth()->user()->employee->id
        ]);

        return redirect()->back()->with('success', 'Bill status updated successfully.');
    }

    public function destroy(EmployeeBill $bill)
    {
        $user = auth()->user();
        
        if ($user->employee->id !== $bill->employee_id || $bill->status !== 'pending') {
            return redirect()->back()->with('error', 'You cannot delete this bill.');
        }

        Storage::disk('public')->delete($bill->file_path);
        $bill->delete();

        return redirect()->back()->with('success', 'Bill deleted successfully.');
    }
}
