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
