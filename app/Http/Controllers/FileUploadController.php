<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FileUpload;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class FileUploadController extends Controller
{
    public function uploadAvatar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = $request->user();

        if ($user->avatar) {
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
        ], 201);
    }

    public function deleteAvatar($userId)
    {
        $user = User::find($userId);

        if (!$user) {
            return response()->json(['message' => 'Nem található a felhasználó'], 404);
        }

        if ($user->avatar) {
            
            if (Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            $user->avatar = null;
            $user->save();

            FileUpload::where('user_id', $userId)->delete();

            return response()->json(['message' => 'Avatar sikeresen törölve']);
        }

        return response()->json(['message' => 'Nincs avatar'], 404);
    }
}