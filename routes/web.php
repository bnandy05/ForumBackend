<?php
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;

Route::get('/route-endpoint', function () {
    return file_get_contents(public_path('index.html'));
})->where('any', '.*');

