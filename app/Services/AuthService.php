<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserType;
use App\Models\Client;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AuthService
{
    /**
     * Registra um novo cliente com transação garantida.
     * 
     * @param array $data
     * @return User
     * @throws \Exception
     */
    public function registerClient(array $data): User
    {
        return DB::transaction(function () use ($data) {
            $clientType = UserType::where('name', 'Cliente')->first();
            if (!$clientType) {
                throw new \Exception('O tipo de usuário Cliente não foi encontrado no sistema.');
            }

            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'user_type_id' => $clientType->id,
            ]);

            Client::create([
                'user_id' => $user->id,
                'phone' => $data['phone'],
                'address' => $data['address'],
                'city' => $data['city'],
            ]);

            return $user;
        });
    }

    /**
     * Registra um novo admin. (Protegido por middleware nas rotas)
     * 
     * @param array $data
     * @return User
     * @throws \Exception
     */
    public function registerAdmin(array $data): User
    {
        $adminType = UserType::where('name', 'Admin')->first();
        if (!$adminType) {
            throw new \Exception('O tipo de usuário Admin não foi encontrado.');
        }

        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'user_type_id' => $adminType->id,
        ]);
    }

    /**
     * Realiza o login retornando credenciais e token.
     * 
     * @param array $credentials
     * @return array
     * @throws ValidationException
     */
    public function login(array $credentials): array
    {
        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['As credenciais fornecidas estão incorretas.'],
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;
        $userType = $user->userType->name;

        return [
            'user' => $user,
            'role' => $userType,
            'token' => $token
        ];
    }
}
