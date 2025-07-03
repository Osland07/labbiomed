<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectBasedOnRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $user = auth()->user();
            
            // Jika user mengakses dashboard umum, arahkan ke dashboard sesuai role
            if ($request->routeIs('dashboard')) {
                if ($user->hasRole('Admin')) {
                    return redirect()->route('admin.dashboard');
                } elseif ($user->hasRole('Mahasiswa')) {
                    return redirect()->route('client.dashboard');
                } elseif ($user->hasRole('Dosen')) {
                    return redirect()->route('dosen.dashboard');
                } elseif ($user->hasRole('Laboran')) {
                    return redirect()->route('laboran.dashboard');
                } elseif ($user->hasRole('Koordinator Laboratorium')) {
                    return redirect()->route('koorlab.dashboard');
                }
            }
        }

        return $next($request);
    }
} 