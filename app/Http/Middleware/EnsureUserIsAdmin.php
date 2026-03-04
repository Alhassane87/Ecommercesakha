<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if (! $user || ! method_exists($user, 'isAdmin') || ! $user->isAdmin()) {
            abort(403);
        }

        return $next($request);
    }
}
