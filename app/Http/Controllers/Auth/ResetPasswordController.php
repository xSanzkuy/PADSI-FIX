<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class ResetPasswordController extends Controller
{
    use ResetsPasswords;

    protected function reset(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
            'token' => 'required'
        ]);

        // Temukan user berdasarkan email
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email tidak terdaftar.']);
        }

        // Periksa token dan reset password
        // Anda mungkin perlu menyesuaikan ini untuk penggunaan token
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('login.form')->with('status', 'Password berhasil direset!');
    }
}

