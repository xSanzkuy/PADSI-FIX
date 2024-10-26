<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Exception;

class PegawaiController extends Controller
{
    // Fungsi untuk menampilkan semua pegawai
    public function index(Request $request)
    {
        try {
            $search = $request->input('search');
            $pegawai = Pegawai::with('role')
                ->when($search, function ($query, $search) {
                    return $query->where('nama', 'like', "%{$search}%")
                                 ->orWhere('email', 'like', "%{$search}%")
                                 ->orWhere('alamat', 'like', "%{$search}%");
                })
                ->get(); // Mengambil semua pegawai dengan relasi role

            return view('pegawai.index', compact('pegawai'));
        } catch (Exception $e) {
            Log::error('Error saat mengambil data pegawai: ' . $e->getMessage());
            return redirect()->back()->withErrors('Terjadi kesalahan saat mengambil data pegawai, silakan coba lagi.');
        }
    }

    // Fungsi untuk menampilkan form tambah pegawai
    public function create()
    {
        try {
            $roles = Role::all(); // Mengambil semua role untuk form
            return view('pegawai.create', compact('roles'));
        } catch (Exception $e) {
            Log::error('Error saat menampilkan form tambah pegawai: ' . $e->getMessage());
            return redirect()->route('pegawai.index')->withErrors('Terjadi kesalahan saat menampilkan form tambah pegawai.');
        }
    }

    // Fungsi untuk menyimpan data pegawai baru ke dalam database
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:pegawai,email|unique:users,email', // Pastikan email unik di tabel users
            'alamat' => 'required|string|max:255',
            'id_role' => 'required|exists:roles,id',
        ]);
    
        try {
            // Buat data pegawai
            $pegawai = Pegawai::create($request->only(['nama', 'email', 'alamat', 'id_role']));
    
            // Buat akun user otomatis
            $user = User::create([
                'username' => strtolower(str_replace(' ', '_', $request->nama)), // Username di-generate dari nama
                'email' => $request->email,
                'password' => Hash::make('auto123'), // Set password default dengan hashing
            ]);
    
            // Ambil role berdasarkan id_role yang dipilih
            $role = Role::find($request->id_role);
            if ($role) {
                $user->roles()->attach($role->id); // Kaitkan role ke user
            }
    
            // Simpan ID user di data pegawai untuk referensi
            $pegawai->update(['user_id' => $user->id]);
    
            return redirect()->route('pegawai.index')->with('success', 'Pegawai dan akun berhasil ditambahkan.');
        } catch (Exception $e) {
            Log::error('Error saat menyimpan pegawai: ' . $e->getMessage());
            return redirect()->back()->withErrors('Terjadi kesalahan saat menyimpan data pegawai, silakan coba lagi.');
        }
    }
    
    // Fungsi untuk menampilkan form edit pegawai
    public function edit($id)
    {
        try {
            $pegawai = Pegawai::findOrFail($id);
            $roles = Role::all();
            return view('pegawai.edit', compact('pegawai', 'roles'));
        } catch (Exception $e) {
            Log::error('Error saat menampilkan form edit pegawai: ' . $e->getMessage());
            return redirect()->route('pegawai.index')->withErrors('Terjadi kesalahan saat menampilkan form edit pegawai.');
        }
    }

    // Fungsi untuk memperbarui data pegawai
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:pegawai,email,' . $id . '|unique:users,email,' . $id,
            'alamat' => 'required|string|max:255',
            'id_role' => 'required|exists:roles,id',
        ]);

        try {
            $pegawai = Pegawai::findOrFail($id);
            $pegawai->update($request->only(['nama', 'email', 'alamat', 'id_role']));

            // Update juga role di akun user terkait jika ada
            if ($pegawai->user) {
                $pegawai->user->update(['email' => $request->email]);

                // Update role user jika diubah
                $role = Role::find($request->id_role);
                if ($role) {
                    $pegawai->user->roles()->sync([$role->id]); // Update role di tabel pivot
                }
            }

            return redirect()->route('pegawai.index')->with('success', 'Pegawai berhasil diperbarui.');
        } catch (Exception $e) {
            Log::error('Error saat memperbarui pegawai: ' . $e->getMessage());
            return redirect()->back()->withErrors('Terjadi kesalahan saat memperbarui data pegawai, silakan coba lagi.');
        }
    }

    // Fungsi untuk menghapus pegawai
    public function destroy($id)
    {
        try {
            $pegawai = Pegawai::findOrFail($id);

            // Hapus user terkait jika ada
            if ($pegawai->user) {
                $pegawai->user->delete();
            }

            $pegawai->delete(); // Menghapus pegawai

            return redirect()->route('pegawai.index')->with('success', 'Pegawai berhasil dihapus.');
        } catch (Exception $e) {
            Log::error('Error saat menghapus pegawai: ' . $e->getMessage());
            return redirect()->route('pegawai.index')->withErrors('Terjadi kesalahan saat menghapus pegawai, silakan coba lagi.');
        }
    }
}
