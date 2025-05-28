<?php

use App\Models\Author;
use App\Models\Book;

describe('Book Model', function () {
    uses(\Tests\TestCase::class);
    uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

    beforeEach(function () {
        // Não precisa do migrate:fresh quando usando RefreshDatabase
    });

    test('pode criar um livro sem capa', function () {
        $author = Author::factory()->create();

        $book = Book::create([
            'titulo'          => 'Livro Teste',
            'descricao'       => 'Descrição do livro',
            'data_publicacao' => '2024-01-01',
            'author_id'       => $author->id,
        ]);

        expect($book)->toBeInstanceOf(Book::class);
        expect($book->titulo)->toBe('Livro Teste');
        expect($book->capa)->toBeNull();
    });

    test('pode criar um livro com capa', function () {
        $author = Author::factory()->create();

        $book = Book::create([
            'titulo'          => 'Livro com Capa',
            'descricao'       => 'Descrição do livro',
            'data_publicacao' => '2024-01-01',
            'author_id'       => $author->id,
            'capa'            => 'capas/exemplo.jpg',
        ]);

        expect($book)->toBeInstanceOf(Book::class);
        expect($book->titulo)->toBe('Livro com Capa');
        expect($book->capa)->toBe('capas/exemplo.jpg');
    });

    test('campo capa está no fillable', function () {
        $book = new Book();
        expect($book->getFillable())->toContain('capa');
    });

    test('pode atualizar a capa do livro', function () {
        $author = Author::factory()->create();

        $book = Book::factory()->create([
            'author_id' => $author->id,
            'capa'      => null,
        ]);

        $book->update(['capa' => 'capas/nova_capa.jpg']);

        expect($book->fresh()->capa)->toBe('capas/nova_capa.jpg');
    });

    test('pode remover a capa do livro', function () {
        $author = Author::factory()->create();

        $book = Book::factory()->create([
            'author_id' => $author->id,
            'capa'      => 'capas/capa_para_remover.jpg',
        ]);

        $book->update(['capa' => null]);

        expect($book->fresh()->capa)->toBeNull();
    });

    test('relacionamento com autor funciona corretamente', function () {
        $author = Author::factory()->create();

        $book = Book::factory()->create([
            'author_id' => $author->id,
            'capa'      => 'capas/livro_com_autor.jpg',
        ]);

        expect($book->author)->toBeInstanceOf(Author::class);
        expect($book->author->id)->toBe($author->id);
    });

    test('pode buscar livros com capa', function () {
        $author = Author::factory()->create();

        Book::factory()->create([
            'author_id' => $author->id,
            'titulo'    => 'Livro sem capa',
            'capa'      => null,
        ]);

        Book::factory()->create([
            'author_id' => $author->id,
            'titulo'    => 'Livro com capa',
            'capa'      => 'capas/exemplo.jpg',
        ]);

        $booksWithCover    = Book::whereNotNull('capa')->get();
        $booksWithoutCover = Book::whereNull('capa')->get();

        expect($booksWithCover)->toHaveCount(1);
        expect($booksWithoutCover)->toHaveCount(1);
        expect($booksWithCover->first()->titulo)->toBe('Livro com capa');
        expect($booksWithoutCover->first()->titulo)->toBe('Livro sem capa');
    });

    test('factory funciona com capa', function () {
        $author = Author::factory()->create();

        $bookWithCover    = Book::factory()->withCover()->create(['author_id' => $author->id]);
        $bookWithoutCover = Book::factory()->create(['author_id' => $author->id]);

        expect($bookWithCover->capa)->not->toBeNull();
        expect($bookWithCover->capa)->toContain('capas/fake_cover_');
        expect($bookWithoutCover->capa)->toBeNull();
    });
});
