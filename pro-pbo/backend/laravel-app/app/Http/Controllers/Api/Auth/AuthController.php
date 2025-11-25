<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest; // Kita akan buat ini
use App\Http\Requests\Auth\RegisterRequest; // Kita akan buat ini
use App\Services\AuthServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    protected AuthServiceInterface $authService;

    public function __construct(AuthServiceInterface $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Register a new user.
     *
     * @param \App\Http\Requests\Auth\RegisterRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        // Log data yang diterima oleh controller (sebelum FormRequest memvalidasi ulang)
        \Log::info('AuthController@register - Raw Input Data:', $request->all());

        try {
            $user = $this->authService->register($request->validated());
            return response()->json([
                'message' => 'User registered successfully',
                'user' => $user
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Log error validasi juga bisa membantu
            \Log::info('AuthController@register - Validation Error:', $e->validator->errors()->toArray());
            return response()->json(['errors' => $e->validator->errors()], 422);
        }
    }

    /**
     * Login user and return token.
     *
     * @param \App\Http\Requests\Auth\LoginRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $result = $this->authService->login($request->email, $request->password);
            return response()->json([
                'message' => 'Login successful',
                'user' => $result['user'],
                'token' => $result['token'],
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->validator->errors()], 422);
        }
    }

    /**
     * Logout user.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        $user = $request->user(); // Sanctum otomatis mengautentikasi user jika token valid
        if ($user) {
            $this->authService->logout($user);
            return response()->json(['message' => 'Logged out successfully']);
        }

        return response()->json(['message' => 'Not authenticated'], 401);
    }
}