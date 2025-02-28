<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ForumController;
use App\Http\Controllers\FileUploadController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/password/forgot', [AuthController::class, 'forgotPassword']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::post('/my-details', [AuthController::class, 'userDetails']);

    Route::post('/user/{id}', [AuthController::class, 'getProfile']);

    Route::post('/password/change', [AuthController::class, 'changePassword']);

    Route::post('/avatar/upload', [FileUploadController::class, 'uploadAvatar']);

    Route::delete('/avatar/delete', [FileUploadController::class, 'deleteAvatar']);

    Route::controller(ForumController::class)->group(function () {

        Route::get('/forum/home', 'getTopics')->name('forum.home');

        Route::post('/forum/upload', 'createTopic')->name('forum.upload.post');

        Route::get('/forum/my-topics', 'myTopics')->name('forum.my.topics');

        Route::get('/forum/categories', 'getCategories')->name('forum.categories');

        Route::get('/forum/topic/{id}', 'getTopic')->name('forum.topic.getTopic');
        Route::post('/forum/topic/{id}/comment', 'addComment')->name('forum.comment.hozzaadas');
        Route::post('/forum/topic/{id}/vote', 'voteTopic')->name('forum.topic.vote');
        Route::post('/forum/topic/{id}/modify', 'modifyTopic')->name('forum.topic.modfiy');
        
        Route::post('/forum/comment/{id}/vote', 'voteComment')->name('forum.comment.vote');
        Route::post('/forum/comment/{id}/modify', 'modifyComment')->name('forum.comment.modfiy');

        Route::delete('/forum/topic/delete/{id}', 'deleteTopic')->name('forum.topic.delete');
        Route::delete('/forum/comment/delete/{id}', 'deleteComment')->name('forum.comment.delete');
    });

    Route::middleware('admin')->group(function () {
        Route::delete('/forum/topic/admin/{id}', [ForumController::class, 'deleteAdminTopic'])->name('forum.topic.delete.admin');
        Route::delete('/forum/comment/admin/{id}', [ForumController::class, 'deleteAdminComment'])->name('forum.comment.delete.admin');
    });
});