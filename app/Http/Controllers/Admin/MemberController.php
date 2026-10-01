<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'user')->withCount(['borrowings as active_loans_count' => function ($q) {
            $q->whereIn('status', ['pending', 'borrowed']);
        }]);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $members = $query->latest()->paginate(10)->withQueryString();

        return view('admin.members.index', compact('members'));
    }

    public function toggleStatus(User $user)
    {
        if ($user->isAdmin()) {
            return back()->with('error', 'Status akun Admin tidak dapat diubah.');
        }

        $user->is_active = !$user->is_active;
        $user->save();

        $statusText = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Akun anggota [{$user->name}] berhasil {$statusText}.");
    }

    public function show(User $user)
    {
        if ($user->isAdmin()) {
            return back()->with('error', 'Detail hanya untuk anggota perpustakaan.');
        }

        $user->load(['borrowings.book', 'fines']);

        return view('admin.members.show', compact('user'));
    }
}
