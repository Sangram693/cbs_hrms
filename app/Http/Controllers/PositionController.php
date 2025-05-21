<?php

namespace App\Http\Controllers;

use App\Models\Position;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if ($user->isSuperAdmin()) {
            $positions = Position::all();
        } else {
            $positions = Position::where('company_id', $user->company_id)->get();
        }
        return view('positions.index', compact('positions'));
    }

    public function create()
    {
        $user = auth()->user();
        $isSuperAdmin = $user->isSuperAdmin();
        $companies = $isSuperAdmin ? \App\Models\Company::all() : null;
        $departments = $isSuperAdmin ? \App\Models\Department::all() : \App\Models\Department::where('company_id', $user->company_id)->get();
        return view('positions.create', compact('departments', 'companies', 'isSuperAdmin'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $isSuperAdmin = $user->isSuperAdmin();
        $rules = [
            'title' => 'required|string|max:255',
            'level' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
        ];
        if ($isSuperAdmin) {
            $rules['company_id'] = 'required|exists:companies,id';
        }
        $validated = $request->validate($rules);
        $validated['id'] = \Illuminate\Support\Str::uuid();
        if (!$isSuperAdmin) {
            $validated['company_id'] = $user->company_id;
        }
        \App\Models\Position::create($validated);
        return redirect()->route('positions.index')->with('success', 'Position created successfully.');
    }

    public function edit(Position $position)
    {
        $user = auth()->user();
        $isSuperAdmin = $user->isSuperAdmin();
        $companies = $isSuperAdmin ? \App\Models\Company::all() : null;
        $departments = $isSuperAdmin ? \App\Models\Department::all() : \App\Models\Department::where('company_id', $user->company_id)->get();
        return view('positions.edit', compact('position', 'departments', 'companies', 'isSuperAdmin'));
    }

    public function update(Request $request, Position $position)
    {
        $user = auth()->user();
        $isSuperAdmin = $user->isSuperAdmin();
        $rules = [
            'title' => 'required|string|max:255',
            'level' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
        ];
        if ($isSuperAdmin) {
            $rules['company_id'] = 'required|exists:companies,id';
        }
        $validated = $request->validate($rules);
        if (!$isSuperAdmin) {
            $validated['company_id'] = $user->company_id;
        }
        $position->update($validated);
        return redirect()->route('positions.index')->with('success', 'Position updated successfully.');
    }

    public function destroy(Position $position)
    {
        $position->delete();
        return redirect()->route('positions.index')->with('success', 'Position deleted successfully.');
    }
}
