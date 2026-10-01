<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Tampilkan halaman profil pengguna.
     */
    public function edit(Request $request)
    {
        $user = $request->user();

        // Hitung statistik peminjaman untuk anggota jika role user
        $stats = [
            'total_borrowed' => $user->borrowings()->count(),
            'active_borrowed' => $user->borrowings()->whereIn('status', ['pending', 'borrowed'])->count(),
            'total_fines' => $user->fines()->sum('amount'),
            'unpaid_fines' => $user->fines()->where('status', 'unpaid')->sum('amount'),
        ];

        return view('profile.edit', compact('user', 'stats'));
    }

    /**
     * Perbarui informasi profil (nama, telepon, alamat).
     */
    public function updateInfo(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
        ]);

        $user->update($validated);

        return back()->with('success', 'Informasi profil berhasil diperbarui.');
    }

    /**
     * Perbarui kata sandi pengguna.
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Kata sandi berhasil diubah.');
    }
}
