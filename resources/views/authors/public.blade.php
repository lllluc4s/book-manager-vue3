@extends('layouts.app')

@section('title', 'Autores')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3">
        <i class="bi bi-person text-primary"></i>
        Catálogo de Autores
    </h1>
    <div class="alert alert-info mb-0" role="alert">
        <i class="bi bi-info-circle"></i>
        <a href="{{ route('login') }}" class="alert-link">Faça login</a> para gerenciar autores
    </div>
</div>

@if($authors->count() > 0)
    <div class="row">
        @foreach($authors as $author)
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="bi bi-person-circle display-4 text-primary"></i>
                    </div>
                    <h5 class="card-title">{{ $author->nome }}</h5>
                    <div class="mb-2">
                        @if($author->estado)
                            <span class="badge bg-success">
                                <i class="bi bi-check-circle"></i> Ativo
                            </span>
                        @else
                            <span class="badge bg-secondary">
                                <i class="bi bi-x-circle"></i> Inativo
                            </span>
                        @endif
                    </div>
                    <p class="card-text">
                        <i class="bi bi-book"></i>
                        {{ $author->books_count }} 
                        {{ $author->books_count == 1 ? 'livro' : 'livros' }}
                    </p>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Paginação -->
    <div class="d-flex justify-content-center mt-4">
        {{ $authors->links() }}
    </div>
@else
    <div class="text-center py-5">
        <i class="bi bi-person display-1 text-muted"></i>
        <h3 class="mt-3 text-muted">Nenhum autor encontrado</h3>
        <p class="text-muted">Não há autores cadastrados no momento.</p>
    </div>
@endif
@endsection
