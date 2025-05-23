<?php

namespace App\Http\Controllers;

use App\Models\LeaveType;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaveTypeController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $companyId = $user->isSuperAdmin() ? $request->get('company_id') : $user->company_id;
        $companies = $user->isSuperAdmin() ? Company::all() : null;
        $leaveTypes = $companyId ? LeaveType::where('company_id', $companyId)->get() : collect();
        return view('leavetypes.index', compact('leaveTypes', 'companies', 'companyId'));
    }

    public function create(Request $request)
    {
        $user = Auth::user();
        $companyId = $user->isSuperAdmin() ? $request->get('company_id') : $user->company_id;
        $companies = $user->isSuperAdmin() ? Company::all() : null;
        return view('leavetypes.create', compact('companies', 'companyId'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $companyId = $user->isSuperAdmin() ? $request->get('company_id') : $user->company_id;
        $request->validate([
            'name' => 'required|string|max:255',
            'company_id' => 'required|exists:companies,id',
        ]);
        LeaveType::create([
            'name' => $request->name,
            'company_id' => $companyId ?? $request->company_id,
        ]);
        return redirect()->route('leavetypes.index', $user->isSuperAdmin() ? ['company_id' => $companyId] : [])->with('success', 'Leave type created.');
    }

    public function edit(LeaveType $leavetype)
    {
        $user = Auth::user();
        $companies = $user->isSuperAdmin() ? Company::all() : null;
        return view('leavetypes.edit', compact('leavetype', 'companies'));
    }

    public function update(Request $request, LeaveType $leavetype)
    {
        $user = Auth::user();
        $companyId = $user->isSuperAdmin() ? $request->get('company_id') : $user->company_id;
        $request->validate([
            'name' => 'required|string|max:255',
            'company_id' => 'required|exists:companies,id',
        ]);
        $leavetype->update([
            'name' => $request->name,
            'company_id' => $companyId ?? $request->company_id,
        ]);
        return redirect()->route('leavetypes.index', $user->isSuperAdmin() ? ['company_id' => $companyId] : [])->with('success', 'Leave type updated.');
    }

    public function destroy(LeaveType $leavetype)
    {
        $leavetype->delete();
        return back()->with('success', 'Leave type deleted.');
    }
}
