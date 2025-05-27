<?php

use App\Models\Author;
use App\Models\Book;
use Illuminate\Support\Facades\DB;

describe('Saúde Essencial do Sistema', function () {
    test('conexão com banco de dados funciona', function () {
        expect(DB::connection()->getPdo())->not->toBeNull();
    });

    test('tabelas principais existem', function () {
        expect(DB::getSchemaBuilder()->hasTable('authors'))->toBeTrue();
        expect(DB::getSchemaBuilder()->hasTable('books'))->toBeTrue();
    });

    test('models funcionam corretamente', function () {
        // Testa Author
        $author = Author::factory()->create(['nome' => 'Autor Teste']);
        expect($author->exists)->toBeTrue();
        expect($author->nome)->toBe('Autor Teste');

        // Testa Book
        $book = Book::factory()->create([
            'titulo'    => 'Livro Teste',
            'author_id' => $author->id,
        ]);
        expect($book->exists)->toBeTrue();
        expect($book->titulo)->toBe('Livro Teste');
        expect($book->author->id)->toBe($author->id);
    });

    test('rota inicial funciona', function () {
        $response = $this->get('/');
        expect($response->status())->toBe(302); // Deve redirecionar
    });

    test('configuração básica é válida', function () {
        expect(config('app.key'))->not->toBeEmpty();
        expect(config('database.default'))->not->toBeEmpty();
    });
});
