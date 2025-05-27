@extends('layouts.app')

@section('title', 'Lista de Livros')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-book"></i> Lista de Livros</h1>
    @if(auth()->user()->canManage())
        <a href="{{ route('books.create') }}" class="btn btn-custom-primary">
            <i class="bi bi-plus-circle"></i> Novo Livro
        </a>
    @endif
</div>

@if($books->count() > 0)
    <div class="row">
        @foreach($books as $book)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100">
                    @if($book->capa)
                        <div class="text-center pt-3">
                            <img src="{{ asset('storage/' . $book->capa) }}" 
                                 alt="Capa de {{ $book->titulo }}" 
                                 class="rounded"
                                 style="width: 120px; height: 120px; object-fit: cover;">
                        </div>
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $book->titulo }}</h5>
                        <p class="card-text text-muted mb-2">
                            <i class="bi bi-person"></i> {{ $book->author->nome }}
                        </p>
                        <p class="card-text text-muted mb-2">
                            <i class="bi bi-calendar"></i> {{ $book->data_publicacao->format('d/m/Y') }}
                        </p>
                        <p class="card-text">{{ Str::limit($book->descricao, 100) }}</p>
                    </div>
                    <div class="card-footer bg-transparent">
                        <div class="btn-group w-100" role="group">
                            <a href="{{ route('books.show', $book) }}" class="btn btn-custom-outline btn-sm">
                                <i class="bi bi-eye"></i> Ver
                            </a>
                            @if(auth()->user()->canManage())
                                <a href="{{ route('books.edit', $book) }}" class="btn btn-custom-outline btn-sm">
                                    <i class="bi bi-pencil"></i> Editar
                                </a>
                                <form action="{{ route('books.destroy', $book) }}" method="POST" class="d-inline" 
                                      onsubmit="return confirm('Tem certeza que deseja excluir este livro?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm">
                                        <i class="bi bi-trash"></i> Excluir
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Paginação -->
    <div class="d-flex justify-content-center">
        {{ $books->links() }}
    </div>
@else
    <div class="text-center py-5">
        <i class="bi bi-book display-1 text-muted"></i>
        <h3 class="text-muted mt-3">Nenhum livro encontrado</h3>
        <p class="text-muted">Comece adicionando seu primeiro livro!</p>
        <a href="{{ route('books.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Adicionar Livro
        </a>
    </div>
@endif
@endsection
