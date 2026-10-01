<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Borrowing;
use App\Models\Fine;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BorrowingTransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Borrowing::with(['user', 'book', 'fine']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('borrow_code', 'like', "%{$search}%")
                  ->orWhereHas('user', fn($u) => $u->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%"))
                  ->orWhereHas('book', fn($b) => $b->where('title', 'like', "%{$search}%")->orWhere('isbn', 'like', "%{$search}%"));
            });
        }

        $borrowings = $query->latest()->paginate(10)->withQueryString();

        // Hitung denda terdeteksi untuk transaksi yang sedang berjalan (borrowed)
        foreach ($borrowings as $b) {
            if ($b->status === 'borrowed') {
                $fineData = $b->calculateOverdueFine();
                $b->current_days_late = $fineData['days_late'];
                $b->current_fine_amount = $fineData['amount'];
            }
        }

        $stats = [
            'pending' => Borrowing::where('status', 'pending')->count(),
            'borrowed' => Borrowing::where('status', 'borrowed')->count(),
            'returned' => Borrowing::where('status', 'returned')->count(),
            'unpaid_fines' => Fine::where('status', 'unpaid')->sum('amount'),
        ];

        return view('admin.transactions.index', compact('borrowings', 'stats'));
    }

    /**
     * Verifikasi & Setujui Peminjaman oleh Admin
     */
    public function approve(Borrowing $borrowing)
    {
        if ($borrowing->status !== 'pending') {
            return back()->with('error', 'Status peminjaman ini bukan pending.');
        }

        $today = Carbon::now();
        $dueDate = $today->copy()->addDays(7); // Default 7 Hari

        $borrowing->update([
            'status' => 'borrowed',
            'borrow_date' => $today->toDateString(),
            'due_date' => $dueDate->toDateString(),
        ]);

        return back()->with('success', "Peminjaman [{$borrowing->borrow_code}] berhasil disetujui. Jatuh tempo: {$dueDate->format('d M Y')}");
    }

    /**
     * Penolakan Peminjaman oleh Admin
     */
    public function reject(Request $request, Borrowing $borrowing)
    {
        if ($borrowing->status !== 'pending') {
            return back()->with('error', 'Hanya pengajuan status pending yang dapat ditolak.');
        }

        DB::transaction(function () use ($borrowing, $request) {
            $borrowing->update([
                'status' => 'rejected',
                'note' => $request->input('note', 'Pengajuan ditolak oleh petugas.'),
            ]);

            // Kembalikan stok fisik buku
            $borrowing->book()->increment('available_stock');
        });

        return back()->with('success', "Pengajuan peminjaman [{$borrowing->borrow_code}] ditolak dan stok dikembalikan.");
    }

    /**
     * Proses Pengembalian Buku & Verifikasi Denda (DB Transaction)
     */
    public function processReturn(Request $request, Borrowing $borrowing)
    {
        if ($borrowing->status !== 'borrowed') {
            return back()->with('error', 'Transaksi ini tidak dalam status sedang dipinjam.');
        }

        $request->validate([
            'condition_status' => ['required', 'in:returned,lost,damaged'],
            'penalty_amount' => ['nullable', 'numeric', 'min:0'],
            'note' => ['nullable', 'string'],
        ]);

        $condition = $request->condition_status;
        $customPenalty = $request->penalty_amount ?? 0;

        try {
            DB::transaction(function () use ($borrowing, $condition, $customPenalty, $request) {
                $returnDate = Carbon::now();
                $dueDate = Carbon::parse($borrowing->due_date);

                $daysLate = 0;
                $overdueFineAmount = 0;

                // Perhitungan Denda Keterlambatan Otomatis (Rp 1.000 / hari)
                if ($returnDate->startOfDay()->greaterThan($dueDate->startOfDay())) {
                    $daysLate = (int) $dueDate->startOfDay()->diffInDays($returnDate->startOfDay());
                    $overdueFineAmount = $daysLate * 1000;
                }

                $totalFineAmount = $overdueFineAmount + $customPenalty;

                // Update Status Transaksi Sirkulasi
                $borrowing->update([
                    'return_date' => $returnDate->toDateString(),
                    'status' => $condition,
                    'note' => $request->note,
                ]);

                // Update Stok Buku sesuai Kondisi
                $book = Book::where('id', $borrowing->book_id)->lockForUpdate()->first();

                if ($condition === 'returned') {
                    // Pengembalian Normal: Kembalikan Stok Tersedia
                    $book->increment('available_stock');
                } elseif ($condition === 'lost') {
                    // Hilang: Kurangi Total Stok & Stok Tersedia
                    $book->decrement('total_stock');
                } elseif ($condition === 'damaged') {
                    // Rusak: Kurangi Total Stok & Stok Tersedia
                    $book->decrement('total_stock');
                }

                // Catat Denda jika ada nominal keterlambatan atau penalty fisik
                if ($totalFineAmount > 0) {
                    Fine::create([
                        'borrowing_id' => $borrowing->id,
                        'user_id' => $borrowing->user_id,
                        'amount' => $totalFineAmount,
                        'days_late' => $daysLate,
                        'status' => 'unpaid',
                    ]);
                }
            });

            return back()->with('success', "Proses pengembalian [{$borrowing->borrow_code}] berhasil disimpan.");

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat memproses pengembalian: ' . $e->getMessage());
        }
    }

    /**
     * Konfirmasi Pembayaran Denda
     */
    public function payFine(Fine $fine)
    {
        if ($fine->status === 'paid') {
            return back()->with('error', 'Denda ini sudah dibayar.');
        }

        $fine->update([
            'status' => 'paid',
            'paid_at' => Carbon::now(),
        ]);

        return back()->with('success', 'Pembayaran denda berhasil dikonfirmasi!');
    }

    /**
     * Ekspor Laporan Sirkulasi & Denda ke CSV / Excel
     */
    public function exportCsv(Request $request)
    {
        $fileName = 'laporan-sirkulasi-perpustakaan-' . date('Y-m-d') . '.csv';

        $borrowings = Borrowing::with(['user', 'book', 'fine'])->latest()->get();

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function () use ($borrowings) {
            $file = fopen('php://output', 'w');
            // Header kolom
            fputcsv($file, ['Kode TRX', 'Nama Anggota', 'Email Anggota', 'Judul Buku', 'ISBN', 'Tgl Pinjam', 'Jatuh Tempo', 'Tgl Kembali', 'Status', 'Nominal Denda (Rp)', 'Status Denda']);

            foreach ($borrowings as $b) {
                fputcsv($file, [
                    $b->borrow_code,
                    $b->user->name ?? '-',
                    $b->user->email ?? '-',
                    $b->book->title ?? '-',
                    $b->book->isbn ?? '-',
                    $b->borrow_date,
                    $b->due_date,
                    $b->return_date ?? '-',
                    strtoupper($b->status),
                    $b->fine ? $b->fine->amount : 0,
                    $b->fine ? strtoupper($b->fine->status) : 'TIDAK ADA'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
