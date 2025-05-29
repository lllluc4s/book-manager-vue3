<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Rota inicial - servir Vue SPA para todas as rotas
Route::get('/', function () {
    return view('app');
})->name('home');

// Rotas Vue SPA (todas as rotas do frontend serão capturadas aqui)
Route::get('/{any}', function () {
    return view('app');
})->where('any', '.*')->name('spa');

/*
// Rotas de autenticação Blade desabilitadas - usando API
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
*/

/*
// Rotas Blade desabilitadas - substituídas por Vue SPA + API
// Rotas protegidas por autenticação - apenas visualização
Route::middleware('auth')->group(function () {
    Route::get('/books', [BookController::class, 'index'])->name('books.index');
    Route::get('/authors', [AuthorController::class, 'index'])->name('authors.index');
});

// Rotas admin
Route::middleware(['auth', 'admin'])->group(function () {
    // Books - operações administrativas
    Route::get('/books/create', [BookController::class, 'create'])->name('books.create');
    Route::post('/books', [BookController::class, 'store'])->name('books.store');
    Route::get('/books/{book}/edit', [BookController::class, 'edit'])->name('books.edit');
    Route::put('/books/{book}', [BookController::class, 'update'])->name('books.update');
    Route::delete('/books/{book}', [BookController::class, 'destroy'])->name('books.destroy');
    Route::delete('/books/{book}/capa', [BookController::class, 'removeCapa'])->name('books.removeCapa');

    // Authors - operações administrativas
    Route::get('/authors/create', [AuthorController::class, 'create'])->name('authors.create');
    Route::post('/authors', [AuthorController::class, 'store'])->name('authors.store');
    Route::get('/authors/{author}/edit', [AuthorController::class, 'edit'])->name('authors.edit');
    Route::put('/authors/{author}', [AuthorController::class, 'update'])->name('authors.update');
    Route::delete('/authors/{author}', [AuthorController::class, 'destroy'])->name('authors.destroy');
});

// Rotas protegidas por autenticação
Route::middleware('auth')->group(function () {
    Route::get('/books/{book}', [BookController::class, 'show'])->name('books.show');
    Route::get('/authors/{author}', [AuthorController::class, 'show'])->name('authors.show');
});
*/
