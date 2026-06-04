<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('divisi')->orderBy('nama')->paginate(15);
        return view('users.index', compact('users'));
    }

    public function create()
    {
        $divisi = \App\Models\Divisi::orderBy('nama')->get();
        return view('users.create', compact('divisi'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'      => 'required|string|max:100',
            'username'  => 'required|string|max:50|unique:users',
            'password'  => 'required|string|min:6|confirmed',
            'role'      => 'required|in:Super Admin,Administrator,User Divisi',
            'id_divisi' => 'required_if:role,User Divisi|nullable|exists:divisi,id',
        ], [
            'nama.required'      => 'Nama wajib diisi.',
            'username.required'  => 'Username wajib diisi.',
            'username.unique'    => 'Username sudah digunakan.',
            'password.required'  => 'Password wajib diisi.',
            'password.min'       => 'Password minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'role.required'      => 'Role wajib dipilih.',
            'id_divisi.required_if' => 'Divisi wajib dipilih untuk role User Divisi.',
        ]);

        User::create([
            'nama'      => $request->nama,
            'username'  => $request->username,
            'password'  => Hash::make($request->password),
            'role'      => $request->role,
            'id_divisi' => $request->role === 'User Divisi' ? $request->id_divisi : null,
        ]);

        return redirect()->route('users.index')
            ->with('success', "User {$request->username} berhasil ditambahkan.");
    }

    public function edit(User $user)
    {
        $divisi = \App\Models\Divisi::orderBy('nama')->get();
        return view('users.edit', compact('user', 'divisi'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'nama'      => 'required|string|max:100',
            'username'  => ['required', 'string', 'max:50', Rule::unique('users')->ignore($user->id)],
            'role'      => 'required|in:Super Admin,Administrator,User Divisi',
            'id_divisi' => 'required_if:role,User Divisi|nullable|exists:divisi,id',
            'password'  => 'nullable|string|min:6|confirmed',
        ]);

        $data = [
            'nama'      => $request->nama,
            'username'  => $request->username,
            'role'      => $request->role,
            'id_divisi' => $request->role === 'User Divisi' ? $request->id_divisi : null,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('users.index')
            ->with('success', "User {$user->username} berhasil diperbarui.");
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak dapat menghapus akun sendiri.');
        }

        $username = $user->username;
        $user->delete();

        return redirect()->route('users.index')
            ->with('success', "User {$username} berhasil dihapus.");
    }
}
