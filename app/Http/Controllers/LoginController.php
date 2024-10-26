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

        $dataLogin = $request->only('username', 'password');

        if (Auth::attempt(['username' => strtolower($dataLogin['username']), 'password' => $dataLogin['password']])) {
            $user = Auth::guard('web')->user();
            $pegawai = $user->pegawai;
        } else {
            $pegawai = Pegawai::where('email', $dataLogin['username'])->first();

            if (empty($pegawai)) {
                return redirect()->to('/login')->with('error', 'Username/Email dan Password salah')->withInput();
            }

            $user = User::where('id', $pegawai->user_id)->first();

            if (empty($user) || !Hash::check($dataLogin['password'], $user->password)) {
                return redirect()->to('/login')->with('error', 'Username/Email dan Password salah')->withInput();
            }
        }

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

        if (session('role') === 'pegawai') {
            return redirect()->route('transactions.index');
        } else {
            return redirect()->route('dashboard');
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
            ? back()->with('status', __('Password reset link sent to your email.'))
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
            ? redirect()->route('login.form')->with('status', __('Password has been reset!'))
            : back()->withErrors(['email' => __($response)]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->flush();
        return redirect()->route('login.form');
    }
}
