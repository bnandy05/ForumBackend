<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ForumController;
use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\AdminController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/password/forgot', [AuthController::class, 'forgotPassword']);

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::middleware('auth:sanctum', 'not_banned')->group(function () {
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
        Route::controller(AdminController::class)->group(function () {
            Route::post('/forum/admin/user/ban', 'banUser')->name('admin.user.ban');
            Route::post('/forum/admin/user/unban', 'unbanUser')->name('admin.user.unban');

            Route::post('/forum/admin/user/admin/give', 'makeAdmin')->name('admin.user.give.admin');
            Route::post('/forum/admin/user/admin/revoke', 'revokeAdmin')->name('admin.user.revoke.admin');

            Route::delete('/forum/admin/user/delete/{id}', 'deleteUser')->name('admin.user.delete');

            Route::delete('/forum/admin/topic/delete/{id}', 'deleteTopic')->name('admin.topic.delete');
            Route::delete('/forum/admin/comment/delete/{id}', 'deleteComment')->name('admin.comment.delete');

            Route::post('/forum/admin/categories/get', 'getCategories')->name('admin.category.get');
            Route::post('/forum/admin/category/upload', 'uploadCategory')->name('admin.category.upload');
            Route::delete('/forum/admin/category/delete/{id}', 'deleteCategory')->name('admin.category.delete');

            Route::post('/forum/admin/users/get', 'getUsers')->name('admin.users.get');

            Route::post('/forum/admin/users/get/{id}', 'getUser')->name('admin.user.get');
        });
    });
});