<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SuperAdminController extends Controller
{


    /**
 * @OA\Post(
 *     path="/create-company",
 *     summary="Create a company and its admin",
 *     description="Creates a new company along with an associated admin user.",
 *     tags={"SuperAdmin"},
 *     security={{"sanctum":{}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name"},
 *             @OA\Property(property="name", type="string", example="TechCorp"),
 *             @OA\Property(property="address", type="string", nullable=true, example="123 Tech Street"),
 *             @OA\Property(property="phone", type="string", nullable=true, example="+1234567890")
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Company and Admin created successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Company and Admin created successfully."),
 *             @OA\Property(property="company", type="object",
 *                 @OA\Property(property="id", type="string", format="uuid", example="f47ac10b-58cc-4372-a567-0e02b2c3d479"),
 *                 @OA\Property(property="name", type="string", example="TechCorp"),
 *                 @OA\Property(property="address", type="string", nullable=true, example="123 Tech Street"),
 *                 @OA\Property(property="phone", type="string", nullable=true, example="+1234567890")
 *             ),
 *             @OA\Property(property="admin", type="object",
 *                 @OA\Property(property="id", type="string", format="uuid", example="a3f0c70e-1234-41ef-867e-7df8a320b155"),
 *                 @OA\Property(property="name", type="string", example="TechCorp Admin"),
 *                 @OA\Property(property="email", type="string", example="techcorp@admin.com"),
 *                 @OA\Property(property="role", type="string", example="admin"),
 *                 @OA\Property(property="company_id", type="string", format="uuid", example="f47ac10b-58cc-4372-a567-0e02b2c3d479")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=409,
 *         description="Company name or admin email already exists",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Company name already exists.")
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Failed to create company or admin",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Failed to create company or admin."),
 *             @OA\Property(property="error", type="string", example="SQLSTATE[...]: Integrity constraint violation...")
 *         )
 *     )
 * )
 */

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

/**
 * @OA\Get(
 *     path="/company/{companyId}",
 *     summary="Get a company by ID",
 *     description="Fetches the details of a company, including its associated users (e.g., admin).",
 *     tags={"SuperAdmin"},
 *     security={{"sanctum":{}}},
 *     @OA\Parameter(
 *         name="companyId",
 *         in="path",
 *         required=true,
 *         description="The ID of the company to retrieve",
 *         @OA\Schema(type="string", format="uuid")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Company retrieved successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Company retrieved successfully."),
 *             @OA\Property(property="company", type="object",
 *                 @OA\Property(property="id", type="string", format="uuid", example="f47ac10b-58cc-4372-a567-0e02b2c3d479"),
 *                 @OA\Property(property="name", type="string", example="TechCorp"),
 *                 @OA\Property(property="address", type="string", nullable=true, example="123 Tech Street"),
 *                 @OA\Property(property="phone", type="string", nullable=true, example="+1234567890"),
 *                 @OA\Property(property="users", type="array", @OA\Items(
 *                     @OA\Property(property="id", type="string", format="uuid", example="a3f0c70e-1234-41ef-867e-7df8a320b155"),
 *                     @OA\Property(property="name", type="string", example="TechCorp Admin"),
 *                     @OA\Property(property="email", type="string", example="techcorp@admin.com"),
 *                     @OA\Property(property="role", type="string", example="admin"),
 *                     @OA\Property(property="company_id", type="string", format="uuid", example="f47ac10b-58cc-4372-a567-0e02b2c3d479")
 *                 ))
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid company ID format",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Invalid company ID format.")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Company not found",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Company not found.")
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Failed to fetch company",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Failed to fetch company."),
 *             @OA\Property(property="error", type="string", example="SQLSTATE[...]: Integrity constraint violation...")
 *         )
 *     )
 * )
 */



public function readCompany($companyId)
    {
        try {

            if (!Str::isUuid($companyId)) {
            return response()->json([
                'message' => 'Invalid company ID format.',
            ], 400);
        }
            // Find the company by its ID
            $company = Company::with(['users' => function($query) {
                $query->where('active', true);
            }])->find($companyId);

            if (!$company) {
                return response()->json([
                    'message' => 'Company not found.',
                ], 404);
            }

            // If the company exists, return the company with users (including the admin)
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
 * @OA\Get(
 *     path="/companies",
 *     summary="Get all companies",
 *     description="Fetches a list of all companies with pagination, including associated users (e.g., admins).",
 *     tags={"SuperAdmin"},
 *     security={{"sanctum":{}}},
 *     @OA\Parameter(
 *         name="per_page",
 *         in="query",
 *         description="Number of results per page",
 *         required=false,
 *         @OA\Schema(type="integer", example=10)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Companies retrieved successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Companies retrieved successfully."),
 *             @OA\Property(property="data", type="array", @OA\Items(
 *                 @OA\Property(property="id", type="string", format="uuid", example="f47ac10b-58cc-4372-a567-0e02b2c3d479"),
 *                 @OA\Property(property="name", type="string", example="TechCorp"),
 *                 @OA\Property(property="address", type="string", nullable=true, example="123 Tech Street"),
 *                 @OA\Property(property="phone", type="string", nullable=true, example="+1234567890"),
 *                 @OA\Property(property="users", type="array", @OA\Items(
 *                     @OA\Property(property="id", type="string", format="uuid", example="a3f0c70e-1234-41ef-867e-7df8a320b155"),
 *                     @OA\Property(property="name", type="string", example="TechCorp Admin"),
 *                     @OA\Property(property="email", type="string", example="techcorp@admin.com"),
 *                     @OA\Property(property="role", type="string", example="admin"),
 *                     @OA\Property(property="company_id", type="string", format="uuid", example="f47ac10b-58cc-4372-a567-0e02b2c3d479")
 *                 ))
 *             ))
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Failed to fetch companies",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Failed to fetch companies."),
 *             @OA\Property(property="error", type="string", example="SQLSTATE[...]: Integrity constraint violation...")
 *         )
 *     )
 * )
 */

    public function readAllCompanies(Request $request)
{
    try {
        // Get the 'per_page' query parameter, or default to 10 if not provided
        $perPage = $request->input('per_page', 10); 

        // Get all companies with pagination
        $companies = Company::with(['users' => function($query) {
            $query->where('active', true);
        }])->paginate($perPage);

        return response()->json([
            'message' => 'Companies retrieved successfully.',
            'data' => $companies,
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Failed to fetch companies.',
            'error' => $e->getMessage(),
        ], 500);
    }
}


}
