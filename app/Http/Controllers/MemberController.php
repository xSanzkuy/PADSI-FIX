<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Mengambil input pencarian (search)
        $search = $request->input('search');

        // Mengambil data member berdasarkan pencarian
        $members = Member::when($search, function ($query, $search) {
            return $query->where('nama', 'like', '%' . $search . '%')
                         ->orWhere('no_hp', 'like', '%' . $search . '%')
                         ->orWhere('tingkat', 'like', '%' . $search . '%');
        })->get();

        return view('member.index', compact('members', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Mengarahkan ke halaman form tambah member
        return view('member.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama' => 'required|string|max:255',
            'no_hp' => 'required|string|max:15',
            'tingkat' => 'required|in:bronze,silver,gold', // Memastikan input tingkat sesuai dengan opsi
        ]);

        // Membuat member baru
        Member::create($request->all());

        return redirect()->route('member.index')->with('success', 'Member berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // Mengambil data member berdasarkan ID untuk form edit
        $member = Member::findOrFail($id);
        return view('member.edit', compact('member'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'nama' => 'required|string|max:255',
            'no_hp' => 'required|string|max:15',
            'tingkat' => 'required|in:bronze,silver,gold', // Memastikan input tingkat sesuai dengan opsi
        ]);

        // Update member
        $member = Member::findOrFail($id);
        $member->update($request->all());

        return redirect()->route('member.index')->with('success', 'Member berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Mengambil data member berdasarkan ID dan menghapusnya
        $member = Member::findOrFail($id);
        $member->delete();

        return redirect()->route('member.index')->with('success', 'Member berhasil dihapus.');
    }
    
    /**
     * Show the form to check membership status.
     */
    public function showCheckMemberForm()
    {
        // Menampilkan form untuk cek tingkat member berdasarkan nomor telepon
        return view('member.check');
    }

    /**
     * Check membership details based on phone number.
     */
    public function checkMember(Request $request)
    {
        // Validasi input nomor telepon
        $request->validate([
            'phone' => 'required',
        ]);

        // Mencari member berdasarkan nomor telepon
        $member = Member::where('no_hp', $request->phone)->first();

        if ($member) {
            // Jika ditemukan, tampilkan data member
            return view('member.detail', compact('member'));
        } else {
            // Jika tidak ditemukan, arahkan kembali dengan pesan error
            return redirect()->route('check.member.form')->with('error', 'Nomor telepon tidak ditemukan.');
        }
    }
}
