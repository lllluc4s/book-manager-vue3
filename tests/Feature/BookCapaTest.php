<?php

use App\Models\Author;
use App\Models\Book;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

describe('Book Capa (Upload de Capas)', function () {
    beforeEach(function () {
        $this->user = User::factory()->create();
        Storage::fake('public');
    });

    test('pode criar livro sem capa', function () {
        $author = Author::factory()->create();

        $response = $this->actingAs($this->user)
            ->post(route('books.store'), [
                'titulo'          => 'Livro sem Capa',
                'descricao'       => 'Descrição do livro',
                'data_publicacao' => '2024-01-01',
                'author_id'       => $author->id,
            ]);

        $response->assertRedirect(route('books.index'));
        $response->assertSessionHas('success', 'Livro criado com sucesso!');

        $this->assertDatabaseHas('books', [
            'titulo' => 'Livro sem Capa',
            'capa'   => null,
        ]);
    });

    test('rejeita arquivo que não é imagem', function () {
        $author = Author::factory()->create();
        $file   = UploadedFile::fake()->create('documento.txt', 100, 'text/plain');

        $response = $this->actingAs($this->user)
            ->post(route('books.store'), [
                'titulo'          => 'Livro Teste',
                'descricao'       => 'Descrição do livro',
                'data_publicacao' => '2024-01-01',
                'author_id'       => $author->id,
                'capa'            => $file,
            ]);

        $response->assertSessionHasErrors(['capa']);
    });

    test('rejeita arquivo muito grande', function () {
        $author = Author::factory()->create();
        // Simula arquivo de 3MB (maior que o limite de 2MB)
        $file = UploadedFile::fake()->create('capa.jpg', 3072, 'image/jpeg');

        $response = $this->actingAs($this->user)
            ->post(route('books.store'), [
                'titulo'          => 'Livro Teste',
                'descricao'       => 'Descrição do livro',
                'data_publicacao' => '2024-01-01',
                'author_id'       => $author->id,
                'capa'            => $file,
            ]);

        $response->assertSessionHasErrors(['capa']);
    });

    test('validação funciona para campos obrigatórios', function () {
        $response = $this->actingAs($this->user)
            ->post(route('books.store'), []);

        $response->assertSessionHasErrors(['titulo', 'descricao', 'data_publicacao', 'author_id']);
    });

    test('pode criar livro com dados válidos sem capa', function () {
        $author = Author::factory()->create();

        $response = $this->actingAs($this->user)
            ->post(route('books.store'), [
                'titulo'          => 'Livro Válido',
                'descricao'       => 'Descrição válida',
                'data_publicacao' => '2024-01-01',
                'author_id'       => $author->id,
            ]);

        $response->assertRedirect(route('books.index'));
        $response->assertSessionHas('success', 'Livro criado com sucesso!');

        $this->assertDatabaseHas('books', [
            'titulo' => 'Livro Válido',
            'capa'   => null,
        ]);
    });

    test('pode atualizar livro mantendo capa', function () {
        $author = Author::factory()->create();
        $book   = Book::factory()->create([
            'author_id' => $author->id,
            'capa'      => 'capas/capa_existente.jpg',
        ]);

        Storage::disk('public')->put('capas/capa_existente.jpg', 'conteudo_fake');

        $response = $this->actingAs($this->user)
            ->put(route('books.update', $book), [
                'titulo'          => 'Título Atualizado',
                'descricao'       => $book->descricao,
                'data_publicacao' => $book->data_publicacao->format('Y-m-d'),
                'author_id'       => $book->author_id,
            ]);

        $response->assertRedirect(route('books.index'));
        $book->refresh();

        expect($book->titulo)->toBe('Título Atualizado');
        expect($book->capa)->toBe('capas/capa_existente.jpg');
        Storage::disk('public')->assertExists('capas/capa_existente.jpg');
    });

    test('pode atualizar livro básico', function () {
        $author = Author::factory()->create();
        $book   = Book::factory()->create([
            'author_id' => $author->id,
            'titulo'    => 'Título Original',
        ]);

        $response = $this->actingAs($this->user)
            ->put(route('books.update', $book), [
                'titulo'          => 'Título Atualizado',
                'descricao'       => $book->descricao,
                'data_publicacao' => $book->data_publicacao->format('Y-m-d'),
                'author_id'       => $book->author_id,
            ]);

        $response->assertRedirect(route('books.index'));
        $book->refresh();

        expect($book->titulo)->toBe('Título Atualizado');
    });

    test('pode deletar livro básico', function () {
        $author = Author::factory()->create();
        $book   = Book::factory()->create([
            'author_id' => $author->id,
        ]);

        $response = $this->actingAs($this->user)
            ->delete(route('books.destroy', $book));

        $response->assertRedirect(route('books.index'));
        $this->assertDatabaseMissing('books', ['id' => $book->id]);
    });

    test('página index exibe lista de livros', function () {
        $author = Author::factory()->create();
        Book::factory()->create([
            'author_id' => $author->id,
            'titulo'    => 'Livro Visível',
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('books.index'));

        $response->assertStatus(200);
        $response->assertSee('Livro Visível');
    });

    test('página pública exibe lista de livros', function () {
        $author = Author::factory()->create();
        Book::factory()->create([
            'author_id' => $author->id,
            'titulo'    => 'Livro Público',
        ]);

        $response = $this->get(route('books.public'));

        $response->assertStatus(200);
        $response->assertSee('Livro Público');
    });

    test('página de detalhes exibe livro', function () {
        $author = Author::factory()->create();
        $book   = Book::factory()->create([
            'author_id' => $author->id,
            'titulo'    => 'Livro Detalhado',
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('books.show', $book));

        $response->assertStatus(200);
        $response->assertSee('Livro Detalhado');
    });

    test('formulário de criação é exibido', function () {
        Author::factory()->create();

        $response = $this->actingAs($this->user)
            ->get(route('books.create'));

        $response->assertStatus(200);
        $response->assertSee('enctype="multipart/form-data"', false);
        $response->assertSee('name="capa"', false);
    });

    test('formulário de edição é exibido', function () {
        $author = Author::factory()->create();
        $book   = Book::factory()->create(['author_id' => $author->id]);

        $response = $this->actingAs($this->user)
            ->get(route('books.edit', $book));

        $response->assertStatus(200);
        $response->assertSee('enctype="multipart/form-data"', false);
        $response->assertSee('name="capa"', false);
    });
});
