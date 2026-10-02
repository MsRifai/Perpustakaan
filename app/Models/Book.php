<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'isbn',
        'author',
        'publisher',
        'publication_year',
        'total_stock',
        'available_stock',
        'cover_image',
        'description',
    ];

    /**
     * Relasi ke Category.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relasi ke Borrowings.
     */
    public function borrowings(): HasMany
    {
        return $this->hasMany(Borrowing::class);
    }

    /**
     * Cek apakah stok fisik buku tersedia.
     */
    public function isAvailable(): bool
    {
        return $this->available_stock > 0;
    }

    /**
     * Get full URL or Data URI for book cover image.
     */
    public function getCoverUrlAttribute(): ?string
    {
        if (!$this->cover_image) {
            return null;
        }

        if (str_starts_with($this->cover_image, 'http://') || str_starts_with($this->cover_image, 'https://') || str_starts_with($this->cover_image, 'data:')) {
            return $this->cover_image;
        }

        return asset('storage/' . $this->cover_image);
    }
}
