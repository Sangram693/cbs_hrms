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
        $companyId = $user->isSuperAdmin() ? $request->get('company_id') : $user->company_id;        $request->validate([
            'name' => 'required|string|max:255',
            'company_id' => 'required|exists:companies,id',
        ], [
            'name.required' => 'Leave type name is required',
            'name.string' => 'Leave type name must be a valid text',
            'name.max' => 'Leave type name cannot exceed 255 characters',
            'company_id.required' => 'Company is required',
            'company_id.exists' => 'Selected company does not exist'
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
    }    public function update(Request $request, LeaveType $leavetype)
    {
        $user = Auth::user();
        $rules = ['name' => 'required|string|max:255'];
        
        if ($user->isSuperAdmin()) {
            $rules['company_id'] = 'required|exists:companies,id';
            $companyId = $request->company_id;
        } else {
            $companyId = $user->company_id;
        }

        $request->validate($rules, [
            'name.required' => 'Leave type name is required',
            'name.string' => 'Leave type name must be a valid text',
            'name.max' => 'Leave type name cannot exceed 255 characters',
            'company_id.required' => 'Company is required',
            'company_id.exists' => 'Selected company does not exist'
        ]);
        
        $leavetype->update([
            'name' => $request->name,
            'company_id' => $companyId,
        ]);
        return redirect()->route('leavetypes.index', $user->isSuperAdmin() ? ['company_id' => $companyId] : [])->with('success', 'Leave type updated.');
    }

    public function destroy(LeaveType $leavetype)
    {
        $leavetype->delete();
        return back()->with('success', 'Leave type deleted.');
    }
}
