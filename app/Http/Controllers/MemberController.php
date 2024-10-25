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
            'tingkat' => 'required|in:silver,bronze,gold',
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
            'tingkat' => 'required|in:silver,bronze,gold',
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
        $member = Member::findOrFail($id);
        $member->delete();

        return redirect()->route('member.index')->with('success', 'Member berhasil dihapus.');
    }
}
