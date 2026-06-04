<?php

namespace App\Http\Controllers;

use App\Models\Divisi;
use Illuminate\Http\Request;

class DivisiController extends Controller
{
    public function index()
    {
        $divisi = Divisi::orderBy('nama')->get();
        return view('divisi.index', compact('divisi'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:150',
            'singkatan' => 'required|string|max:20|unique:divisi,singkatan',
            'keterangan' => 'nullable|string',
        ], [
            'nama.required' => 'Nama Divisi wajib diisi.',
            'singkatan.required' => 'Singkatan wajib diisi.',
            'singkatan.unique' => 'Singkatan sudah digunakan.',
        ]);

        Divisi::create($request->only('nama', 'singkatan', 'keterangan'));

        return back()->with('success', 'Divisi berhasil ditambahkan.');
    }

    public function update(Request $request, Divisi $divisi)
    {
        $request->validate([
            'nama' => 'required|string|max:150',
            'singkatan' => 'required|string|max:20|unique:divisi,singkatan,' . $divisi->id,
            'keterangan' => 'nullable|string',
        ], [
            'nama.required' => 'Nama Divisi wajib diisi.',
            'singkatan.required' => 'Singkatan wajib diisi.',
            'singkatan.unique' => 'Singkatan sudah digunakan.',
        ]);

        $divisi->update($request->only('nama', 'singkatan', 'keterangan'));

        return back()->with('success', 'Divisi berhasil diperbarui.');
    }

    public function destroy(Divisi $divisi)
    {
        // Cek apakah divisi sudah digunakan di pengajuan atau user
        if ($divisi->pengajuan()->count() > 0 || $divisi->users()->count() > 0) {
            return back()->with('error', 'Divisi tidak dapat dihapus karena masih digunakan pada data Pengajuan atau User.');
        }

        $divisi->delete();

        return back()->with('success', 'Divisi berhasil dihapus.');
    }
}
