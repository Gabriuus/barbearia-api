<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json(['message' => 'Sistema de Barbearia API (Laravel 12) está rodando perfeitamente. Acesse /api para os endpoints principais.']);
});
