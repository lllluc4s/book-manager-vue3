<?php
namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Author;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $books = Book::with('author')->latest()->paginate(10);

        return view('books.index', compact('books'));
    }

    /**
     * Display a listing of books for public (non-authenticated users).
     */
    public function publicIndex()
    {
        $books = Book::with('author')->latest()->paginate(10);

        return view('books.public', compact('books'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $authors = Author::active()->orderBy('nome')->get();

        return view('books.create', compact('authors'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'titulo'          => 'required|string|max:255',
            'descricao'       => 'required|string',
            'data_publicacao' => 'required|date',
            'author_id'       => 'required|exists:authors,id',
            'capa'            => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
        ]);

        $data = $request->all();

        // Processar upload da capa
        if ($request->hasFile('capa')) {
            $data['capa'] = $this->processImageUpload($request->file('capa'));
        }

        Book::create($data);

        return redirect()->route('books.index')
            ->with('success', 'Livro criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        $book->load('author');

        return view('books.show', compact('book'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Book $book)
    {
        $authors = Author::active()->orderBy('nome')->get();

        return view('books.edit', compact('book', 'authors'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Book $book)
    {
        $request->validate([
            'titulo'          => 'required|string|max:255',
            'descricao'       => 'required|string',
            'data_publicacao' => 'required|date',
            'author_id'       => 'required|exists:authors,id',
            'capa'            => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
        ]);

        $data = $request->all();

        // Processar upload da capa
        if ($request->hasFile('capa')) {
            // Deletar capa anterior se existir
            if ($book->capa) {
                Storage::disk('public')->delete($book->capa);
            }
            $data['capa'] = $this->processImageUpload($request->file('capa'));
        }

        $book->update($data);

        return redirect()->route('books.index')
            ->with('success', 'Livro atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        // Deletar capa se existir
        if ($book->capa) {
            Storage::disk('public')->delete($book->capa);
        }

        $book->delete();

        return redirect()->route('books.index')
            ->with('success', 'Livro excluído com sucesso!');
    }

    /**
     * Processa o upload e redimensionamento da imagem.
     *
     * @param mixed $file
     */
    private function processImageUpload($file)
    {
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $path     = 'capas/' . $filename;

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
