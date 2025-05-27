<?php

use App\Models\Author;
use App\Models\Book;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

describe('BookController', function () {
    beforeEach(function () {
        $this->user = User::factory()->create();
        Storage::fake('public');
    });

    test('pode criar um livro sem capa', function () {
        $author   = Author::factory()->create();
        $bookData = [
            'titulo'          => 'Livro Teste',
            'descricao'       => 'Descrição do livro',
            'data_publicacao' => '2024-01-01',
            'author_id'       => $author->id,
        ];

        $this->actingAs($this->user)
            ->post(route('books.store'), $bookData)
            ->assertRedirect(route('books.index'))
            ->assertSessionHas('success', 'Livro criado com sucesso!');

        $this->assertDatabaseHas('books', [
            'titulo'    => $bookData['titulo'],
            'author_id' => $bookData['author_id'],
            'capa'      => null,
        ]);
    });

    test('valida tipos de arquivo para capa', function () {
        $author = Author::factory()->create();
        $file   = UploadedFile::fake()->create('documento.txt', 100);

        $bookData = [
            'titulo'          => 'Livro Teste',
            'descricao'       => 'Descrição do livro',
            'data_publicacao' => '2024-01-01',
            'author_id'       => $author->id,
            'capa'            => $file,
        ];

        $this->actingAs($this->user)
            ->post(route('books.store'), $bookData)
            ->assertSessionHasErrors(['capa']);
    });

    test('pode atualizar livro mantendo capa existente', function () {
        $author = Author::factory()->create();
        $book   = Book::factory()->create([
            'author_id' => $author->id,
            'capa'      => 'capas/capa_existente.jpg',
        ]);

        // Simular arquivo existente no storage
        Storage::disk('public')->put('capas/capa_existente.jpg', 'conteudo_fake');

        $updateData = [
            'titulo'          => 'Título Atualizado',
            'descricao'       => $book->descricao,
            'data_publicacao' => $book->data_publicacao->format('Y-m-d'),
            'author_id'       => $book->author_id,
        ];

        $this->actingAs($this->user)
            ->put(route('books.update', $book), $updateData)
            ->assertRedirect(route('books.index'))
            ->assertSessionHas('success', 'Livro atualizado com sucesso!');

        $book->refresh();
        expect($book->titulo)->toBe('Título Atualizado');
        expect($book->capa)->toBe('capas/capa_existente.jpg');
        Storage::disk('public')->assertExists('capas/capa_existente.jpg');
    });

    test('remove capa ao deletar livro', function () {
        $author = Author::factory()->create();
        $book   = Book::factory()->create([
            'author_id' => $author->id,
            'capa'      => 'capas/capa_para_deletar.jpg',
        ]);

        // Simular arquivo existente no storage
        Storage::disk('public')->put('capas/capa_para_deletar.jpg', 'conteudo_fake');

        $this->actingAs($this->user)
            ->delete(route('books.destroy', $book))
            ->assertRedirect(route('books.index'))
            ->assertSessionHas('success', 'Livro excluído com sucesso!');

        // Verificar se o livro foi deletado do banco
        $this->assertDatabaseMissing('books', ['id' => $book->id]);

        // Verificar se a capa foi removida do storage
        Storage::disk('public')->assertMissing('capas/capa_para_deletar.jpg');
    });

    test('deleta livro sem capa sem erros', function () {
        $author = Author::factory()->create();
        $book   = Book::factory()->create([
            'author_id' => $author->id,
            'capa'      => null,
        ]);

        $this->actingAs($this->user)
            ->delete(route('books.destroy', $book))
            ->assertRedirect(route('books.index'))
            ->assertSessionHas('success', 'Livro excluído com sucesso!');

        $this->assertDatabaseMissing('books', ['id' => $book->id]);
    });

    test('páginas de listagem exibem corretamente', function () {
        $author        = Author::factory()->create();
        $bookWithCover = Book::factory()->create([
            'author_id' => $author->id,
            'capa'      => 'capas/livro_com_capa.jpg',
        ]);

        $bookWithoutCover = Book::factory()->create([
            'author_id' => $author->id,
            'capa'      => null,
        ]);

        // Teste página autenticada
        $this->actingAs($this->user)
            ->get(route('books.index'))
            ->assertStatus(200)
            ->assertSee($bookWithCover->titulo)
            ->assertSee($bookWithoutCover->titulo);

        // Teste página pública
        $this->get(route('books.public'))
            ->assertStatus(200)
            ->assertSee($bookWithCover->titulo)
            ->assertSee($bookWithoutCover->titulo);
    });

    test('página de detalhes exibe capa quando presente', function () {
        $author = Author::factory()->create();
        $book   = Book::factory()->create([
            'author_id' => $author->id,
            'capa'      => 'capas/capa_detalhes.jpg',
        ]);

        $this->actingAs($this->user)
            ->get(route('books.show', $book))
            ->assertStatus(200)
            ->assertSee($book->titulo)
            ->assertSee('storage/capas/capa_detalhes.jpg');
    });

    test('formulários de criação e edição são exibidos corretamente', function () {
        $author = Author::factory()->create();

        // Teste formulário de criação
        $this->actingAs($this->user)
            ->get(route('books.create'))
            ->assertStatus(200)
            ->assertSee('enctype="multipart/form-data"', false)
            ->assertSee('accept="image/jpeg,image/jpg,image/png"', false);

        // Teste formulário de edição
        $book = Book::factory()->create([
            'author_id' => $author->id,
            'capa'      => 'capas/capa_edicao.jpg',
        ]);

        $this->actingAs($this->user)
            ->get(route('books.edit', $book))
            ->assertStatus(200)
            ->assertSee('enctype="multipart/form-data"', false)
            ->assertSee('accept="image/jpeg,image/jpg,image/png"', false);
    });
});
