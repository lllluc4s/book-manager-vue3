@extends('layouts.app')

@section('title', 'Livros')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3">
        <i class="bi bi-book text-primary"></i>
        Catálogo de Livros
    </h1>
    <div class="alert alert-info mb-0" role="alert">
        <i class="bi bi-info-circle"></i>
        <a href="{{ route('login') }}" class="alert-link">Faça login</a> para gerenciar livros
    </div>
</div>

@if($books->count() > 0)
    <div class="row">
        @foreach($books as $book)
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title text-primary">{{ $book->titulo }}</h5>
                    <h6 class="card-subtitle mb-2 text-muted">
                        <i class="bi bi-person"></i>
                        {{ $book->author->nome }}
                    </h6>
                    <p class="card-text">{{ Str::limit($book->descricao, 120) }}</p>
                </div>
                <div class="card-footer bg-light">
                    <small class="text-muted">
                        <i class="bi bi-calendar"></i>
                        Publicado em {{ $book->data_publicacao->format('d/m/Y') }}
                    </small>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Paginação -->
    <div class="d-flex justify-content-center mt-4">
        {{ $books->links() }}
    </div>
@else
    <div class="text-center py-5">
        <i class="bi bi-book display-1 text-muted"></i>
        <h3 class="mt-3 text-muted">Nenhum livro encontrado</h3>
        <p class="text-muted">Não há livros cadastrados no momento.</p>
    </div>
@endif
@endsection
