<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        $user = Auth::user();

        // Berikan akses penuh jika pengguna adalah Superadmin
        if ($user && $user->roles()->where('nama_role', 'Superadmin')->exists()) {
            return $next($request); // Akses tanpa batas untuk Superadmin
        }

        // Batasi akses berdasarkan role lainnya
        if ($user && $user->roles()->where('nama_role', $role)->exists()) {
            return $next($request);
        }

        // Jika role tidak sesuai, arahkan ke halaman lain atau tampilkan pesan error
        return redirect('/dashboard')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
    }
}
