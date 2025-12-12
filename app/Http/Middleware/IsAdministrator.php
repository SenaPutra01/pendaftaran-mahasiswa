<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsAdministrator
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if ($user->role === 'administrator') {
            return $next($request);
        }

        return redirect()->route('dashboard')
            ->with('error', 'Anda tidak memiliki akses ke halaman admin.');
    }
}
