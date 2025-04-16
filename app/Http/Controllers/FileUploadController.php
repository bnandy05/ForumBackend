<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FileUpload;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
date_default_timezone_set("europe/budapest");

class FileUploadController extends Controller
{
    public function uploadAvatar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => "A fájl nem lehet nagyobb 2MB-nál"], 422);
        }

        $user = $request->user();

        if ($user->avatar && $user->avatar!="Default.png") {
            Storage::disk('public')->delete($user->avatar);
        }

        $file = $request->file('file');
        $path = $file->store('avatars', 'public');

        $user->avatar = $path;
        $user->save();

        $fileUpload = FileUpload::create([
            'filename' => $file->getClientOriginalName(),
            'path' => $path,
            'user_id' => $user->id,
        ]);

        return response()->json([
            'message' => 'Avatar sikeresen feltöltve',
            'avatar' => asset('storage/' . $path),
            'file_upload' => $fileUpload,
            'url' => $path
        ], 201);
    }
}