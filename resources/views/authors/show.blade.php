@extends('layouts.app')

@section('title', $author->nome)

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="bi bi-person"></i> {{ $author->nome }}
                        @if($author->estado)
                            <span class="badge bg-success ms-2">Ativo</span>
                        @else
                            <span class="badge bg-secondary ms-2">Inativo</span>
                        @endif
                    </h4>
                    @if(auth()->user()->canManage())
                    <div class="d-flex gap-2">
                        <a href="{{ route('authors.edit', $author) }}" class="btn btn-edit-outline btn-sm">
                            <i class="bi bi-pencil"></i> Editar
                        </a>
                        <form action="{{ route('authors.destroy', $author) }}" method="POST" class="d-inline" 
                              onsubmit="return confirm('Tem certeza que deseja excluir este autor?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                <i class="bi bi-trash"></i> Excluir
                            </button>
                        </form>
                    </div>
                    @endif
                </div>
            </div>
            <div class="card-body">
                <h5>Livros do Autor ({{ $author->books->count() }})</h5>
                
                @if($author->books->count() > 0)
                    <div class="row">
                        @foreach($author->books as $book)
                            <div class="col-md-6 col-lg-4 mb-3">
                                <div class="card">
                                    <div class="card-body">
                                        <h6 class="card-title">{{ $book->titulo }}</h6>
                                        <p class="card-text text-muted small">
                                            <i class="bi bi-calendar"></i> {{ $book->data_publicacao->format('d/m/Y') }}
                                        </p>
                                        <p class="card-text">{{ Str::limit($book->descricao, 80) }}</p>
                                        <a href="{{ route('books.show', $book) }}" class="btn btn-outline-primary btn-sm">
                                            <i class="bi bi-eye"></i> Ver Livro
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-book text-muted" style="font-size: 3rem;"></i>
                        <p class="text-muted mt-2">Este autor ainda não possui livros cadastrados.</p>
                        @if(auth()->user()->canManage())
                        <a href="{{ route('books.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Adicionar Livro
                        </a>
                        @endif
                    </div>
                @endif

                <div class="mt-4">
                    <a href="{{ route('authors.index') }}" class="btn btn-custom-outline">
                        <i class="bi bi-arrow-left"></i> Voltar à Lista
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
