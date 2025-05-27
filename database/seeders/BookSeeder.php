<?php
namespace Database\Seeders;

use App\Models\Author;
use App\Models\Book;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $authors = Author::all();

        if (0 === $authors->count()) {
            $this->call(AuthorSeeder::class);
            $authors = Author::all();
        }

        $books = [
            [
                'titulo'          => 'O Cortiço',
                'descricao'       => 'Romance naturalista brasileiro que retrata a vida em um cortiço no Rio de Janeiro do século XIX, explorando as condições sociais e os conflitos humanos da época.',
                'data_publicacao' => '1890-01-15',
            ],
            [
                'titulo'          => 'Dom Casmurro',
                'descricao'       => 'Clássico da literatura brasileira que narra a história de Bentinho e Capitu, explorando temas como ciúme, memória e a complexidade dos relacionamentos humanos.',
                'data_publicacao' => '1899-12-01',
            ],
            [
                'titulo'          => 'A Moreninha',
                'descricao'       => 'Romance romântico brasileiro que conta a história de amor entre Augusto e Carolina, ambientado na Ilha de Paquetá, no Rio de Janeiro.',
                'data_publicacao' => '1844-06-10',
            ],
            [
                'titulo'          => 'O Guarani',
                'descricao'       => 'Romance indianista que narra a história de amor entre Peri, um índio guarani, e Ceci, filha de um fidalgo português, durante o período colonial brasileiro.',
                'data_publicacao' => '1857-04-20',
            ],
            [
                'titulo'          => 'Iracema',
                'descricao'       => 'Lenda do Ceará que conta a história de amor entre a índia Iracema e o português Martim, simbolizando o nascimento do povo brasileiro.',
                'data_publicacao' => '1865-05-01',
            ],
            [
                'titulo'          => 'O Ateneu',
                'descricao'       => 'Romance que critica o sistema educacional brasileiro através da narrativa autobiográfica de Sérgio, um jovem estudante em um colégio interno.',
                'data_publicacao' => '1888-03-15',
            ],
        ];

        foreach ($books as $bookData) {
            Book::create([
                'titulo'          => $bookData['titulo'],
                'descricao'       => $bookData['descricao'],
                'data_publicacao' => $bookData['data_publicacao'],
                'author_id'       => $authors->random()->id,
            ]);
        }
    }
}
