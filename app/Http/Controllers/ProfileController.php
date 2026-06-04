<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function editPassword()
    {
        return view('profile.password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'password_lama'         => 'required|string',
            'password_baru'         => 'required|string|min:6|confirmed',
        ], [
            'password_lama.required'         => 'Password lama wajib diisi.',
            'password_baru.required'         => 'Password baru wajib diisi.',
            'password_baru.min'              => 'Password baru minimal 6 karakter.',
            'password_baru.confirmed'        => 'Konfirmasi password baru tidak cocok.',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->password_lama, $user->password)) {
            return back()->withErrors(['password_lama' => 'Password lama tidak sesuai.']);
        }

        $user->update(['password' => Hash::make($request->password_baru)]);

        return back()->with('success', 'Password berhasil diperbarui.');
    }
}
