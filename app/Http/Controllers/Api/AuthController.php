<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * লগইন হ্যান্ডেল করা এবং টোকেন ইস্যু করা।
     */
    public function login(Request $request)
    {
        // 1. Validate input data
        $request->validate([
            'phone' => 'required|string',
            'password' => 'required|string',
        ]);

        // 2. Find user and load role relationship
        $user = User::with('role')->where('phone', $request->phone)->first();

        // 3. Check password
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid phone number or password.'
            ], 401);
        }

        // 4. Check user status (optional, if 'status' column exists)
        // Note: Make sure your database has a 'status' column before using this
        // if ($user->status !== 1) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Account is inactive.'
        //     ], 403);
        // }

        // 5. Issue token (use role name as ability)
        $roleName = $user->role ? $user->role->name : 'user';
        $token = $user->createToken('auth_token', [$roleName])->plainTextToken;

        // 6. Return response
        return response()->json([
            'success' => true,
            'message' => 'Login successful.',
            'token' => $token,
            'token_type' => 'Bearer',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'phone' => $user->phone,
                'role' => $user->role ? $user->role->name : null,
                'tenant_id' => $user->tenant_id,
                'outlet_id' => $user->outlet_id,
            ]
        ], 200);
    }

    /**
     * লগআউট হ্যান্ডেল করা।
     */
    public function logout(Request $request)
    {
        /** @var \Laravel\Sanctum\PersonalAccessToken|null $token */
        $token = $request->user()?->currentAccessToken();
        if ($token) {
            $token->delete();
        }

        return response()->json([
            'success' => true,
            'message' => 'সফলভাবে লগআউট হয়েছে।'
        ], 200);
    }
}
