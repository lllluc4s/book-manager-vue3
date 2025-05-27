<?php
namespace Database\Seeders;

use App\Models\Author;
use Illuminate\Database\Seeder;

class AuthorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $authors = [
            'João Silva',
            'Maria Santos',
            'Pedro Oliveira',
            'Ana Costa',
            'Carlos Ferreira',
            'Lucia Pereira',
            'Roberto Almeida',
            'Fernanda Lima',
            'Antonio Rodrigues',
            'Isabel Carvalho',
        ];

        foreach ($authors as $name) {
            Author::create([
                'nome'   => $name,
                'estado' => true,
            ]);
        }
    }
}
