<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterClientRequest;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    private AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Handle client registration.
     */
    public function register(RegisterClientRequest $request): JsonResponse
    {
        try {
            $user = $this->authService->registerClient($request->validated());

            return response()->json([
                'message' => 'Cliente registrado com sucesso.',
                'user' => $user
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao registrar.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Handle login.
     */
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $result = $this->authService->login($request->only('email', 'password'));

        return response()->json([
            'message' => 'Login realizado com sucesso.',
            'data' => $result
        ]);
    }

    /**
     * Handle logout.
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout realizado com sucesso.'
        ]);
    }
}
