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
date_default_timezone_set("europe/budapest");

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

            $to = $user->email;
            $subject = 'Fiók kitiltása';
            $message = $this->getBanUserEmailContent($user->name);
            $headers = $this->getEmailHeaders();
            mail($to, $subject, $message, $headers);

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

            $to = $user->email;
            $subject = 'Kitiltás feloldása';
            $message = $this->getUnbanUserEmailContent($user->name);
            $headers = $this->getEmailHeaders();
            mail($to, $subject, $message, $headers);

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

            $to = $user->email;
            $subject = 'Fiók törlése';
            $message = $this->getDeleteUserEmailContent($user->name);
            $headers = $this->getEmailHeaders();
            mail($to, $subject, $message, $headers);

            return response()->json(['message' => 'A felhasználó sikeresen törölve'], 200);
        }
        return response()->json(['message' => 'A felhasználó nem található'], 404);
    }

    public function deleteTopic($id)
    {
        $topic = Topic::find($id);
        if ($topic) {

            $user = Users::find($topic->user_id);

            $topic->delete();

            $to = $user->email;
            $subject = 'Téma törlése';
            $message = $this->getDeleteTopicEmailContent($user->name, $topic->title);
            $headers = $this->getEmailHeaders();
            mail($to, $subject, $message, $headers);

            return response()->json(['message' => 'A téma sikeresen törölve'], 200);
        }
        return response()->json(['message' => 'A téma nem található'], 404);
    }

    public function deleteComment($id)
    {
        $comment = Comment::find($id);
        if ($comment) {

            $user = Users::find($comment->user_id);

            $comment->delete();

            $to = $user->email;
            $subject = 'Hozzászólás törlése';
            $message = $this->getDeleteCommentEmailContent($user->name, $comment->content);
            $headers = $this->getEmailHeaders();
            mail($to, $subject, $message, $headers);

            return response()->json(['message' => 'A hozzászólás sikeresen törölve'], 200);
        }
        return response()->json(['message' => 'A hozzászólás nem található'], 404);
    }

    public function uploadCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category = new Category();
        $category->category_name = $request->name;
        $category->save();

        return response()->json(['message' => 'A kategória sikeresen feltöltve'], 201);
    }

    public function getCategories()
    {
        $categories = Category::where('permanent','0')->get();
        
        return response()->json([
            'categories' => $categories
        ]);
    }

    public function deleteCategory($id)
    {
        $category = Category::find($id);
        
        if (!$category) {
            return response()->json(['message' => 'A kategória nem található'], 404);
        }
        
        if($category->permanent == 0)
        {
            $category->delete();
        }
        else
        {
            return response()->json(['message' => 'Nem törölhetsz állandó kategóriákat'], 404);
        }
        
        
        return response()->json(['message' => 'A kategória sikeresen törölve']);
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

            $to = $user->email;
            $subject = 'Adminisztrátori jog megadása';
            $message = $this->getMakeAdminEmailContent($user->name);
            $headers = $this->getEmailHeaders();
            mail($to, $subject, $message, $headers);

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
            'banned' => 'nullable|integer',
            'admin' => 'nullable|integer'
        ]);

        $query = Users::select('id', 'name', 'avatar', 'email', 'is_admin', 'is_banned');    

        if ($request->filled('banned')) {
            $query->where('is_banned', $request->banned);
        }
        
        if ($request->filled('admin')) {
            $query->where('is_admin', $request->admin);
        }

        if ($request->filled('name') && !empty($request->name)) { 
            $name = $request->name;
            $query->where(function ($q) use ($name) {
                $q->where('name', 'like', "%$name%")
                  ->orWhere('email', 'like', "%$name%");
            });
        }    

        $users = $query->paginate(10);

        return response()->json(['users' => $users], 200);
    }

    public function getUser($id)
    {
        $query = Users::find($id);    

        return response()->json(['user' => $query], 200);
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

    private function getMakeAdminEmailContent($name)
    {
        $content = $this->getEmailHeader("Adminisztrátori jog megadása");
        
        $content .= "
                    <h1>Kedves $name!</h1>
                    
                    <p>Örömmel értesítünk, hogy a FreeForum közösségében adminisztrátori jogokat kaptál!</p>
                    
                    <div class='info-box'>
                        <p>Új szerepköröd:</p>
                        <p class='highlight-text'>Adminisztrátor</p>
                    </div>
                    
                    <p>Ezzel a jogosultsággal mostantól hozzáférésed van a fórum adminisztrációs funkcióihoz, például felhasználók kezelésére, témák moderálására és kategóriák szerkesztésére.</p>
                    
                    <div class='advice'>
                        <p><strong>Fontos:</strong> Kérjük, hogy az új jogosultságaiddal felelősségteljesen élj, és tartsd be a FreeForum irányelveit.</p>
                    </div>
                    
                    <center><a href='https://berenandor.moriczcloud.hu/frontend/#/admin/home' class='button'>Adminisztrációs panel megtekintése</a></center>
        ";
        
        $content .= $this->getEmailFooter();
        
        return $content;
    }

    private function getRevokeAdminEmailContent($name)
    {
        $content = $this->getEmailHeader("Adminisztrátori jog visszavonása");
        
        $content .= "
                    <h1>Kedves $name!</h1>
                    
                    <p>Ezúton értesítünk, hogy adminisztrátori jogaid visszavonásra kerültek a FreeForum közösségében.</p>
                    
                    <div class='info-box'>
                        <p>Új szerepköröd:</p>
                        <p class='highlight-text'>Felhasználó</p>
                    </div>
                    
                    <p>Továbbra is aktív tagja maradhatsz a közösségnek, és részt vehetsz a fórum beszélgetéseiben.</p>
                    
                    <div class='advice'>
                        <p><strong>Fontos:</strong> Ha úgy gondolod, hogy ez tévedésből történt, kérjük, vedd fel velünk a kapcsolatot mielőbb!</p>
                    </div>
                    
                    <center><a href='https://berenandor.moriczcloud.hu' class='button'>Fórum megtekintése</a></center>
        ";
        
        $content .= $this->getEmailFooter();
        
        return $content;
    }

    private function getDeleteUserEmailContent($name)
    {
        $content = $this->getEmailHeader("Fiók törlése");
        
        $content .= "
                    <h1>Kedves $name!</h1>
                    
                    <p>Ezúton értesítünk, hogy a FreeForum fiókod törlésre került.</p>
                    
                    <div class='info-box'>
                        <p>Fiók állapota:</p>
                        <p class='highlight-text'>Törölve</p>
                    </div>
                    
                    <p>Ezzel a fiókkal többé nem tudsz bejelentkezni, és minden kapcsolódó adatod eltávolításra került rendszerünkből.</p>
                    
                    <div class='advice'>
                        <p><strong>Fontos:</strong> Ha új fiókot szeretnél létrehozni, bármikor regisztrálhatsz újra a FreeForumon.</p>
                    </div>
                    
                    <center><a href='https://berenandor.moriczcloud.hu/frontend/#/register' class='button'>Új fiók regisztrálása</a></center>
        ";
        
        $content .= $this->getEmailFooter();
        
        return $content;
    }

    private function getBanUserEmailContent($name)
    {
        $content = $this->getEmailHeader("Fiók kitiltása");
        
        $content .= "
                    <h1>Kedves $name!</h1>
                    
                    <p>Sajnálattal értesítünk, hogy fiókodat kitiltottuk a FreeForum közösségéből a szabályzat megsértése miatt.</p>
                    
                    <div class='info-box'>
                        <p>Fiók állapota:</p>
                        <p class='highlight-text'>Kitiltva</p>
                    </div>
                    
                    <p>A kitiltással nem tudsz bejelentkezni, hozzászólni vagy új tartalmat létrehozni.</p>
                    
                    <div class='advice'>
                        <p><strong>Fontos:</strong> Kérjük, ismerkedj meg a FreeForum közösségi irányelveivel, hogy elkerüld a hasonló helyzeteket a jövőben.</p>
                    </div>
        ";
        
        $content .= $this->getEmailFooter();
        
        return $content;
    }

    private function getUnbanUserEmailContent($name)
    {
        $content = $this->getEmailHeader("Kitiltás feloldása");
        
        $content .= "
                    <h1>Kedves $name!</h1>
                    
                    <p>Örömmel értesítünk, hogy fiókod kitiltása feloldásra került a FreeForum közösségében!</p>
                    
                    <div class='info-box'>
                        <p>Fiók állapota:</p>
                        <p class='highlight-text'>Aktív</p>
                    </div>
                    
                    <p>Mostantól újra bejelentkezhetsz, hozzászólhatsz és részt vehetsz a fórum életében. Köszönjük, hogy továbbra is a közösségünk tagja vagy!</p>
                    
                    <div class='advice'>
                        <p><strong>Fontos:</strong> Kérjük, tartsd be a FreeForum közösségi irányelveit, hogy zavartalanul élvezhesd a fórumot.</p>
                    </div>
                    
                    <center><a href='https://berenandor.moriczcloud.hu' class='button'>Bejelentkezés</a></center>
        ";
        
        $content .= $this->getEmailFooter();
        
        return $content;
    }

    private function getDeleteTopicEmailContent($name, $topicTitle)
    {
        $content = $this->getEmailHeader("Téma törlése");
        
        $content .= "
                    <h1>Kedves $name!</h1>
                    
                    <p>Ezúton értesítünk, hogy az általad létrehozott téma törlésre került a FreeForum közösségében.</p>
                    
                    <div class='info-box'>
                        <p>Törölt téma címe:</p>
                        <p class='highlight-text'>$topicTitle</p>
                    </div>
                    
                    <p>A téma törlésére a közösségi irányelvek megsértése vagy moderációs döntés miatt kerülhetett sor.</p>
                    
                    <div class='advice'>
                        <p><strong>Fontos:</strong> Kérjük, ismerkedj meg a FreeForum irányelveivel, hogy a jövőben elkerüld a hasonló helyzeteket.</p>
                    </div>
                    
                    <center><a href='https://berenandor.moriczcloud.hu' class='button'>Fórum megtekintése</a></center>
        ";
        
        $content .= $this->getEmailFooter();
        
        return $content;
    }

    private function getDeleteCommentEmailContent($name, $commentContent)
    {
        $content = $this->getEmailHeader("Hozzászólás törlése");
        
        $content .= "
                    <h1>Kedves $name!</h1>
                    
                    <p>Ezúton értesítünk, hogy egy hozzászólásod törlésre került a FreeForum közösségében.</p>
                    
                    <div class='info-box'>
                        <p>Törölt hozzászólás részlete:</p>
                        <p class='highlight-text'>" . substr($commentContent, 0, 50) . (strlen($commentContent) > 50 ? '...' : '') . "</p>
                    </div>
                    
                    <p>A hozzászólás törlésére a közösségi irányelvek megsértése vagy moderációs döntés miatt kerülhetett sor.</p>
                    
                    <div class='advice'>
                        <p><strong>Fontos:</strong> Kérjük, tartsd be a FreeForum irányelveit, hogy a jövőben elkerüld a hasonló helyzeteket.</p>
                    </div>
                    
                    <center><a href='https://berenandor.moriczcloud.hu' class='button'>Fórum megtekintése</a></center>
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