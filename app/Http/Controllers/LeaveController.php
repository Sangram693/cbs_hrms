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
        } else {
            $leaves = $query->where('company_id', $user->company_id)->get();
        }
        return view('leaves.index', compact('leaves'));
    }

    public function create()
    {
        return view('leaves.create');
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

    public function edit(Leave $leave)
    {
        return view('leaves.edit', compact('leave'));
    }

    public function update(Request $request, Leave $leave)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'company_id' => 'required|exists:companies,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'type' => 'required|string|max:255',
            'status' => 'required|string|max:255',
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
}
