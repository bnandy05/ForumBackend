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
date_default_timezone_set("europe/budapest");

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

        $topicVotes = TopicVote::whereIn('topic_id', $topics->pluck('id'))
            ->where('user_id', '!=', $user->id)
            ->get();
        $topicUpvotes = $topicVotes->where('vote_type', 'up')->count();
        $topicDownvotes = $topicVotes->where('vote_type', 'down')->count();

        $comments = Comment::where('user_id', $user->id)->get();
        $commentCount = $comments->count();

        $commentVotes = CommentVote::whereIn('comment_id', $comments->pluck('id'))
            ->where('user_id', '!=', $user->id)
            ->get();
        $commentUpvotes = $commentVotes->where('vote_type', 'up')->count();
        $commentDownvotes = $commentVotes->where('vote_type', 'down')->count();

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

        $userData = [
            'id' => $user->id,
            'name' => $user->name,
            'avatar' => $user->avatar,
            'email' => $user->email,
            'created_at' => $user->created_at,
            'stats' => $stats
        ];
        
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
            return response()->json(['message' => 'A jelenlegi jelszó nem megfelelő.'], 403);
        }

        $user->password = bcrypt($request->new_password);
        $user->save();
        
        $changeDate = date('Y-m-d H:i:s');
        
        $to = $user->email;
        $subject = "Jelszóváltoztatás megerősítése - FreeForum";
        $message = $this->getPasswordChangedEmailContent($user->name, $changeDate);
        $headers = $this->getEmailHeaders();
        
        mail($to, $subject, $message, $headers);

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
            return response()->json(['error' => 'Felhasználó nem található'], 404);
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

    private function getEmailStyle()
    {
        return "
            <style>
                body {
                    font-family: Arial, sans-serif;
                    line-height: 1.6;
                    color: #2c2c2c;
                    max-width: 600px;
                    margin: 0 auto;
                    padding: 20px;
                    background-color: #f5ebe0;
                } 
                .email-container {
                    background-color: #e3dac9;
                    border-radius: 8px;
                    padding: 30px;
                    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
                    border: 1px solid #d6c4b2;
                }
                .header {
                    text-align: center;
                    padding-bottom: 20px;
                    border-bottom: 2px solid #b08968;
                    margin-bottom: 25px;
                }
                .logo {
                    max-width: 200px;
                    margin-bottom: 15px;
                }
                h1 {
                    color: #3a3a3a;
                    font-size: 22px;
                    margin-top: 0;
                    margin-bottom: 20px;
                }
                .info-box {
                    background-color: #f5ebe0;
                    border: 1px solid #d6c4b2;
                    border-radius: 5px;
                    padding: 15px;
                    margin: 20px 0;
                    text-align: center;
                }
                .highlight-text {
                    font-weight: bold;
                    font-size: 18px;
                    color: #b08968;
                    letter-spacing: 1px;
                }
                .advice {
                    background-color: rgba(176, 137, 104, 0.1);
                    border-left: 4px solid #b08968;
                    padding: 12px;
                    margin: 20px 0;
                }
                .footer {
                    margin-top: 30px;
                    text-align: center;
                    font-size: 14px;
                    color: #5a5a5a;
                    padding-top: 20px;
                    border-top: 1px solid #d6c4b2;
                }
                .button {
                    display: inline-block;
                    background-color: #b08968;
                    color: #eeeeee !important; /* Explicit szín Gmailhez */
                    text-decoration: none !important; /* Link aláhúzás eltávolítása */
                    padding: 10px 20px;
                    border-radius: 5px;
                    margin-top: 20px;
                    font-weight: bold;
                    border: none; /* Biztonság kedvéért */
                }
                .button:hover {
                    background-color: #8b5e3b;
                }
                /* Gmail-specifikus biztosíték */
                a.button {
                    color: #eeeeee !important; /* Dupla biztosítás */
                    text-decoration: none !important;
                }
                .welcome-message {
                    background-color: rgba(176, 137, 104, 0.1);
                    border-radius: 5px;
                    padding: 20px;
                    margin: 20px 0;
                    text-align: center;
                    font-size: 18px;
                    border: 1px solid rgba(176, 137, 104, 0.3);
                }
                .features {
                    background-color: #f5ebe0;
                    border-radius: 5px;
                    padding: 15px;
                    margin: 20px 0;
                    border: 1px solid #d6c4b2;
                }
                .features h2 {
                    font-size: 18px;
                    color: #b08968;
                    margin-top: 0;
                    margin-bottom: 12px;
                }
                .features ul {
                    padding-left: 20px;
                    margin-bottom: 5px;
                }
                .features li {
                    margin-bottom: 8px;
                }
                @media (max-width: 576px) {
                    .email-container {
                        padding: 20px 15px;
                    }
                    h1 {
                        font-size: 20px;
                    }
                    .highlight-text {
                        font-size: 16px;
                    }
                    .welcome-message {
                        font-size: 16px;
                        padding: 15px;
                    }
                }
            </style>
        ";
    }

    private function getEmailHeader($title)
    {
        return "
            <html>
            <head>
                <title>$title - FreeForum</title>
                <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                " . $this->getEmailStyle() . "
            </head>
            <body>
                <div class='email-container'>
                    <div class='header'>
                        <img src='https://berenandor.moriczcloud.hu/storage/logo.png' alt='FreeForum' class='logo'>
                    </div>
        ";
    }

    private function getEmailFooter()
    {
        return "
                    <div class='footer'>
                        <p><strong>Üdvözlettel,<br>A FreeForum csapata</strong></p>
                    </div>
                </div>
            </body>
            </html>
        ";
    }

    private function getPasswordResetEmailContent($name, $newPassword)
    {
        $content = $this->getEmailHeader("Jelszó visszaállítás");
        
        $content .= "
                    <h1>Kedves $name!</h1>
                    
                    <p>Jelszó-visszaállítási kérelmet kaptunk a fiókodhoz. Az új jelszavad elkészült, amellyel mostantól beléphetsz fiókodba.</p>
                    
                    <div class='info-box'>
                        <p>Az új jelszavad:</p>
                        <p class='highlight-text'>$newPassword</p>
                    </div>
                    
                    <div class='advice'>
                        <p><strong>Fontos:</strong> Biztonsági okokból javasoljuk, hogy bejelentkezés után azonnal változtasd meg ezt az ideiglenes jelszót a profilodban.</p>
                    </div>
                    
                    <p>Ha nem te kérted a jelszó visszaállítását, kérjük, azonnal vedd fel a kapcsolatot ügyfélszolgálatunkkal.</p>
                    
                    <center><a href='https://berenandor.moriczcloud.hu' class='button'>Bejelentkezés</a></center>
        ";
        
        $content .= $this->getEmailFooter();
        
        return $content;
    }

    private function getWelcomeEmailContent($name)
    {
        $content = $this->getEmailHeader("Üdvözlünk a FreeForum közösségében!");
        
        $content .= "
                    <h1>Köszöntünk a FreeForum közösségében, $name!</h1>
                    
                    <div class='welcome-message'>
                        <p>Örömmel üdvözlünk a fórumunkon! Fiókod sikeresen létrejött, és már most elkezdheted felfedezni az oldalt.</p>
                    </div>
                    
                    <p>A FreeForum egy hely, ahol szabadon kifejezheted gondolataidat, megoszthatsz tartalmakat és kapcsolatba léphetsz hasonló érdeklődésű emberekkel.</p>
                    
                    <div class='features'>
                        <h2>Mit tehetsz most?</h2>
                        <ul>
                            <li>Személyre szabhatod a profilodat</li>
                            <li>Felfedezheted a különböző témaköröket</li>
                            <li>Részt vehetsz izgalmas beszélgetésekben</li>
                            <li>Megoszthatod saját gondolataidat és kérdezhetsz</li>
                        </ul>
                    </div>
                    
                    <center><a href='https://berenandor.moriczcloud.hu' class='button'>Felfedezés indítása</a></center>
        ";
        
        $content .= $this->getEmailFooter();
        
        return $content;
    }

    private function getPasswordChangedEmailContent($name, $changeDate)
    {
        $content = $this->getEmailHeader("Jelszóváltoztatás megerősítése");
        
        $content .= "
                    <h1>Kedves $name!</h1>
                    
                    <p>Ezúton értesítünk, hogy fiókod jelszava sikeresen megváltozott.</p>
                    
                    <div class='info-box'>
                        <p>A jelszóváltoztatás időpontja:</p>
                        <p class='highlight-text'>$changeDate</p>
                    </div>
                    
                    <div class='advice'>
                        <p><strong>Fontos:</strong> Ha nem te változtattad meg a jelszavad, azonnal vedd fel a kapcsolatot ügyfélszolgálatunkkal és állítsd vissza a jelszavad az Elfelejtett jelszó funkció segítségével.</p>
                    </div>
                    
                    <p>Ez az értesítés kizárólag biztonsági célból lett elküldve, hogy tájékoztassunk a fiókodban történt fontos változásról.</p>
                    
                    <center><a href='https://berenandor.moriczcloud.hu/frontend/#/forgot-password' class='button'>Biztonsági beállítások megtekintése</a></center>
        ";
        
        $content .= $this->getEmailFooter();
        
        return $content;
    }

    private function getEmailHeaders()
    {
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= 'From: FreeForum <noreply@freeforum.com>' . "\r\n";
        return $headers;
    }
}