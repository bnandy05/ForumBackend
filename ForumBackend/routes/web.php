<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ForumController;
use App\Http\Controllers\AuthController;

Route::middleware('sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['auth'])->group(function () {
    Route::controller(ForumController::class)->group(function () {
        Route::get('/', 'index')->name('forum.fooldal');
        
        Route::get('/feltoltes', 'createTopic')->name('forum.feltoltes');
        Route::post('/feltoltes', 'createTopic')->name('forum.feltoltes.post');
        
        Route::get('/topik/{id}', 'show')->name('forum.topik.show');
        
        Route::post('/topik/{id}/komment', 'addComment')->name('forum.komment.hozzaadas');
        
        Route::post('/topik/{id}/vote', 'voteTopic')->name('forum.topik.vote');
        
        Route::post('/komment/{id}/vote', 'voteComment')->name('forum.komment.vote');

        Route::delete('/topik/{id}', 'deleteTopic')->name('forum.topik.torles');

        Route::delete('/komment/{id}', 'deleteComment')->name('forum.komment.torles');

        Route::middleware(['auth', 'admin'])->group(function () {
            Route::delete('/topik/admin/{id}', 'deleteAdminTopic')->name('forum.topik.torles');
            Route::delete('/komment/admin/{id}', 'deleteAdminComment')->name('forum.komment.torles');
        });   
    });
});
