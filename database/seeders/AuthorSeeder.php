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
            'Fiódor Dostoiévski',
            'Lev Tolstói',
            'Anton Tchekhov',
            'Aleksandr Pushkin',
            'Nikolai Gogol',
            'Ivan Turguêniev',
            'Mikhail Bulgakov',
            'Boris Pasternak',
            'Aleksandr Soljenítsin',
            'Maxim Górki',
        ];

        foreach ($authors as $name) {
            // Verifica se o autor já existe antes de criar
            if (!Author::where('nome', $name)->exists()) {
                Author::create([
                    'nome'   => $name,
                    'estado' => true,
                ]);
            }
        }
    }
}
