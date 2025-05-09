<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/**
 * @OA\Info(title="User API", version="1.0")
* @OA\Server(url="http://localhost:8000/api")
 */
class UserController extends Controller
{
    /**
     * @OA\Post(
     *     path="/login",
     *     summary="Login user",
     *     description="Authenticate a user and return a token.",
     *     tags={"User"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", format="email", example="techcorp@admin.com"),
     *             @OA\Property(property="password", type="string", format="password", example="admin123")
     *         )
     *     ),
    *     @OA\Response(
*         response=200,
*         description="Successfully logged in",
*         @OA\JsonContent(
*             @OA\Property(property="token", type="string", example="9|bBZM8d9DI29uZLHklyHYnfrE8htxxJYsKllZyNu9a53ca4ed"),
*             @OA\Property(property="user", type="object",
*                 @OA\Property(property="id", type="string", example="58981246-b10f-4a93-80ad-c0c1a376813d"),
*                 @OA\Property(property="name", type="string", example="TechCorp Admin"),
*                 @OA\Property(property="email", type="string", example="techcorp@admin.com"),
*                 @OA\Property(property="role", type="string", example="admin"),
*                 @OA\Property(property="company_id", type="string", nullable=true, example="38238916-0cb9-4e13-9537-8236f665fcfd"),
*                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-05-09T10:12:42.000000Z"),
*                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-05-09T10:12:42.000000Z")
*             )
*         )
*     ),

     *     @OA\Response(
     *         response=401,
     *         description="Invalid credentials",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Invalid credentials")
     *         )
     *     )
     * )
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => $user,
        ]);
    }

    // Other methods like index, store, etc., can be similarly annotated.
}
