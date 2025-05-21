<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    /**
     * @OA\Post(
     *     path="/create-user",
     *     summary="Create a new user (employee) under the admin's company",
     *     tags={"Admin"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","email","password"},
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User created successfully.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="User created successfully."),
     *             @OA\Property(property="user", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Only admins can create users."
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error."
     *     )
     * )
     */
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

    /**
     * @OA\Get(
     *     path="/my-company",
     *     summary="Get the company details for the authenticated admin, including its users",
     *     tags={"Admin"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Company retrieved successfully.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Company retrieved successfully."),
     *             @OA\Property(property="company", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Company not found for this user."
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Failed to fetch company."
     *     )
     * )
     */
    public function myCompany(Request $request)
    {
        try {
            $user = $request->user();

            if (!$user || !$user->company_id) {
                return response()->json([
                    'message' => 'Company not found for this user.',
                ], 404);
            }

            $company = \App\Models\Company::with(['users' => function($query) {
                $query->where('active', true);
            }])->find($user->company_id);

            if (!$company) {
                return response()->json([
                    'message' => 'Company not found.',
                ], 404);
            }

            return response()->json([
                'message' => 'Company retrieved successfully.',
                'company' => $company,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to fetch company.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/update-company",
     *     summary="Update the authenticated admin's company details",
     *     tags={"Admin"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="New Company Name"),
     *             @OA\Property(property="address", type="string", example="123 Main St")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Company updated successfully.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Company updated successfully."),
     *             @OA\Property(property="company", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Company not found."
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized."
     *     )
     * )
     */
    public function updateCompany(Request $request)
    {
        $user = $request->user();
        if (!$user->isAdmin()) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }
        $company = \App\Models\Company::find($user->company_id);
        if (!$company) {
            return response()->json(['message' => 'Company not found.'], 404);
        }
        $company->update($request->only(['name', 'address']));
        return response()->json([
            'message' => 'Company updated successfully.',
            'company' => $company,
        ]);
    }

    /**
     * @OA\Put(
     *     path="/update-user/{userId}",
     *     summary="Update user details (admin only)",
     *     tags={"Admin"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="userId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Jane Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="jane@example.com")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User updated successfully.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="User updated successfully."),
     *             @OA\Property(property="user", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found."
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized."
     *     )
     * )
     */
    public function updateUser(Request $request, $userId)
    {
        $user = $request->user();
        if (!$user->isAdmin()) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }
        $targetUser = User::find($userId);
        if (!$targetUser) {
            return response()->json(['message' => 'User not found.'], 404);
        }
        $targetUser->update($request->only(['name', 'email']));
        return response()->json([
            'message' => 'User updated successfully.',
            'user' => $targetUser,
        ]);
    }

    /**
     * @OA\Put(
     *     path="/terminate-user/{userId}",
     *     summary="Terminate (deactivate) a user (admin only)",
     *     tags={"Admin"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="userId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User terminated successfully.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="User terminated successfully."),
     *             @OA\Property(property="user", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found."
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized."
     *     )
     * )
     */
    public function terminateUser(Request $request, $userId)
    {
        $user = $request->user();
        if (!$user->isAdmin()) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }
        $targetUser = User::find($userId);
        if (!$targetUser) {
            return response()->json(['message' => 'User not found.'], 404);
        }
        $targetUser->active = false; // or $targetUser->status = 'terminated';
        $targetUser->save();
        return response()->json([
            'message' => 'User terminated successfully.',
            'user' => $targetUser,
        ]);
    }

}
