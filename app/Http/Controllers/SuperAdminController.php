<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SuperAdminController extends Controller
{
   public function createCompany(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'address' => 'nullable|string',
        'phone' => 'nullable|string'
    ]);

    if (Company::where('name', $request->name)->exists()) {
    return response()->json([
        'message' => 'Company name already exists.',
    ], 409);
}

    try {
        $company = Company::create([
            'id' => Str::uuid(),
            'name' => $request->name,
            'address' => $request->address,
            'phone' => $request->phone
        ]);

        $email = strtolower($request->name) . '@admin.com';

        // Check if the email already exists
        if (User::where('email', $email)->exists()) {
            return response()->json([
                'message' => 'Admin email already exists for another company.',
            ], 409); // Conflict
        }

        $admin = User::create([
            'id' => Str::uuid(),
            'name' => $request->name . ' Admin',
            'email' => $email,
            'password' => 'admin123',
            'role' => 'admin',
            'company_id' => $company->id,
        ]);

        return response()->json([
            'message' => 'Company and Admin created successfully.',
            'company' => $company,
            'admin' => $admin,
        ], 201);
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Failed to create company or admin.',
            'error' => $e->getMessage(),
        ], 500);
    }
}

}
