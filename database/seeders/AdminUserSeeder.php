<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\UserType;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminType = UserType::where('name', 'Admin')->first();

        if ($adminType) {
            User::firstOrCreate(
                ['email' => 'admin@barbearia.com'],
                [
                    'name' => 'Administrador principal',
                    'password' => Hash::make('admin123'),
                    'user_type_id' => $adminType->id,
                    'email_verified_at' => now(),
                    'remember_token' => Str::random(10),
                ]
            );
        }
    }
}
