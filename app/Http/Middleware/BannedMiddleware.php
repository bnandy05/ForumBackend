<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BannedMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && !(Auth::user()->is_banned)) {
            return $next($request);
        }

        return response()->json(['message' => 'A fiókod ki lett tiltva'], 403);
    }
}