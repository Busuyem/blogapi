<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;


class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        try {
            $data = $request->only(['name','email','password']);
            $user = User::create($data);
            $token = JWTAuth::fromUser($user);

            return response()->json([
                'user' => $user,
                'token' => $token
            ], 201);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Registration failed', 'error' => $e->getMessage()], 500);
        }
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only(['email','password']);
        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['message' => 'Invalid credentials'], 401);
            }
            return response()->json(['message' => 'Login successful!', 'token' => $token]);
        } catch (JWTException $e) {
            return response()->json(['message' => 'Could not create token', 'error' => $e->getMessage()], 500);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Login error', 'error' => $e->getMessage()], 500);
        }
    }

    public function me(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            return response()->json($user);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Could not fetch user', 'error' => $e->getMessage()], 401);
        }
    }

    public function logout()
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());
            return response()->json(['message' => 'Successfully logged out']);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Logout failed', 'error' => $e->getMessage()], 500);
        }
    }
}
