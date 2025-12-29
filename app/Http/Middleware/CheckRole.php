<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Cek jika admin tidak aktif
        if ($user->role === 'admin' && ($user->is_active === false)) {
            Auth::logout();
            return redirect()->route('login')
                ->with('error', 'Akun Anda telah dinonaktifkan.');
        }

        foreach ($roles as $role) {
            if ($user->role === $role) {
                return $next($request);
            }
        }

        // Redirect based on role
        return match ($user->role) {
            'client' => redirect()->route('client.dashboard'),
            'admin' => redirect()->route('admin.dashboard'),
            'owner' => redirect()->route('owner.dashboard'),
            default => redirect('/'),
        };
    }
}