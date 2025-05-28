<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Employee;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('profile');
    }

    public function update(Request $request)
    {
        $user = $request->user();
        $employee = $user->isSuperAdmin() ? null : $user->employee;

        

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6',
        ];

        // Add employee-specific validation rules if not superadmin
        if (!$user->isSuperAdmin() && $employee) {
            $rules['phone'] = 'nullable|string|max:255';
        }

        $validated = $request->validate($rules);

        // Update user
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        if ($request->filled('password')) {
            $user->password = $validated['password']; // No need to hash, model mutator will do it
        }
        $user->save();
          // Update employee if exists (for both admin and regular employees)
        if ($employee) {
            $employee->name = $validated['name'];
            $employee->email = $validated['email'];
            if (isset($validated['phone'])) {
                $employee->phone = $validated['phone'];
            }
            $employee->save();
        }        return redirect()->route('dashboard')->with('success', 'Profile updated successfully.');
    }
}
