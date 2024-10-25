<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /**
     * Menampilkan halaman login.
     */
    public function index(Request $request)
    {
        return view('auth.login'); // Mengarahkan ke view login
    }

    /**
     * Proses login user.
     */
    public function login(Request $request)
    {
        // Validasi input login
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Ambil input username dan password
        $dataLogin = $request->only('username', 'password');

        // Cek login menggunakan username
        if (Auth::attempt(['username' => strtolower($dataLogin['username']), 'password' => $dataLogin['password']])) {
            // Ambil data user yang login
            $user = Auth::guard('web')->user();

            // Jika user bukan pegawai
            if ($user->username != 'pegawai') {
                // Cari data pegawai berdasarkan user
                $pegawai = Pegawai::where('id_user', $user->id_user)->first();
            }

        } else {
            // Jika gagal login dengan username, cek dengan email
            $pegawai = Pegawai::where('email', $dataLogin['username'])->first();

            // Jika pegawai tidak ditemukan, kirim error
            if (empty($pegawai)) {
                return redirect()->to('/login')->with('error', 'Username/Email dan Password salah')->withInput();
            }

            // Cari user berdasarkan ID pegawai
            $user = User::where('id_user', $pegawai->id_user)->first();

            // Jika tidak ditemukan atau password salah, kirim error
            if (empty($pegawai) || !Hash::check($dataLogin['password'], $user->password)) {
                return redirect()->to('/login')->with('error', 'Username/Email dan Password salah')->withInput();
            }
        }

        // Simpan data sesi
        $ses_data = [
            'username' => $user->username,
            'role' => Role::where('id_role', $user->id_role)->value('nama_role'),
        ];

        // Jika user bukan pegawai, tambahkan informasi pegawai ke sesi
        if ($user->username != 'pegawai') {
            $ses_data += [
                'no_pegawai' => $pegawai->no_pegawai,
                'foto' => '/images/pegawai/' . $pegawai->foto,
                'email' => $pegawai->email,
                'no_hp' => $pegawai->no_hp,
                'nama_depan' => explode(' ', $pegawai->nama)[0],
                'nama_blkng' => explode(' ', $pegawai->nama, 2)[1] ?? '',
            ];
        } else {
            $ses_data += [
                'foto' => '/images/pegawai/pegawai.svg',
            ];
        }

        // Simpan data sesi
        $request->session()->put($ses_data);

        // Redirect user berdasarkan role
        if (session('role') == 'pegawai') {
            return redirect()->route('transaksi'); // Redirect ke transaksi jika pegawai
        } else {
            return redirect()->route('dashboard'); // Redirect ke dashboard untuk lainnya
        }
    }

    /**
     * Proses reset password.
     */
    public function resetPass(Request $request, $id)
    {
        // Validasi input reset password
        $validator = Validator::make(
            $request->all(),
            [
                'oldPassword' => 'required',
                'newPassword' => 'required|min:10|same:confirmPassword|regex:/^(?:(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{10,})$/',
                'confirmPassword' => 'required',
            ],
            [
                'oldPassword.required' => 'Masukkan password lama anda',
                'newPassword.required' => 'Masukkan password terbaru',
                'newPassword.min' => 'Password minimal 10 karakter',
                'newPassword.same' => 'Konfirmasi password tidak cocok',
                'confirmPassword.required' => 'Konfirmasi password diperlukan',
                'newPassword.regex' => 'Password harus mengandung minimal satu huruf besar, satu huruf kecil, satu angka, dan satu karakter khusus',
            ]
        );

        // Jika validasi gagal
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Dekripsi ID user yang ingin direset
        $id = decrypt($id);
        $user = User::where('username', $id)->first();

        // Cek password lama
        if (!Hash::check($request->oldPassword, $user->password)) {
            return redirect()->back()->with('error', 'Password lama salah')->withInput();
        }

        // Update password baru
        $user->update([
            'password' => Hash::make($request->newPassword),
        ]);

        // Pesan sukses
        return redirect()->back()->with('success', 'Password berhasil diubah');
    }

    /**
     * Proses logout user.
     */
    public function logout(Request $request)
    {
        Auth::logout(); // Logout user

        // Hapus semua data sesi
        $request->session()->flush();

        // Redirect ke halaman login
        return redirect()->route('login.form');
    }
}
