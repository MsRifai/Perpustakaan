<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Borrowing;
use App\Models\Category;
use App\Models\Fine;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBooks = Book::sum('total_stock');
        $totalMembers = User::where('role', 'user')->count();
        $activeBorrowingsCount = Borrowing::where('status', 'borrowed')->count();
        $pendingBorrowingsCount = Borrowing::where('status', 'pending')->count();
        $totalFinesUnpaid = Fine::where('status', 'unpaid')->sum('amount');
        $totalFinesPaid = Fine::where('status', 'paid')->sum('amount');

        $recentBorrowings = Borrowing::with(['user', 'book'])->latest()->take(5)->get();
        $recentMembers = User::where('role', 'user')->latest()->take(4)->get();
        $categories = Category::withCount('books')->get();

        return view('admin.dashboard', compact(
            'totalBooks',
            'totalMembers',
            'activeBorrowingsCount',
            'pendingBorrowingsCount',
            'totalFinesUnpaid',
            'totalFinesPaid',
            'recentBorrowings',
            'recentMembers',
            'categories'
        ));
    }
}
