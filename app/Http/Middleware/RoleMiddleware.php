<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = Auth::user();

        // Log pertama untuk memastikan middleware dijalankan
        \Log::info('RoleMiddleware is triggered');

        // Log kedua untuk melihat role yang dimiliki user
        if ($user) {
            \Log::info('User roles:', $user->roles->pluck('nama_role')->toArray());
            \Log::info('Required roles for access:', $roles);
        } else {
            \Log::info('No authenticated user found.');
        }

        // Berikan akses penuh jika pengguna adalah Superadmin
        if ($user && $user->roles()->where('nama_role', 'Superadmin')->exists()) {
            \Log::info('Access granted for Superadmin.');
            return $next($request); // Akses tanpa batas untuk Superadmin
        }

        // Cek jika user memiliki salah satu role yang diperbolehkan
        if ($user && $user->roles()->whereIn('nama_role', $roles)->exists()) {
            \Log::info('Access granted based on role');
            return $next($request);
        }

        // Jika role tidak sesuai, arahkan ke dashboard atau tampilkan pesan error
        \Log::info('Access denied: user does not have required role');
        return redirect('/dashboard')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
    }
}
