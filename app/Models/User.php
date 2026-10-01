<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
        'phone',
        'address',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Check if user is Admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is regular User / Member.
     */
    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    /**
     * Relasi ke Borrowings (peminjaman).
     */
    public function borrowings(): HasMany
    {
        return $this->hasMany(Borrowing::class);
    }

    /**
     * Relasi ke Fines (denda).
     */
    public function fines(): HasMany
    {
        return $this->hasMany(Fine::class);
    }

    /**
     * Hitung jumlah pinjaman aktif (pending / borrowed).
     */
    public function activeBorrowingsCount(): int
    {
        return $this->borrowings()
            ->whereIn('status', ['pending', 'borrowed'])
            ->count();
    }

    /**
     * Cek apakah user masih diperbolehkan meminjam buku (max 3).
     */
    public function canBorrowBook(): bool
    {
        return $this->is_active && $this->activeBorrowingsCount() < 3;
    }
}
