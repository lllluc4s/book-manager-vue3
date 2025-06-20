@extends('layouts.app')

@section('title', 'Lista de Autores')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-person"></i> Lista de Autores</h1>
    @if(auth()->user()->canManage())
        <a href="{{ route('authors.create') }}" class="btn btn-custom-primary">
            <i class="bi bi-plus-circle"></i> Novo Autor
        </a>
    @endif
</div>

@if($authors->count() > 0)
    <div class="card card-custom">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Estado</th>
                            <th>Livros</th>
                            <th width="200">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($authors as $author)
                            <tr>
                                <td>{{ $author->nome }}</td>
                                <td>
                                    @if($author->estado)
                                        <span class="badge bg-success">Ativo</span>
                                    @else
                                        <span class="badge bg-secondary">Inativo</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $author->books_count ?? 0 }}</span>
                                </td>
                                <td>
                                    <div class="btn-action-group">
                                        <a href="{{ route('authors.show', $author) }}" class="btn btn-action-vertical btn-view" title="Ver detalhes">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        @if(auth()->user()->canManage())
                                            <a href="{{ route('authors.edit', $author) }}" class="btn btn-action-vertical btn-edit" title="Editar autor">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('authors.destroy', $author) }}" method="POST" class="d-inline" 
                                                  onsubmit="return confirm('Tem certeza que deseja excluir este autor? Esta ação não pode ser desfeita. ATENÇÃO: Não será possível excluir se houver livros associados.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-action-vertical btn-delete" title="Excluir autor">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Paginação -->
    <div class="pagination-wrapper">
        {{ $authors->links('pagination::custom') }}
    </div>
@else
    <div class="text-center py-5">
        <i class="bi bi-person display-1 text-muted"></i>
        <h3 class="text-muted mt-3">Nenhum autor encontrado</h3>
        <p class="text-muted">Comece adicionando seu primeiro autor!</p>
        <a href="{{ route('authors.create') }}" class="btn btn-custom-primary">
            <i class="bi bi-plus-circle"></i> Adicionar Autor
        </a>
    </div>
@endif
@endsection
