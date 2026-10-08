<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleAdminPetugas
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Hanya admin dan petugas yang boleh mengakses
        if (!in_array(Auth::user()->role, ['admin', 'petugas'])) {
            return redirect()->route('public.home')->with('error', 'Akses ditolak. Anda tidak memiliki izin untuk mengakses panel admin.');
        }

        return $next($request);
    }
}
