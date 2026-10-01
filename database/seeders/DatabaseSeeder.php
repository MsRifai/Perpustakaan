<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Admin Account
        User::create([
            'name' => 'Administrator Utama',
            'email' => 'admin@perpustakaan.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
            'phone' => '081234567890',
            'address' => 'Gedung Perpustakaan Pusat, Lantai 2',
        ]);

        // 2. Seed Regular Member User
        User::create([
            'name' => 'Budi Santoso (Anggota)',
            'email' => 'user@perpustakaan.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'is_active' => true,
            'phone' => '089876543210',
            'address' => 'Jl. Merdeka No. 45, Jakarta',
        ]);

        // 3. Seed Categories
        $catPemrograman = Category::create([
            'name' => 'Pemrograman & IT',
            'slug' => 'pemrograman-it',
            'description' => 'Buku seputar pengembangan perangkat lunak, algoritma, dan teknologi modern.',
        ]);

        $catSains = Category::create([
            'name' => 'Sains & Teknologi',
            'slug' => 'sains-teknologi',
            'description' => 'Pengetahuan tentang alam, fisika, astronomi, dan matematika.',
        ]);

        $catNovel = Category::create([
            'name' => 'Novel & Sastra',
            'slug' => 'novel-sastra',
            'description' => 'Karya fiksi, novel fiksi ilmiah, dan literatur klasik.',
        ]);

        $catBisnis = Category::create([
            'name' => 'Bisnis & Keuangan',
            'slug' => 'bisnis-keuangan',
            'description' => 'Manajemen, kewirausahaan, dan strategi keuangan.',
        ]);

        // 4. Seed Books
        Book::create([
            'category_id' => $catPemrograman->id,
            'title' => 'Clean Code: A Handbook of Agile Software Craftsmanship',
            'slug' => 'clean-code-a-handbook-of-agile-software-craftsmanship',
            'isbn' => '978-0132350884',
            'author' => 'Robert C. Martin',
            'publisher' => 'Prentice Hall',
            'publication_year' => 2008,
            'total_stock' => 5,
            'available_stock' => 5,
            'description' => 'Panduan komprehensif menulis kode yang bersih, mudah dibaca, dan mudah dirawat.',
        ]);

        Book::create([
            'category_id' => $catPemrograman->id,
            'title' => 'Mastering Laravel 11 & modern PHP',
            'slug' => 'mastering-laravel-11-modern-php',
            'isbn' => '978-1803234411',
            'author' => 'Taylor Otwell & Community',
            'publisher' => 'O\'Reilly Media',
            'publication_year' => 2024,
            'total_stock' => 3,
            'available_stock' => 3,
            'description' => 'Buku panduan lengkap arsitektur framework Laravel untuk pengembang tingkat menengah & profesional.',
        ]);

        Book::create([
            'category_id' => $catSains->id,
            'title' => 'Cosmos: Penjelajahan Alam Semesta',
            'slug' => 'cosmos-penjelajahan-alam-semesta',
            'isbn' => '978-0345331359',
            'author' => 'Carl Sagan',
            'publisher' => 'Gramedia Pustaka Utama',
            'publication_year' => 2013,
            'total_stock' => 4,
            'available_stock' => 4,
            'description' => 'Perjalanan ilmiah mendalam melintasi sejarah alam semesta, sains, dan sains populer.',
        ]);

        Book::create([
            'category_id' => $catNovel->id,
            'title' => 'Laskar Pelangi',
            'slug' => 'laskar-pelangi',
            'isbn' => '978-9793062792',
            'author' => 'Andrea Hirata',
            'publisher' => 'Bentang Pustaka',
            'publication_year' => 2005,
            'total_stock' => 6,
            'available_stock' => 6,
            'description' => 'Kisah inspiratif tentang perjuangan anak-anak Belitung menuntut ilmu.',
        ]);

        Book::create([
            'category_id' => $catBisnis->id,
            'title' => 'Atomic Habits: Perubahan Kecil yang Memberikan Hasil Luar Biasa',
            'slug' => 'atomic-habits',
            'isbn' => '978-0735211292',
            'author' => 'James Clear',
            'publisher' => 'Gramedia Pustaka Utama',
            'publication_year' => 2019,
            'total_stock' => 4,
            'available_stock' => 4,
            'description' => 'Cara mudah dan terbukti untuk membangun kebiasaan baik dan meruntuhkan kebiasaan buruk.',
        ]);

        Book::create([
            'category_id' => $catNovel->id,
            'title' => 'Bumi Manusia',
            'slug' => 'bumi-manusia',
            'isbn' => '978-9799731235',
            'author' => 'Pramoedya Ananta Toer',
            'publisher' => 'Lentera Dipantara',
            'publication_year' => 1980,
            'total_stock' => 5,
            'available_stock' => 5,
            'description' => 'Karya sastra monumental tentang perjuangan Minke di masa awal pergerakan nasional.',
        ]);

        Book::create([
            'category_id' => $catPemrograman->id,
            'title' => 'Design Patterns: Elements of Reusable Object-Oriented Software',
            'slug' => 'design-patterns-elements-of-reusable-object-oriented-software',
            'isbn' => '978-0201633610',
            'author' => 'Erich Gamma, Richard Helm, Ralph Johnson, John Vlissides',
            'publisher' => 'Addison-Wesley',
            'publication_year' => 1994,
            'total_stock' => 3,
            'available_stock' => 3,
            'description' => 'Buku klasik arsitektur perangkat lunak berorientasi objek yang wajib dibaca para pengembang.',
        ]);

        Book::create([
            'category_id' => $catSains->id,
            'title' => 'Sapiens: Riwayat Singkat Umat Manusia',
            'slug' => 'sapiens-riwayat-singkat-umat-manusia',
            'isbn' => '978-6024244163',
            'author' => 'Yuval Noah Harari',
            'publisher' => 'Kepustakaan Populer Gramedia',
            'publication_year' => 2017,
            'total_stock' => 4,
            'available_stock' => 4,
            'description' => 'Eksplorasi sejarah perkembangan spesies Homo Sapiens dari jaman batu hingga era kecerdasan buatan.',
        ]);

        Book::create([
            'category_id' => $catBisnis->id,
            'title' => 'Rich Dad Poor Dad',
            'slug' => 'rich-dad-poor-dad',
            'isbn' => '978-1612680194',
            'author' => 'Robert T. Kiyosaki',
            'publisher' => 'Plata Publishing',
            'publication_year' => 2017,
            'total_stock' => 5,
            'available_stock' => 5,
            'description' => 'Pelajaran dasar literasi keuangan, investasi, dan mindset kebebasan finansial.',
        ]);

        Book::create([
            'category_id' => $catBisnis->id,
            'title' => 'Psychology of Money',
            'slug' => 'psychology-of-money',
            'isbn' => '978-6020653600',
            'author' => 'Morgan Housel',
            'publisher' => 'Gramedia Pustaka Utama',
            'publication_year' => 2021,
            'total_stock' => 4,
            'available_stock' => 4,
            'description' => 'Pelajaran abadi mengenai kekayaan, ketakutan, dan kebahagiaan dalam mengelola keuangan pribadi.',
        ]);
    }
}
