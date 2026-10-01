<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Borrowing extends Model
{
    use HasFactory;

    protected $fillable = [
        'borrow_code',
        'user_id',
        'book_id',
        'borrow_date',
        'due_date',
        'return_date',
        'status',
        'note',
    ];

    protected $casts = [
        'borrow_date' => 'date',
        'due_date' => 'date',
        'return_date' => 'date',
    ];

    /**
     * Relasi ke User (Anggota).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke Book.
     */
    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    /**
     * Relasi ke Fine (Denda).
     */
    public function fine(): HasOne
    {
        return $this->hasOne(Fine::class);
    }

    /**
     * Cek apakah peminjaman melewati tanggal jatuh tempo (terlambat).
     */
    public function isOverdue(): bool
    {
        if ($this->status !== 'borrowed') {
            return false;
        }

        return Carbon::now()->startOfDay()->greaterThan(Carbon::parse($this->due_date)->startOfDay());
    }

    /**
     * Hitung hari keterlambatan dan nominal denda berjalan.
     * Aturan Bisnis: Rp 1.000 / hari keterlambatan.
     */
    public function calculateOverdueFine(): array
    {
        if ($this->status !== 'borrowed') {
            return ['days_late' => 0, 'amount' => 0];
        }

        $today = Carbon::now()->startOfDay();
        $dueDate = Carbon::parse($this->due_date)->startOfDay();

        if ($today->greaterThan($dueDate)) {
            $daysLate = (int) $dueDate->diffInDays($today);
            $ratePerDay = 1000;
            return [
                'days_late' => $daysLate,
                'amount' => $daysLate * $ratePerDay,
            ];
        }

        return ['days_late' => 0, 'amount' => 0];
    }
}
