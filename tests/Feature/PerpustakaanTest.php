<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Borrowing;
use App\Models\Category;
use App\Models\Fine;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PerpustakaanTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $member;
    private Category $category;
    private Book $book;

    protected function setUp(): void
    {
        parent::setUp();

        // Create Admin
        $this->admin = User::create([
            'name' => 'Admin Utama',
            'email' => 'admin@perpustakaan.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        // Create Regular Member
        $this->member = User::create([
            'name' => 'Budi Anggota',
            'email' => 'budi@gmail.com',
            'password' => bcrypt('password'),
            'role' => 'user',
            'is_active' => true,
        ]);

        // Create Category & Book
        $this->category = Category::create([
            'name' => 'Pemrograman',
            'slug' => 'pemrograman',
        ]);

        $this->book = Book::create([
            'category_id' => $this->category->id,
            'title' => 'Laravel 11 Guide',
            'slug' => 'laravel-11-guide',
            'isbn' => '978-1234567890',
            'author' => 'Taylor Otwell',
            'publisher' => 'O-Reilly',
            'publication_year' => 2024,
            'total_stock' => 5,
            'available_stock' => 5,
        ]);
    }

    public function test_guest_can_access_catalog(): void
    {
        $response = $this->get('/catalog');
        $response->assertStatus(200);
        $response->assertSee('Laravel 11 Guide');
    }

    public function test_user_can_login_and_access_user_dashboard(): void
    {
        $response = $this->post('/login', [
            'email' => 'budi@gmail.com',
            'password' => 'password',
        ]);

        $response->assertRedirect('/user/dashboard');
        $this->assertAuthenticatedAs($this->member);
    }

    public function test_user_can_request_book_borrowing(): void
    {
        $response = $this->actingAs($this->member)->post(route('user.borrow.store', $this->book->id));

        $response->assertRedirect(route('user.dashboard'));
        $this->assertDatabaseHas('borrowings', [
            'user_id' => $this->member->id,
            'book_id' => $this->book->id,
            'status' => 'pending',
        ]);

        // Available stock should be decremented from 5 to 4
        $this->assertEquals(4, $this->book->fresh()->available_stock);
    }

    public function test_user_cannot_borrow_more_than_3_books(): void
    {
        // Create 3 active borrowings for member
        for ($i = 1; $i <= 3; $i++) {
            $b = Book::create([
                'category_id' => $this->category->id,
                'title' => "Buku $i",
                'slug' => "buku-$i",
                'isbn' => "978-000000000$i",
                'author' => 'Penulis',
                'publisher' => 'Penerbit',
                'publication_year' => 2024,
                'total_stock' => 2,
                'available_stock' => 2,
            ]);

            Borrowing::create([
                'borrow_code' => "TRX-00$i",
                'user_id' => $this->member->id,
                'book_id' => $b->id,
                'borrow_date' => now()->toDateString(),
                'due_date' => now()->addDays(7)->toDateString(),
                'status' => 'borrowed',
            ]);
        }

        // Try to borrow 4th book
        $response = $this->actingAs($this->member)->post(route('user.borrow.store', $this->book->id));
        $response->assertSessionHas('error');

        // Stock should remain 5
        $this->assertEquals(5, $this->book->fresh()->available_stock);
    }

    public function test_admin_can_approve_and_return_borrowing(): void
    {
        $borrowing = Borrowing::create([
            'borrow_code' => 'TRX-1001',
            'user_id' => $this->member->id,
            'book_id' => $this->book->id,
            'borrow_date' => now()->toDateString(),
            'due_date' => now()->addDays(7)->toDateString(),
            'status' => 'pending',
        ]);
        $this->book->decrement('available_stock');

        // Admin approves
        $response = $this->actingAs($this->admin)->post(route('admin.transactions.approve', $borrowing->id));
        $response->assertSessionHas('success');
        $this->assertEquals('borrowed', $borrowing->fresh()->status);

        // Admin processes return
        $response = $this->actingAs($this->admin)->post(route('admin.transactions.return', $borrowing->id), [
            'condition_status' => 'returned',
            'penalty_amount' => 0,
        ]);

        $response->assertSessionHas('success');
        $this->assertEquals('returned', $borrowing->fresh()->status);
        $this->assertEquals(5, $this->book->fresh()->available_stock);
    }
}
