<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
            'email' => 'required|email|unique:pegawai,email',
            'alamat' => 'required|string|max:255',
            'id_role' => 'required|exists:roles,id',
        ]);

        try {
            Pegawai::create($request->only(['nama', 'email', 'alamat', 'id_role']));
            return redirect()->route('pegawai.index')->with('success', 'Pegawai berhasil ditambahkan.');
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
            'email' => 'required|email|unique:pegawai,email,' . $id,
            'alamat' => 'required|string|max:255',
            'id_role' => 'required|exists:roles,id',
        ]);

        try {
            $pegawai = Pegawai::findOrFail($id);
            $pegawai->update($request->only(['nama', 'email', 'alamat', 'id_role']));
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
            $pegawai = Pegawai::findOrFail($id); // Mengambil pegawai berdasarkan ID
            $pegawai->delete(); // Menghapus pegawai menggunakan soft delete
    
            return redirect()->route('pegawai.index')->with('success', 'Pegawai berhasil dihapus.');
        } catch (Exception $e) {
            Log::error('Error saat menghapus pegawai: ' . $e->getMessage());
            return redirect()->route('pegawai.index')->withErrors('Terjadi kesalahan saat menghapus pegawai, silakan coba lagi.');
        }
    }
}
