<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ClientController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $clients = Client::with('user')->paginate($request->input('per_page', 15));
        return response()->json($clients);
    }

    public function show(Client $client): JsonResponse
    {
        $client->load('user');
        return response()->json($client);
    }

    public function update(Request $request, Client $client): JsonResponse
    {
        $data = $request->validate([
            'phone' => 'sometimes|string|max:20',
            'address' => 'sometimes|string|max:255',
            'city' => 'sometimes|string|max:100',
        ]);

        $client->update($data);
        return response()->json(['message' => 'Cliente atualizado.', 'client' => $client]);
    }

    public function destroy(Client $client): JsonResponse
    {
        $user = $client->user;
        $client->delete();
        if ($user) {
            $user->delete();
        }
        return response()->json(['message' => 'Cliente e usuário deletados.']);
    }
}
