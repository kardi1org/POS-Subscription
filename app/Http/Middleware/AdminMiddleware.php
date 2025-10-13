<?php

// namespace App\Http\Middleware;

// use Closure;
// use Illuminate\Support\Facades\Auth;

// class AdminMiddleware
// {
//     public function handle($request, Closure $next)
//     {
//         if (!Auth::check() || Auth::user()->is_admin !== 1) {
//             return redirect('/home')->with('error', 'Anda tidak punya akses ke halaman ini.');
//         }
//         return $next($request);
//     }

// }

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle($request, Closure $next)
    {
        if (Auth::check() && Auth::user()->role === 'admin') {
            return $next($request);
        }

        return redirect('/home')->with('error', 'Anda tidak punya akses ke halaman admin.');
    }
}
