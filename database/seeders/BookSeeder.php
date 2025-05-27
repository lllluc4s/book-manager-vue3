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
                'titulo'          => 'Os Irmãos Karamázov',
                'descricao'       => 'Último romance de Dostoiévski, explorando questões de fé, dúvida, moralidade e livre arbítrio através da história de uma família disfuncional.',
                'data_publicacao' => '1880-01-01',
                'autor'           => 'Fiódor Dostoiévski',
            ],
            [
                'titulo'          => 'O Idiota',
                'descricao'       => 'Romance sobre o Príncipe Myshkin, um homem de bondade cristã que tenta viver em uma sociedade corrupta e materialista.',
                'data_publicacao' => '1869-01-01',
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
                'titulo'          => 'Ressurreição',
                'descricao'       => 'Último romance de Tolstói, abordando temas de redenção moral e crítica social através da história de um nobre que busca reparar seus erros do passado.',
                'data_publicacao' => '1899-01-01',
                'autor'           => 'Lev Tolstói',
            ],
            [
                'titulo'          => 'O Jardim das Cerejeiras',
                'descricao'       => 'Peça teatral que retrata o declínio da aristocracia russa através da história de uma família que deve vender sua propriedade ancestral.',
                'data_publicacao' => '1904-01-17',
                'autor'           => 'Anton Tchekhov',
            ],
            [
                'titulo'          => 'A Gaivota',
                'descricao'       => 'Peça teatral sobre amor não correspondido, ambições artísticas frustradas e a busca por significado na vida.',
                'data_publicacao' => '1896-01-01',
                'autor'           => 'Anton Tchekhov',
            ],
            [
                'titulo'          => 'Tio Vânia',
                'descricao'       => 'Drama que explora temas de desilusão, amor perdido e a monotonia da vida provinciana russa.',
                'data_publicacao' => '1897-01-01',
                'autor'           => 'Anton Tchekhov',
            ],
            [
                'titulo'          => 'Eugênio Onéguin',
                'descricao'       => 'Romance em versos que narra a história do jovem aristocrata Eugênio Onéguin e seu relacionamento com Tatiana, explorando temas do amor e da sociedade russa.',
                'data_publicacao' => '1833-01-01',
                'autor'           => 'Aleksandr Pushkin',
            ],
            [
                'titulo'          => 'A Dama de Espadas',
                'descricao'       => 'Novela sobre obsessão pelo jogo e pelo dinheiro, considerada uma das obras-primas da literatura russa.',
                'data_publicacao' => '1834-01-01',
                'autor'           => 'Aleksandr Pushkin',
            ],
            [
                'titulo'          => 'Almas Mortas',
                'descricao'       => 'Sátira social sobre um homem que viaja pela Rússia comprando servos mortos para um esquema fraudulento, retratando a corrupção da sociedade russa.',
                'data_publicacao' => '1842-01-01',
                'autor'           => 'Nikolai Gogol',
            ],
            [
                'titulo'          => 'O Capote',
                'descricao'       => 'Conto sobre um funcionário público humilde e sua obsessão por um novo capote, considerado uma obra fundamental da literatura russa.',
                'data_publicacao' => '1842-01-01',
                'autor'           => 'Nikolai Gogol',
            ],
            [
                'titulo'          => 'Pais e Filhos',
                'descricao'       => 'Romance que explora o conflito geracional na Rússia do século XIX através da história de um jovem niilista e sua relação com a família.',
                'data_publicacao' => '1862-01-01',
                'autor'           => 'Ivan Turguêniev',
            ],
            [
                'titulo'          => 'Primeiro Amor',
                'descricao'       => 'Novela autobiográfica sobre a experiência do primeiro amor de um jovem de dezesseis anos.',
                'data_publicacao' => '1860-01-01',
                'autor'           => 'Ivan Turguêniev',
            ],
            [
                'titulo'          => 'O Mestre e Margarida',
                'descricao'       => 'Romance satírico que mistura realismo e fantasia, retratando a visita do diabo à Moscou soviética dos anos 1930.',
                'data_publicacao' => '1967-01-01',
                'autor'           => 'Mikhail Bulgakov',
            ],
            [
                'titulo'          => 'A Guarda Branca',
                'descricao'       => 'Romance sobre uma família burguesa ucraniana durante a Guerra Civil Russa, explorando temas de lealdade e sobrevivência.',
                'data_publicacao' => '1925-01-01',
                'autor'           => 'Mikhail Bulgakov',
            ],
            [
                'titulo'          => 'Doutor Jivago',
                'descricao'       => 'Épico histórico sobre um médico e poeta durante a Revolução Russa e a Guerra Civil, explorando amor, arte e política.',
                'data_publicacao' => '1957-01-01',
                'autor'           => 'Boris Pasternak',
            ],
            [
                'titulo'          => 'Arquipélago Gulag',
                'descricao'       => 'Obra documentária sobre o sistema de campos de trabalho forçado na União Soviética, baseada na experiência pessoal do autor.',
                'data_publicacao' => '1973-01-01',
                'autor'           => 'Aleksandr Soljenítsin',
            ],
            [
                'titulo'          => 'A Mãe',
                'descricao'       => 'Romance sobre uma mãe que se torna revolucionária após a prisão de seu filho, considerado um clássico da literatura socialista.',
                'data_publicacao' => '1906-01-01',
                'autor'           => 'Maxim Górki',
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
