<?php

namespace App\Http\Controllers\Api;

use App\Models\Book;
use App\Models\Author;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class BookApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $books = Book::with('author')->latest();

        // Paginação
        $perPage = $request->get('per_page', 9);
        $books = $books->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $books->items(),
            'current_page' => $books->currentPage(),
            'last_page' => $books->lastPage(),
            'per_page' => $books->perPage(),
            'total' => $books->total()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'titulo' => 'required|string|max:255',
            'descricao' => 'required|string',
            'data_publicacao' => 'required|date',
            'author_id' => 'required|exists:authors,id',
            'capa' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erro de validação',
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $request->all();

        // Processar upload da capa
        if ($request->hasFile('capa')) {
            $data['capa'] = $this->processImageUpload($request->file('capa'));
        }

        $book = Book::create($data);
        $book->load('author');

        return response()->json([
            'success' => true,
            'message' => 'Livro criado com sucesso',
            'data' => $book,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $book = Book::with('author')->find($id);

        if (!$book) {
            return response()->json([
                'success' => false,
                'message' => 'Livro não encontrado',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $book,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json([
                'success' => false,
                'message' => 'Livro não encontrado',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'titulo' => 'sometimes|required|string|max:255',
            'descricao' => 'sometimes|required|string',
            'data_publicacao' => 'sometimes|required|date',
            'author_id' => 'sometimes|required|exists:authors,id',
            'capa' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erro de validação',
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $request->only(['titulo', 'descricao', 'data_publicacao', 'author_id']);

        // Processar upload da capa apenas se um novo arquivo foi enviado
        if ($request->hasFile('capa')) {
            // Deletar capa anterior se existir
            if ($book->capa) {
                Storage::disk('public')->delete($book->capa);
            }
            $data['capa'] = $this->processImageUpload($request->file('capa'));
        }

        $book->update($data);
        $book->load('author');

        return response()->json([
            'success' => true,
            'message' => 'Livro atualizado com sucesso',
            'data' => $book,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json([
                'success' => false,
                'message' => 'Livro não encontrado',
            ], 404);
        }

        // Deletar capa se existir
        if ($book->capa) {
            Storage::disk('public')->delete($book->capa);
        }

        $book->delete();

        return response()->json([
            'success' => true,
            'message' => 'Livro excluído com sucesso',
        ]);
    }

    /**
     * Remove apenas a capa do livro.
     */
    public function removeCapa($id)
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json([
                'success' => false,
                'message' => 'Livro não encontrado',
            ], 404);
        }

        // Verificar se o livro tem capa
        if ($book->capa) {
            // Deletar arquivo da capa
            Storage::disk('public')->delete($book->capa);

            // Remover referência da capa no banco
            $book->update(['capa' => null]);

            return response()->json([
                'success' => true,
                'message' => 'Capa removida com sucesso',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Este livro não possui capa para remover',
        ], 400);
    }

    /**
     * Processa o upload e redimensionamento da imagem.
     */
    private function processImageUpload($file)
    {
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $path = 'capas/' . $filename;

        // Criar instância do manager
        $manager = new ImageManager(new Driver());

        // Redimensionar para 200x200
        $image = $manager->read($file->getRealPath())
            ->cover(200, 200)
            ->encode();

        // Salvar no storage
        Storage::disk('public')->put($path, $image);

        return $path;
    }
}
