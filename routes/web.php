<?php
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;

Route::get('frontend/{any?}', function () {
    return file_get_contents(public_path('frontend/index.html'));
})->where('any', '.*');

