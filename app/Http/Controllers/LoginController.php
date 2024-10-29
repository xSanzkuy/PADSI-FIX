<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log; 
use Illuminate\Support\Facades\Password;

class LoginController extends Controller
{
    public function index(Request $request)
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);
    
        $loginInput = $request->input('username');
        // Tentukan apakah input adalah email atau username
        $fieldType = filter_var($loginInput, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
    
        // Coba login menggunakan kolom yang sesuai (username atau email)
        $user = User::where($fieldType, $loginInput)->first();
    
        if ($user && Hash::check($request->input('password'), $user->password)) {
            Auth::login($user);
            $pegawai = $user->pegawai;
    
            // Data session yang tetap sama
            $ses_data = [
                'username' => $user->username,
                'role' => $user->roles()->pluck('nama_role')->first() ?? 'pegawai',
            ];
    
            if ($pegawai) {
                $ses_data += [
                    'no_pegawai' => $pegawai->no_pegawai,
                    'foto' => '/images/pegawai/' . ($pegawai->foto ?? 'pegawai.svg'),
                    'email' => $pegawai->email,
                    'no_hp' => $pegawai->no_hp,
                    'nama_depan' => explode(' ', $pegawai->nama)[0],
                    'nama_blkng' => explode(' ', $pegawai->nama, 2)[1] ?? '',
                ];
            } else {
                $ses_data['foto'] = '/images/pegawai/pegawai.svg';
            }
    
            $request->session()->put($ses_data);
    
            // Redirect berdasarkan role
            if (session('role') === 'pegawai') {
                return redirect()->route('transactions.index');
            } else {
                return redirect()->route('dashboard');
            }
        } else {
            // Jika login gagal, kembalikan dengan pesan error
            return redirect()->to('/login')->with('error', 'Username/Email atau Password salah')->withInput();
        }
    }
    
    public function showLinkRequestForm()
    {
        return view('auth.passwords.email');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $response = Password::sendResetLink($request->only('email'));

        return $response == Password::RESET_LINK_SENT
            ? back()->with('status', __('Password reset link dikirim ke email anda.'))
            : back()->withErrors(['email' => __($response)]);
    }

    public function showResetForm(Request $request, $token = null)
    {
        return view('auth.passwords.reset')->with(['token' => $token, 'email' => $request->email]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
            'token' => 'required',
        ]);

        $response = Password::reset($request->only('email', 'password', 'token'), function ($user, $password) {
            $user->password = Hash::make($password);
            $user->save();
        });

        return $response == Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __('Password Berhasil Di Ubah!'))
            : back()->withErrors(['email' => __($response)]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->flush();
        return redirect()->route('login');
    }
}
