<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Borrowing;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BorrowingRequestController extends Controller
{
    public function store(Request $request, Book $book)
    {
        $user = Auth::user();

        // 1. Validasi status akun
        if (!$user->is_active) {
            return back()->with('error', 'Akun Anda tidak aktif untuk melakukan peminjaman.');
        }

        // 2. Validasi Batas Maksimal 3 Buku Sekaligus (Business Rule)
        if ($user->activeBorrowingsCount() >= 3) {
            return back()->with('error', 'Gagal Pengajuan: Anda telah mencapai batas maksimal peminjaman (3 buku sekaligus). Kembalikan buku yang sedang dipinjam terlebih dahulu.');
        }

        // 3. Cek ketersediaan buku yang sudah dipinjam user yang sama dan belum dikembalikan
        $alreadyBorrowed = Borrowing::where('user_id', $user->id)
            ->where('book_id', $book->id)
            ->whereIn('status', ['pending', 'borrowed'])
            ->exists();

        if ($alreadyBorrowed) {
            return back()->with('error', 'Anda sudah mengajukan / meminjam buku ini. Harap selesaikan transaksi tersebut terlebih dahulu.');
        }

        // 4. Cek Stok Fisik Buku Tersedia
        if ($book->available_stock <= 0) {
            return back()->with('error', 'Maaf, stok fisik buku ini sedang kosong / habis dipinjam.');
        }

        // 5. Eksekusi DB Transaction untuk Atomisitas Stok dan Peminjaman
        try {
            DB::transaction(function () use ($user, $book) {
                // Lock row buku untuk mencegah race condition stok
                $lockedBook = Book::where('id', $book->id)->lockForUpdate()->first();

                if ($lockedBook->available_stock <= 0) {
                    throw new \Exception('Stok buku tiba-tiba habis saat pemrosesan.');
                }

                // Pengurangan Stok Tersedia Otomatis
                $lockedBook->decrement('available_stock');

                // Generate Kode Transaksi Unik: TRX-YYYYMMDD-RANDOM
                $borrowCode = 'TRX-' . Carbon::now()->format('Ymd') . '-' . strtoupper(Str::random(5));

                $borrowDate = Carbon::now()->toDateString();
                $dueDate = Carbon::now()->addDays(7)->toDateString(); // Default 7 Hari (Business Rule)

                Borrowing::create([
                    'borrow_code' => $borrowCode,
                    'user_id' => $user->id,
                    'book_id' => $lockedBook->id,
                    'borrow_date' => $borrowDate,
                    'due_date' => $dueDate,
                    'status' => 'pending', // Menunggu Verifikasi Admin/Petugas
                ]);
            });

            return redirect()->route('user.dashboard')->with('success', 'Pengajuan peminjaman berhasil dikirim! Silakan datang ke perpustakaan untuk verifikasi petugas.');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memproses transaksi: ' . $e->getMessage());
        }
    }
}
