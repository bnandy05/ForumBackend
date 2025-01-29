<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ZeneController;
use App\Http\Controllers\TestAPIController;
use App\Http\Controllers\ForumController;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('welcome');
});

//Innentől route controller

Route::middleware('sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', [AuthController::class, 'Regisztralas']);
Route::post('/login', [AuthController::class, 'Login']);
Route::post('/logout', [AuthController::class, 'Logout']);

Route::middleware(['auth'])->group(function () {
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

        Route::middleware(['auth', 'admin'])->group(function () {
            Route::delete('/forum/topik/admin/{id}', 'deleteAdminTopic')->name('forum.topik.torles');
            Route::delete('/forum/komment/admin/{id}', 'deleteAdminComment')->name('forum.komment.torles');
        });  
    });
});
