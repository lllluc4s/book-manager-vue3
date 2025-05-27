<?php

use App\Models\Author;
use App\Models\Book;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

describe('Book Capa (Upload de Capas)', function () {
    beforeEach(function () {
        $this->user      = User::factory()->create();
        $this->adminUser = User::factory()->create(['role' => 'admin']);
        Storage::fake('public');
    });

    test('pode criar livro com capa válida', function () {
        $author = Author::factory()->create();
        $file   = UploadedFile::fake()->image('capa.jpg', 300, 300)->size(1024);

        $response = $this->actingAs($this->adminUser)
            ->post(route('books.store'), [
                'titulo'          => 'Livro com Capa',
                'descricao'       => 'Descrição do livro',
                'data_publicacao' => '2024-01-01',
                'author_id'       => $author->id,
                'capa'            => $file,
            ]);

        $response->assertRedirect(route('books.index'));
        $response->assertSessionHas('success', 'Livro criado com sucesso!');

        $book = Book::where('titulo', 'Livro com Capa')->first();
        expect($book->capa)->not->toBeNull();
        expect($book->capa)->toContain('capas/');
        Storage::disk('public')->assertExists($book->capa);
    });

    test('valida formato e tamanho da capa', function () {
        $author = Author::factory()->create();

        // Teste arquivo inválido (não é imagem)
        $fileInvalido = UploadedFile::fake()->create('documento.txt', 100, 'text/plain');
        $response     = $this->actingAs($this->adminUser)
            ->post(route('books.store'), [
                'titulo'          => 'Livro Teste',
                'descricao'       => 'Descrição do livro',
                'data_publicacao' => '2024-01-01',
                'author_id'       => $author->id,
                'capa'            => $fileInvalido,
            ]);
        $response->assertSessionHasErrors(['capa']);

        // Teste arquivo muito grande
        $fileMuitoGrande = UploadedFile::fake()->image('capa.jpg', 500, 500)->size(3072); // 3MB
        $response        = $this->actingAs($this->adminUser)
            ->post(route('books.store'), [
                'titulo'          => 'Livro Teste 2',
                'descricao'       => 'Descrição do livro',
                'data_publicacao' => '2024-01-01',
                'author_id'       => $author->id,
                'capa'            => $fileMuitoGrande,
            ]);
        $response->assertSessionHasErrors(['capa']);
    });

    test('redimensiona imagem para 200x200 pixels', function () {
        $author = Author::factory()->create();
        $file   = UploadedFile::fake()->image('capa_grande.jpg', 800, 600)->size(1000);

        $response = $this->actingAs($this->adminUser)
            ->post(route('books.store'), [
                'titulo'          => 'Livro Redimensionado',
                'descricao'       => 'Teste de redimensionamento',
                'data_publicacao' => '2024-01-01',
                'author_id'       => $author->id,
                'capa'            => $file,
            ]);

        $response->assertRedirect(route('books.index'));

        $book = Book::where('titulo', 'Livro Redimensionado')->first();
        expect($book->capa)->not->toBeNull();
        Storage::disk('public')->assertExists($book->capa);
        expect($book->capa)->toContain('capas/');
    });

    test('pode substituir capa existente', function () {
        $author = Author::factory()->create();
        $book   = Book::factory()->create([
            'author_id' => $author->id,
            'capa'      => 'capas/capa_antiga.jpg',
        ]);

        // Simular arquivo existente no storage
        Storage::disk('public')->put('capas/capa_antiga.jpg', 'conteudo_antigo');

        $newFile = UploadedFile::fake()->image('nova_capa.png', 200, 200)->size(500);

        $response = $this->actingAs($this->adminUser)
            ->put(route('books.update', $book), [
                'titulo'          => $book->titulo,
                'descricao'       => $book->descricao,
                'data_publicacao' => $book->data_publicacao->format('Y-m-d'),
                'author_id'       => $book->author_id,
                'capa'            => $newFile,
            ]);

        $response->assertRedirect(route('books.index'));
        $book->refresh();

        // Verificar se a capa antiga foi deletada
        Storage::disk('public')->assertMissing('capas/capa_antiga.jpg');

        // Verificar se a nova capa foi salva
        expect($book->capa)->not->toBe('capas/capa_antiga.jpg');
        expect($book->capa)->toContain('capas/');
        Storage::disk('public')->assertExists($book->capa);
    });

    test('remove capa ao deletar livro', function () {
        $author = Author::factory()->create();
        $file   = UploadedFile::fake()->image('capa_deletar.jpg', 200, 200)->size(500);

        // Criar livro com capa
        $response = $this->actingAs($this->adminUser)
            ->post(route('books.store'), [
                'titulo'          => 'Livro Para Deletar',
                'descricao'       => 'Será deletado',
                'data_publicacao' => '2024-01-01',
                'author_id'       => $author->id,
                'capa'            => $file,
            ]);

        $book     = Book::where('titulo', 'Livro Para Deletar')->first();
        $capaPath = $book->capa;

        // Verificar que a capa existe
        Storage::disk('public')->assertExists($capaPath);

        // Deletar o livro
        $response = $this->actingAs($this->adminUser)
            ->delete(route('books.destroy', $book));

        $response->assertRedirect(route('books.index'));

        // Verificar se o livro foi deletado do banco
        $this->assertDatabaseMissing('books', ['id' => $book->id]);

        // Verificar se a capa foi removida do storage
        Storage::disk('public')->assertMissing($capaPath);
    });

    test('pode remover apenas a capa do livro', function () {
        $author = Author::factory()->create();
        $file   = UploadedFile::fake()->image('capa_remover.jpg', 200, 200)->size(500);

        // Criar livro com capa
        $response = $this->actingAs($this->adminUser)
            ->post(route('books.store'), [
                'titulo'          => 'Livro Com Capa',
                'descricao'       => 'Terá capa removida',
                'data_publicacao' => '2024-01-01',
                'author_id'       => $author->id,
                'capa'            => $file,
            ]);

        $book     = Book::where('titulo', 'Livro Com Capa')->first();
        $capaPath = $book->capa;

        // Verificar que a capa existe
        Storage::disk('public')->assertExists($capaPath);
        expect($book->capa)->not->toBeNull();

        // Remover apenas a capa
        $response = $this->actingAs($this->adminUser)
            ->delete(route('books.removeCapa', $book));

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Capa removida com sucesso!');

        $book->refresh();

        // Verificar se o livro ainda existe no banco
        $this->assertDatabaseHas('books', ['id' => $book->id]);

        // Verificar se a capa foi removida
        expect($book->capa)->toBeNull();
        Storage::disk('public')->assertMissing($capaPath);
    });

    test('retorna erro ao tentar remover capa de livro sem capa', function () {
        $author = Author::factory()->create();
        $book   = Book::factory()->create([
            'author_id' => $author->id,
            'capa'      => null,
        ]);

        $response = $this->actingAs($this->adminUser)
            ->delete(route('books.removeCapa', $book));

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Este livro não possui capa para remover.');
    });
});
