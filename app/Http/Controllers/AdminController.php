<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function createUser(Request $request)
    {
        // Ensure the user is an admin
        $user = $request->user();

        if (!$user->isAdmin()) {
            return response()->json(['message' => 'Only admins can create users.'], 403);
        }

        // Validate input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
        ]);

        // Create a new employee user under the admin's company
        $newUser = User::create([
            'id' => Str::uuid(),
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'role' => 'user', // Default role is user
            'company_id' => $user->company_id, // Same company as the admin
        ]);

        return response()->json([
            'message' => 'User created successfully.',
            'user' => $newUser,
        ]);
    }
}
