@extends('layouts.app')

@section('title', $book->titulo)

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><i class="bi bi-book"></i> {{ $book->titulo }}</h4>
                    <div class="btn-group" role="group">
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
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    @if($book->capa)
                        <div class="col-md-3 mb-3">
                            <h6 class="text-muted">Capa</h6>
                            <img src="{{ asset('storage/' . $book->capa) }}" 
                                 alt="Capa de {{ $book->titulo }}" 
                                 class="img-fluid rounded shadow-sm"
                                 style="max-width: 200px; max-height: 200px; object-fit: cover;">
                        </div>
                        <div class="col-md-9">
                    @else
                        <div class="col-md-12">
                    @endif
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="text-muted">Autor</h6>
                                <p class="mb-3">
                                    <i class="bi bi-person"></i> 
                                    <a href="{{ route('authors.show', $book->author) }}" class="text-decoration-none">
                                        {{ $book->author->nome }}
                                    </a>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-muted">Data de Publicação</h6>
                                <p class="mb-3">
                                    <i class="bi bi-calendar"></i> {{ $book->data_publicacao->format('d/m/Y') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 mt-3">
                        <h6 class="text-muted">Descrição</h6>
                        <div class="border rounded p-3 bg-light">
                            {!! nl2br(e($book->descricao)) !!}
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <a href="{{ route('books.index') }}" class="btn btn-custom-outline">
                        <i class="bi bi-arrow-left"></i> Voltar à Lista
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
