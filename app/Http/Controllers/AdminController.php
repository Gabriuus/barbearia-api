<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\StoreAdminRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    private AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function index(Request $request): JsonResponse
    {
        $admins = User::whereHas('userType', function ($q) {
            $q->where('name', 'Admin');
        })->paginate($request->input('per_page', 15));

        return response()->json($admins);
    }

    public function show(User $admin): JsonResponse
    {
        if ($admin->userType->name !== 'Admin') {
            return response()->json(['message' => 'Not an admin'], 404);
        }
        return response()->json($admin);
    }

    public function store(StoreAdminRequest $request): JsonResponse
    {
        try {
            $user = $this->authService->registerAdmin($request->validated());

            return response()->json([
                'message' => 'Administrador registrado com sucesso.',
                'user' => $user
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao registrar administrador.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, User $admin): JsonResponse
    {
        if ($admin->userType->name !== 'Admin') {
            return response()->json(['message' => 'Not an admin'], 404);
        }
        
        $data = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,'.$admin->id,
            'password' => 'sometimes|string|min:8|confirmed'
        ]);

        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $admin->update($data);
        return response()->json(['message' => 'Admin atualizado.', 'admin' => $admin]);
    }

    public function destroy(User $admin): JsonResponse
    {
        if ($admin->userType->name !== 'Admin') {
            return response()->json(['message' => 'Not an admin'], 404);
        }
        $admin->delete();
        return response()->json(['message' => 'Admin deletado.']);
    }
}
