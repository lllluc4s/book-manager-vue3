<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AuthorApiController;
use App\Http\Controllers\Api\BookApiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Rotas de autenticação (públicas)
Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
});

// Rotas protegidas por autenticação
Route::middleware('auth:sanctum')->group(function () {
    // Rotas de autenticação (autenticadas)
    Route::prefix('auth')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('user', [AuthController::class, 'user']);
    });

    // API de Autores (protegida)
    Route::apiResource('authors', AuthorApiController::class)->names([
        'index'   => 'api.authors.index',
        'store'   => 'api.authors.store',
        'show'    => 'api.authors.show',
        'update'  => 'api.authors.update',
        'destroy' => 'api.authors.destroy',
    ]);
    Route::get('authors/{id}/books', [AuthorApiController::class, 'books'])->name('api.authors.books');

    // API de Livros (protegida)
    Route::apiResource('books', BookApiController::class)->names([
        'index'   => 'api.books.index',
        'store'   => 'api.books.store',
        'show'    => 'api.books.show',
        'update'  => 'api.books.update',
        'destroy' => 'api.books.destroy',
    ]);
    Route::delete('books/{id}/capa', [BookApiController::class, 'removeCapa'])->name('api.books.remove-capa');
});
