<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\BorrowedBook;
use Tests\TestCase;

class UserTest extends TestCase
{
    protected $user;
    protected $librarian;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = \App\Models\User::factory()->create();
        $this->librarian = \App\Models\Librarian::factory()->create();
    }

    public function test_can_borrow_book()
    {
        $book = Book::factory()->create(['total_copies' => 1, 'available_copies' => 1]);

        $response = $this->actingAs($this->user, 'api')
            ->postJson("/books/{$book->id}/borrow", [
                'return_date' => now()->addDays(7)->format('Y-m-d'),
            ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'Book successfully borrowed'
                 ]);

        $this->assertDatabaseHas('borrowed_books', [
            'user_id' => $this->user->id,
            'book_id' => $book->id,
            'returned' => false,
        ]);

        $this->assertDatabaseHas('books', [
            'id' => $book->id,
            'available_copies' => 0,
        ]);
    }

    public function test_can_return_book()
    {
        $book = Book::factory()->create(['total_copies' => 1, 'available_copies' => 0]);
        $borrowedBook = BorrowedBook::create([
            'user_id' => $this->user->id,
            'book_id' => $book->id,
            'borrow_date' => now(),
            'return_date' => now()->addDays(7),
            'returned' => false,
        ]);

        $response = $this->actingAs($this->user, 'api')
            ->postJson("/books/{$book->id}/return");

        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'Book successfully returned'
                 ]);

        $this->assertDatabaseHas('borrowed_books', [
            'id' => $borrowedBook->id,
            'returned' => true,
        ]);

        $this->assertDatabaseHas('books', [
            'id' => $book->id,
            'available_copies' => 1,
        ]);
    }
}