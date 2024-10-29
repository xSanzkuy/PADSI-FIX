<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class ResetPasswordController extends Controller
{
    /**
     * Show the reset password form.
     *
     * @param  string|null  $token
     * @return \Illuminate\View\View
     */
    public function showResetForm($token = null)
    {
        return view('auth.passwords.reset')->with(['token' => $token]);
    }

    /**
     * Handle the reset password request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reset(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
            'token' => 'required'
        ]);

        Log::info('Reset password request received', $request->all());

        // Temukan record reset password berdasarkan email
        $resetRecord = DB::table('password_reset_tokens')
        ->where('email', $request->email)
        ->first();
    
    if (!$resetRecord || !Hash::check($request->token, $resetRecord->token)) {
        Log::error('Invalid or expired reset token for email: ' . $request->email);
        return back()->withErrors(['email' => 'Token reset password tidak valid atau telah kadaluarsa.']);
    }

        // Temukan user berdasarkan email
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            Log::error('Email not registered: ' . $request->email);
            return back()->withErrors(['email' => 'Email tidak terdaftar.']);
        }

        // Reset password dan hapus record token reset password
        $user->password = Hash::make($request->password);
        $user->save();
        Log::info('Password reset successful for email: ' . $request->email);

        // Hapus token setelah berhasil mereset password
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route('login')->with('status', 'Password berhasil direset!');
    }
}
