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
            'password' => 'nullable|string|min:8|confirmed',
            'password_confirmation' => 'nullable|string|min:8',
        ];        // Add employee-specific validation rules if not superadmin
        if (!$user->isSuperAdmin() && $employee) {
            $rules['phone'] = 'nullable|string|regex:/^\d{10}$/';
        }

        $messages = [
            'name.required' => 'Name is required',
            'name.string' => 'Name must be text',
            'name.max' => 'Name cannot exceed 255 characters',
            'email.required' => 'Email address is required',
            'email.email' => 'Please enter a valid email address',
            'email.unique' => 'This email address is already taken',
            'password.min' => 'Password must be at least 8 characters',            'password.confirmed' => 'Password confirmation does not match',
            'password_confirmation.min' => 'Password confirmation must be at least 8 characters',
            'phone.regex' => 'Phone number must be exactly 10 digits'
        ];

        $validated = $request->validate($rules, $messages);

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
