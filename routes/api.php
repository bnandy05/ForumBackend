<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ForumController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::controller(ForumController::class)->group(function () {

        Route::get('/forum/fooldal', 'index')->name('forum.fooldal');

        Route::get('/forum/feltoltes', 'createTopic')->name('forum.feltoltes');
        Route::post('/forum/feltoltes', 'createTopic')->name('forum.feltoltes.post');

        Route::get('/forum/topik/{id}', 'show')->name('forum.topik.show');
        Route::post('/forum/topik/{id}/komment', 'addComment')->name('forum.komment.hozzaadas');
        Route::post('/forum/topik/{id}/vote', 'voteTopic')->name('forum.topik.vote');
        
        Route::post('/forum/komment/{id}/vote', 'voteComment')->name('forum.komment.vote');
        Route::delete('/forum/topik/{id}', 'deleteTopic')->name('forum.topik.torles');
        Route::delete('/forum/komment/{id}', 'deleteComment')->name('forum.komment.torles');
    });

    Route::middleware('admin')->group(function () {
        Route::delete('/forum/topik/admin/{id}', [ForumController::class, 'deleteAdminTopic'])->name('forum.topik.torles.admin');
        Route::delete('/forum/komment/admin/{id}', [ForumController::class, 'deleteAdminComment'])->name('forum.komment.torles.admin');
    });
});