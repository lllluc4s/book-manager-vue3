<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Rota inicial
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('books.index');
    }

    return redirect()->route('books.public');
})->name('home');

// Rotas de autenticação
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Rotas públicas para visualização (sem autenticação)
Route::get('/books/public', [BookController::class, 'publicIndex'])->name('books.public');
Route::get('/authors/public', [AuthorController::class, 'publicIndex'])->name('authors.public');

// Rotas protegidas por autenticação
Route::middleware('auth')->group(function () {
    Route::resource('books', BookController::class);
    Route::delete('books/{book}/capa', [BookController::class, 'removeCapa'])->name('books.removeCapa');
    Route::resource('authors', AuthorController::class);
});
