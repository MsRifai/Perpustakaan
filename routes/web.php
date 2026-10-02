<?php

use App\Http\Controllers\Admin\BookController;
use App\Http\Controllers\Admin\BorrowingTransactionController;
use App\Http\Controllers\Admin\MemberController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\User\BorrowingRequestController;
use App\Http\Controllers\User\CatalogController;
use App\Http\Controllers\User\UserDashboardController;
use Illuminate\Support\Facades\Route;

// Guest Routes
Route::get('/', [CatalogController::class, 'index'])->name('home');
Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog.index');
Route::get('/catalog/{book:slug}', [CatalogController::class, 'show'])->name('catalog.show');

// Helper Rute untuk Migrasi & Seeder Database Supabase di Vercel
Route::get('/run-migration', function () {
    try {
        \Illuminate\Support\Facades\Artisan::call('migrate:fresh', [
            '--force' => true,
            '--seed' => true,
        ]);
        return '<div style="font-family:sans-serif;padding:30px;background:#ecfdf5;color:#047857;border-radius:12px;">'
            . '<h2 style="margin:0 0 10px 0;">✅ Migration & Seeding Supabase Berhasil!</h2>'
            . '<p>Tabel-tabel dan data awal perpustakaan berhasil dibuat di Supabase.</p>'
            . '<pre style="background:#111827;color:#10b981;padding:15px;border-radius:8px;overflow-x:auto;">' 
            . \Illuminate\Support\Facades\Artisan::output() 
            . '</pre>'
            . '<br><a href="/" style="background:#047857;color:#fff;padding:10px 20px;border-radius:8px;text-decoration:none;font-weight:bold;">Kembali ke Aplikasi</a>'
            . '</div>';
    } catch (\Exception $e) {
        return '<div style="font-family:sans-serif;padding:30px;background:#fef2f2;color:#b91c1c;border-radius:12px;">'
            . '<h2>❌ Error Migration</h2>'
            . '<p>' . e($e->getMessage()) . '</p>'
            . '</div>';
    }
});

// Helper Rute untuk Menyajikan File Storage di Vercel Serverless
Route::get('/storage/{path}', function ($path) {
    $tmpFile = '/tmp/storage/app/public/' . $path;
    $localFile = storage_path('app/public/' . $path);

    if (file_exists($tmpFile)) {
        return response()->file($tmpFile);
    }

    if (file_exists($localFile)) {
        return response()->file($localFile);
    }

    abort(404);
})->where('path', '.*');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Profile Routes (Semua Pengguna Terautentikasi)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'updateInfo'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
});

// User / Member Routes
Route::middleware(['auth', 'role:user'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
    Route::post('/borrow/{book}', [BorrowingRequestController::class, 'store'])->name('borrow.store');
});

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    // Circulation Transactions & Export Report
    Route::get('/transactions', [BorrowingTransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/export', [BorrowingTransactionController::class, 'exportCsv'])->name('transactions.export');
    Route::post('/transactions/{borrowing}/approve', [BorrowingTransactionController::class, 'approve'])->name('transactions.approve');
    Route::post('/transactions/{borrowing}/reject', [BorrowingTransactionController::class, 'reject'])->name('transactions.reject');
    Route::post('/transactions/{borrowing}/return', [BorrowingTransactionController::class, 'processReturn'])->name('transactions.return');
    Route::post('/fines/{fine}/pay', [BorrowingTransactionController::class, 'payFine'])->name('fines.pay');

    // Master Kategori
    Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class)->only(['index', 'store', 'update', 'destroy']);

    // Master Buku
    Route::resource('books', BookController::class);

    // Master Anggota
    Route::get('/members', [MemberController::class, 'index'])->name('members.index');
    Route::post('/members/{user}/toggle', [MemberController::class, 'toggleStatus'])->name('members.toggle');
    Route::get('/members/{user}', [MemberController::class, 'show'])->name('members.show');
});
