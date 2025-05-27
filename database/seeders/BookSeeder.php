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
                'titulo'          => 'Crime e Castigo',
                'descricao'       => 'Romance psicológico que narra a história de Raskólnikov, um jovem estudante que comete um duplo assassinato e lida com as consequências morais e psicológicas de seus atos.',
                'data_publicacao' => '1866-01-01',
                'autor'           => 'Fiódor Dostoiévski',
            ],
            [
                'titulo'          => 'Guerra e Paz',
                'descricao'       => 'Épico histórico que retrata a sociedade russa durante as guerras napoleônicas, seguindo as vidas de várias famílias aristocráticas russas.',
                'data_publicacao' => '1869-01-01',
                'autor'           => 'Lev Tolstói',
            ],
            [
                'titulo'          => 'Anna Karenina',
                'descricao'       => 'Drama que conta a história trágica de Anna Karenina, uma mulher da alta sociedade russa que se envolve em um caso amoroso que mudará sua vida para sempre.',
                'data_publicacao' => '1877-01-01',
                'autor'           => 'Lev Tolstói',
            ],
            [
                'titulo'          => 'O Jardim das Cerejeiras',
                'descricao'       => 'Peça teatral que retrata o declínio da aristocracia russa através da história de uma família que deve vender sua propriedade ancestral.',
                'data_publicacao' => '1904-01-17',
                'autor'           => 'Anton Tchekhov',
            ],
            [
                'titulo'          => 'Eugênio Onéguin',
                'descricao'       => 'Romance em versos que narra a história do jovem aristocrata Eugênio Onéguin e seu relacionamento com Tatiana, explorando temas do amor e da sociedade russa.',
                'data_publicacao' => '1833-01-01',
                'autor'           => 'Aleksandr Pushkin',
            ],
            [
                'titulo'          => 'Os Irmãos Karamázov',
                'descricao'       => 'Último romance de Dostoiévski que explora questões filosóficas e religiosas através da história de uma família disfuncional e o assassinato do patriarca.',
                'data_publicacao' => '1880-01-01',
                'autor'           => 'Fiódor Dostoiévski',
            ],
        ];

        foreach ($books as $bookData) {
            $author = $authors->where('nome', $bookData['autor'])->first();
            
            Book::create([
                'titulo'          => $bookData['titulo'],
                'descricao'       => $bookData['descricao'],
                'data_publicacao' => $bookData['data_publicacao'],
                'author_id'       => $author ? $author->id : $authors->random()->id,
            ]);
        }
    }
}
