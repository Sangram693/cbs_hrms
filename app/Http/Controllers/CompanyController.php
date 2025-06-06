<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        $companies = Company::getAllCompaniesForSuperAdmin($user);
        return view('companies.index', compact('companies'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('companies.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $messages = [
            'name.required' => 'Company name is required.',
            'name.string' => 'Company name must be text.',
            'name.max' => 'Company name cannot exceed 255 characters.',
            'address.max' => 'Company address cannot exceed 255 characters.',
            'phone.regex' => 'Phone number must be exactly 10 digits.'
        ];

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|regex:/^\d{10}$/',
        ], $messages);
        Company::create($validated);
        return redirect()->route('companies.index')->with('success', 'Company created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Company $company)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Company $company)
    {
        return view('companies.edit', compact('company'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Company $company)
    {
        $messages = [
            'name.required' => 'Company name is required.',
            'name.string' => 'Company name must be text.',
            'name.max' => 'Company name cannot exceed 255 characters.',
            'address.max' => 'Company address cannot exceed 255 characters.',
            'phone.regex' => 'Phone number must be exactly 10 digits.'
        ];

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|regex:/^\d{10}$/',
        ], $messages);
        $company->update($validated);
        return redirect()->route('companies.index')->with('success', 'Company updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $company)
    {
        $company->delete();
        return redirect()->route('companies.index')->with('success', 'Company deleted successfully.');
    }
}
