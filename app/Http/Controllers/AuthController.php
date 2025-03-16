<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Users;
use App\Models\Category;
use App\Models\Topic;
use App\Models\Comment;
use App\Models\TopicVote;
use App\Models\CommentVote;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        $to = $user['email'];
        $subject = 'Üdvözlünk az oldalunkon!';
        $message = $this->getWelcomeEmailContent($user['name']);
        $headers = $this->getEmailHeaders();

        mail($to, $subject, $message, $headers);

        return response()->json(['message' => "Sikeres regisztráció!"], 200);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Hibás bejelentkezési adatok.'], 401);
        }

        $user = Auth::user();

        if  ($user->is_banned=="1")
        {
            return response()->json(['message' => 'A fiók ki lett tiltva.'], 401);
        }
        
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['token' => $token, 'user' => $user]);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Sikeres kijelentkezés']);
    }

    public function userDetails(Request $request)
    {
        $user = $request->user();

        $topics = Topic::where('user_id', $user->id)->get();
        $topicCount = $topics->count();

        $topicUpvotes = $topics->sum('upvotes');
        $topicDownvotes = $topics->sum('downvotes');

        $comments = Comment::where('user_id', $user->id)->get();
        $commentCount = $comments->count();

        $commentUpvotes = $comments->sum('upvotes');
        $commentDownvotes = $comments->sum('downvotes');

        $stats = [
            'topic_count' => $topicCount,
            'comment_count' => $commentCount,
            'topic_upvotes' => $topicUpvotes,
            'topic_downvotes' => $topicDownvotes,
            'comment_upvotes' => $commentUpvotes,
            'comment_downvotes' => $commentDownvotes,
            'total_upvotes' => $topicUpvotes + $commentUpvotes,
            'total_downvotes' => $topicDownvotes + $commentDownvotes,
            'total_votes_balance' => ($topicUpvotes + $commentUpvotes) - ($topicDownvotes + $commentDownvotes)
        ];

        $userData = $user->toArray();
        $userData['stats'] = $stats;
        
        return response()->json($userData);
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['message' => 'A jelenlegi jelszó nem megfelelő.'], 401);
        }

        $user->password = bcrypt($request->new_password);
        $user->save();

        return response()->json(['message' => 'A jelszó sikeresen megváltoztatva.'], 200);
    }


    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();

        $newPassword = bin2hex(random_bytes(5));
        $user->password = bcrypt($newPassword);
        $user->save();

        $to = $user->email;
        $subject = 'Új jelszó';
        $message = $this->getPasswordResetEmailContent($user->name, $newPassword);
        $headers = $this->getEmailHeaders();

        mail($to, $subject, $message, $headers);

        return response()->json(['message' => 'Az új jelszót elküldtük az e-mail címedre.'], 200);
    }

    public function getProfile(Request $request, $id)
    {
        $user = User::select('id', 'name', 'created_at', 'avatar', 'is_banned')->where('id', $id)->first();
    
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $topics = Topic::where('user_id', $user->id)->get();
        $topicCount = $topics->count();

        $topicUpvotes = $topics->sum('upvotes');
        $topicDownvotes = $topics->sum('downvotes');

        $comments = Comment::where('user_id', $user->id)->get();
        $commentCount = $comments->count();

        $commentUpvotes = $comments->sum('upvotes');
        $commentDownvotes = $comments->sum('downvotes');

        $stats = [
            'topic_count' => $topicCount,
            'comment_count' => $commentCount,
            'topic_upvotes' => $topicUpvotes,
            'topic_downvotes' => $topicDownvotes,
            'comment_upvotes' => $commentUpvotes,
            'comment_downvotes' => $commentDownvotes,
            'total_upvotes' => $topicUpvotes + $commentUpvotes,
            'total_downvotes' => $topicDownvotes + $commentDownvotes,
            'total_votes_balance' => ($topicUpvotes + $commentUpvotes) - ($topicDownvotes + $commentDownvotes)
        ];

        $userData = $user->toArray();
        $userData['stats'] = $stats;
        
        return response()->json($userData);
    }

    private function getPasswordResetEmailContent($name, $newPassword)
    {
        return "
            <html>
            <head>
                <title>Új jelszó</title>
            </head>
            <body>
                <h1>Kedves $name!</h1>
                <p>Az új jelszavad: <strong>$newPassword</strong></p>
                <p>Javasoljuk, hogy jelentkezz be, és változtasd meg a jelszavad a profilodban.</p>
                <p><strong>Az oldal csapata</strong></p>
            </body>
            </html>
        ";
    }

    private function getWelcomeEmailContent($name)
    {
        return "
            <html>
            <head>
                <title>Üdvözlünk!</title>
            </head>
            <body>
                <h1>Üdvözlünk, $name!</h1>
                <p>Köszönjük, hogy regisztráltál az oldalunkra. Örömmel látunk a közösségünkben!</p>
                <p>Kellemes időtöltést kívánunk!</p>
                <p><strong>Az oldal csapata</strong></p>
            </body>
            </html>
        ";
    }

    private function getEmailHeaders()
    {
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= 'From: FreeForum <noreply@FreeForum.com>' . "\r\n";
        return $headers;
    }
}