<?php

namespace App\Http\Controllers\Api;

use App\Models\Author;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class AuthorApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $authors = Author::query();

        // Filtro por estado se fornecido
        if ($request->has('estado')) {
            $authors->where('estado', $request->estado);
        }

        // Incluir contagem de livros
        $authors->withCount('books');

        // Paginação
        $perPage = $request->get('per_page', 15);
        $authors = $authors->paginate($perPage);

        return response()->json([
            'success' => true,
            'data'    => $authors,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nome'   => 'required|string|max:255',
            'estado' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erro de validação',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $author = Author::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Autor criado com sucesso',
            'data'    => $author,
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param mixed $id
     */
    public function show($id)
    {
        $author = Author::with('books')->find($id);

        if (! $author) {
            return response()->json([
                'success' => false,
                'message' => 'Autor não encontrado',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data'    => $author,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param mixed $id
     */
    public function update(Request $request, $id)
    {
        $author = Author::find($id);

        if (! $author) {
            return response()->json([
                'success' => false,
                'message' => 'Autor não encontrado',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'nome'   => 'sometimes|required|string|max:255',
            'estado' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erro de validação',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $author->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Autor atualizado com sucesso',
            'data'    => $author,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param mixed $id
     */
    public function destroy($id)
    {
        $author = Author::find($id);

        if (! $author) {
            return response()->json([
                'success' => false,
                'message' => 'Autor não encontrado',
            ], 404);
        }

        // Verificar se o autor tem livros associados
        if ($author->books()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => '=( Ops! Não é possível excluir este autor pois ele possui livros associados. Exclua os livros primeiro.',
            ], 409);
        }

        $author->delete();

        return response()->json([
            'success' => true,
            'message' => 'Autor excluído com sucesso',
        ]);
    }

    /**
     * Obter livros de um autor específico.
     *
     * @param mixed $id
     */
    public function books($id)
    {
        $author = Author::find($id);

        if (! $author) {
            return response()->json([
                'success' => false,
                'message' => 'Autor não encontrado',
            ], 404);
        }

        $books = $author->books()->paginate(15);

        return response()->json([
            'success' => true,
            'data'    => [
                'author' => $author,
                'books'  => $books,
            ],
        ]);
    }
}
