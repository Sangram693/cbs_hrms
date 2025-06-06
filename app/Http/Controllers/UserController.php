<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

/**
* @OA\Info(
 *     title="HRMS API",
 *     version="1.0",
 *     description="This API handles all HRMS (Human Resource Management System) operations, including employee management, attendance, and payroll."
 * )
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

        $user = User::where('email', $request->email)->where('active', true)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => $user,
        ]);
    }

    /**
     * Handle session-based login for Blade (web) routes.
     */
    public function sessionLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (auth()->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            $user = auth()->user();
            if ($user->isAdmin()) {
                return redirect()->intended('/dashboard');
            } elseif ($user->isSuperAdmin()) {
                return redirect()->intended('/dashboard');
            } elseif ($user->isUser()) {
                return redirect()->intended('/dashboard');
            } else {
                auth()->logout();
                return back()->withErrors(['email' => 'Unauthorized role'])->withInput();
            }
        }

        return back()->withErrors([
            'email' => 'Invalid credentials',
        ])->withInput();
    }

    /**
     * Display the dashboard.
     */
    public function dashboard()
    {
        $user = auth()->user();
        $stats = $this->dashboardStats(request(), true);

        if ($user->isSuperAdmin()) {
            return view('dashboard_superadmin', compact('stats'));
        } elseif ($user->isAdmin() || $user->isHr()) {
            return view('dashboard_admin', compact('stats'));
        } else {
            return view('dashboard_user');
        }
    }

    // API endpoint for dashboard stats
    public function dashboardStats(Request $request, $returnArray = false)
    {
        $user = $request->user();
        $companyId = $user && $user->company_id ? $user->company_id : null;
        $isSuperAdmin = $user && method_exists($user, 'isSuperAdmin') && $user->isSuperAdmin();

        $stats = [];
        if ($isSuperAdmin) {
            $stats['companies'] = DB::table('companies')->count();
            $stats['employees'] = DB::table('employees')->where('status', "Active")->count();
            $stats['departments'] = DB::table('departments')->count();
            $stats['designations'] = DB::table('designations')->count();
            $stats['attendance'] = DB::table('attendances')->count();
            $stats['leaves'] = DB::table('leaves')->count();
            $stats['pending_leaves'] = DB::table('leaves')->where('status', 'Pending')->count();
            $stats['salaries'] = DB::table('salaries')->count();
            $stats['trainings'] = DB::table('trainings')->count();
        } elseif ($companyId) {
            $stats['companies'] = 1;
            $stats['employees'] = DB::table('employees')->where('company_id', $companyId)->where('status', "Active")->count();
            $stats['departments'] = DB::table('departments')->where('company_id', $companyId)->count();
            $stats['designations'] = DB::table('designations')->where('company_id', $companyId)->count();
            $stats['attendance'] = DB::table('attendances')->where('company_id', $companyId)->count();
            $stats['leaves'] = DB::table('leaves')->where('company_id', $companyId)->count();
            $stats['pending_leaves'] = DB::table('leaves')->where('company_id', $companyId)->where('status', 'Pending')->count();
            $stats['salaries'] = DB::table('salaries')->where('company_id', $companyId)->count();
            $stats['trainings'] = DB::table('trainings')->where('company_id', $companyId)->count();
        } else {
            $stats = [
                'companies' => 0,
                'employees' => 0,
                'departments' => 0,
                'designations' => 0,
                'attendance' => 0,
                'leaves' => 0,
                'pending_leaves' => 0,
                'salaries' => 0,
                'trainings' => 0,
            ];
        }
        return $returnArray ? $stats : response()->json($stats);
    }

    // Other methods like index, store, etc., can be similarly annotated.
}
