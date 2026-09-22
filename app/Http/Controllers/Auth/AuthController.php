<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Handle an incoming authentication request for API.
     */
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            return response()->json([
                'message' => 'Email atau password yang Anda masukkan salah.',
            ], 401);
        }

        $accessToken = $user->createToken('access_token', ['*'])->plainTextToken;
        $refreshToken = $user->createToken('refresh_token', ['issue-access-token'])->plainTextToken;

        return response()->json([
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken,
            'token_type' => 'Bearer',
        ]);
    }

    /**
     * Refresh tokens for authenticated user.
     */
    public function refresh(Request $request): JsonResponse
    {
        $user = $request->user();

        // Revoke existing access tokens for the user
        $user->tokens()->where('name', 'access_token')->delete();

        $accessToken = $user->createToken('access_token', ['*'])->plainTextToken;
        $refreshToken = $user->createToken('refresh_token', ['issue-access-token'])->plainTextToken;

        return response()->json([
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken,
            'token_type' => 'Bearer',
        ]);
    }

    /**
     * Revoke current authenticated session token.
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Anda telah berhasil keluar dari sistem.',
        ]);
    }
}
