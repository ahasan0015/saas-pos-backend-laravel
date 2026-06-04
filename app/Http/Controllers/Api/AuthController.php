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
        // ১. ইনপুট ভ্যালিডেশন
        $request->validate([
            'phone' => 'required|string',
            'password' => 'required|string',
        ]);

        // ২. ইউজার খোঁজা
        $user = User::query()->where('phone', $request->phone)->first();

        // ৩. পাসওয়ার্ড চেক করা
        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'ভুল ফোন নম্বর অথবা পাসওয়ার্ড।'
            ], 401);
        }

        // ৪. স্ট্যাটাস চেক করা
        if ($user->status !== 1) {
            return response()->json([
                'success' => false,
                'message' => 'আপনার অ্যাকাউন্টটি নিষ্ক্রিয়।'
            ], 403);
        }

        // ৫. টোকেন ইস্যু করা
        $token = $user->createToken('auth_token', [$user->role])->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'লগইন সফল হয়েছে।',
            'token' => $token,
            'token_type' => 'Bearer',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'phone' => $user->phone,
                'role' => $user->role,
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