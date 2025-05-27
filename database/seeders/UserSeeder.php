<?php
namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Criar usuário de teste para a API
        User::firstOrCreate(
            ['email' => 'admin@test.com'],
            [
                'name'     => 'Admin Teste',
                'email'    => 'admin@test.com',
                'password' => Hash::make('password'),
            ]
        );

        // Criar usuário adicional
        User::firstOrCreate(
            ['email' => 'user@test.com'],
            [
                'name'     => 'Usuário Teste',
                'email'    => 'user@test.com',
                'password' => Hash::make('password'),
            ]
        );
    }
}
