<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CalonMahasiswaMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (Auth::user()->role !== 'mahasiswa') {
            return redirect()->route('home')->with('error', 'Akses ditolak.');
        }

        return $next($request);
    }
}
