<?php 
namespace App\Http\Controllers;

use App\Models\Users;
use App\Models\Category;
use App\Models\Topic;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;

class AdminController extends Controller 
{
    public function banUser(Request $request)
    {
        $user = Users::find($request->id);
        if ($user) {
            $user->is_banned = 1;
            $token = PersonalAccessToken::where('tokenable_id', $user->id)->first();
            if($token)
            {
                $token->delete();
            }
            $user->save();
            return response()->json(['message' => 'A felhasználó sikeresen letiltva!'], 200);
        }
        return response()->json(['message' => 'A felhasználó nem található'], 404);
    }

    public function unbanUser(Request $request)
    {
        $user = Users::find($request->id);
        if ($user) {
            $user->is_banned = 0;
            $user->save();
            return response()->json(['message' => 'A felhasználó letiltása sikeresen levéve!'], 200);
        }
        return response()->json(['message' => 'A felhasználó nem található'], 404);
    }

    public function deleteUser($id)
    {
        $user = Users::find($id);
        if ($user) {
            $token = PersonalAccessToken::where('tokenable_id', $user->id)->first();
            if($token)
            {
                $token->delete();
            }
            $user->delete();
            return response()->json(['message' => 'A felhasználó sikeresen törölve'], 200);
        }
        return response()->json(['message' => 'A felhasználó nem található'], 404);
    }

    public function deleteTopic($id)
    {
        $topic = Topic::find($id);
        if ($topic) {
            $topic->delete();
            return response()->json(['message' => 'A téma sikeresen törölve'], 200);
        }
        return response()->json(['message' => 'A téma nem található'], 404);
    }

    public function deleteComment($id)
    {
        $comment = Comment::find($id);
        if ($comment) {
            $comment->delete();
            return response()->json(['message' => 'A hozzászólás sikeresen törölve'], 200);
        }
        return response()->json(['message' => 'A hozzászólás nem található'], 404);
    }

    public function categoryUpload(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category = new Category();
        $category->category_name = $request->name;
        $category->save();

        return response()->json(['message' => 'A kategória sikeresen feltöltve'], 201);
    }

    public function makeAdmin(Request $request)
    {
        $user = Users::find($request->id);
        if ($user) {
            $user->is_admin = 1;
            $token = PersonalAccessToken::where('tokenable_id', $user->id)->first();
            if($token)
            {
                $token->delete();
            }
            $user->save();
            return response()->json(['message' => 'A felhasználó sikeresen admin lett!'], 200);
        }
        return response()->json(['message' => 'A felhasználó nem található'], 404);
    }

    public function revokeAdmin(Request $request)
    {
        $user = Users::find($request->id);
        if ($user) {
            $token = PersonalAccessToken::where('tokenable_id', $user->id)->first();
            if($token)
            {
                $token->delete();
            }
            $user->is_admin = 0;
            $user->save();
            return response()->json(['message' => 'Az admin jog sikeresen el lett véve!'], 200);
        }
        return response()->json(['message' => 'A felhasználó nem található'], 404);
    }

    public function getUsers(Request $request)
    {
        $request->validate([
            'name' => 'nullable|string',
        ]);
    
        $query = Users::select('id', 'name', 'avatar', 'email', 'is_admin', 'is_banned');
    
        if ($request->has('name') && $request->name) {
            $name = $request->name;
            $query->where(function($q) use ($name) {
                $q->where('name', 'like', '%' . $name . '%')
                  ->orWhere('email', 'like', '%' . $name . '%');
            });
        }
    
        $users = $query->paginate(10);
    
        return response()->json(['users' => $users], 200);
    }
}