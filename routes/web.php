<?php
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;

Route::get('/route-endpoints', function () {
    return response()->json(['message' => 'API működik!']);
})->where('any', '^(?!api).*$');