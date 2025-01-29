<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Http\Middleware\Authenticate;
class SanctumTestController extends Controller
{
    public function showLoginForm()
    {
        return view('sanctum-test.login');
    }
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'email' => ['A megadott adatok helytelenek.'],
            ]);
        }
        // Create token and authenticate the user
        $token = $user->createToken('test-token')->plainTextToken;
        Auth::guard('web')->login($user);
        session(['api_token' => $token, 'user' => $user]);
        return redirect()->route('dashboard');
    }
    public function dashboard()
    {
        if (!Auth::guard('web')->check()) {
            return redirect()->route('login');
        }
        return view('sanctum-test.dashboard');
    }
    public function testAuth()
    {
        return response()->json([
            'message' => 'You are authenticated!',
            'user' => auth()->user()
        ]);
    }
    public function logout(Request $request)
    {
        // Revoke all tokens if they exist
        if ($request->user()) {
            $request->user()->tokens()->delete();
        }
        
        // Clear the web guard session
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login');
    }
}