<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Borrowing;
use App\Models\Fine;
use Illuminate\Support\Facades\Auth;

class UserDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Pinjaman aktif (pending, borrowed)
        $activeBorrowings = Borrowing::with('book')
            ->where('user_id', $user->id)
            ->whereIn('status', ['pending', 'borrowed'])
            ->latest()
            ->get();

        // Hitung perkiraan denda berjalan untuk buku yang dipinjam dan melewati due_date
        $runningFineTotal = 0;
        foreach ($activeBorrowings as $borrowing) {
            $fineInfo = $borrowing->calculateOverdueFine();
            $borrowing->days_late = $fineInfo['days_late'];
            $borrowing->running_fine = $fineInfo['amount'];
            $runningFineTotal += $fineInfo['amount'];
        }

        // Histori peminjaman yang sudah selesai / ditolak / hilang / rusak
        $borrowingHistory = Borrowing::with(['book', 'fine'])
            ->where('user_id', $user->id)
            ->whereIn('status', ['returned', 'rejected', 'lost', 'damaged'])
            ->latest()
            ->paginate(5);

        // Rekap Denda yang belum dibayar (unpaid)
        $unpaidFines = Fine::where('user_id', $user->id)
            ->where('status', 'unpaid')
            ->sum('amount');

        return view('user.dashboard', compact(
            'user',
            'activeBorrowings',
            'runningFineTotal',
            'borrowingHistory',
            'unpaidFines'
        ));
    }
}
