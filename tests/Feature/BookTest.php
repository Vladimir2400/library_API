<?php

namespace Tests\Feature;

use App\Models\Book;
use Tests\TestCase;

class BookTest extends TestCase
{
    protected $librarian;

    protected function setUp(): void
    {
        parent::setUp();
        $this->librarian = \App\Models\Librarian::factory()->create();
    }

    public function test_can_create_book()
    {
        $response = $this->actingAs($this->librarian, 'librarian_api')
            ->postJson('/books', [
                'title' => 'Test Book',
                'description' => 'This is a test book',
                'total_copies' => 5,
            ]);

        $response->assertStatus(201)
                 ->assertJsonStructure([
                     'message',
                     'book' => [
                         'id',
                         'title',
                         'description',
                         'total_copies',
                         'available_copies',
                         'created_at',
                         'updated_at',
                     ],
                 ]);

        $this->assertDatabaseHas('books', [
            'title' => 'Test Book',
            'description' => 'This is a test book',
            'total_copies' => 5,
            'available_copies' => 5,
        ]);
    }

    public function test_can_update_book()
    {
        $book = Book::factory()->create();

        $response = $this->actingAs($this->librarian, 'librarian_api')
            ->putJson("/books/{$book->id}", [
                'title' => 'Updated Book',
                'description' => 'This is an updated book',
                'total_copies' => 10,
            ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'message',
                     'book' => [
                         'id',
                         'title',
                         'description',
                         'total_copies',
                         'available_copies',
                         'created_at',
                         'updated_at',
                     ],
                 ]);

        $this->assertDatabaseHas('books', [
            'id' => $book->id,
            'title' => 'Updated Book',
            'description' => 'This is an updated book',
            'total_copies' => 10,
            'available_copies' => 10,
        ]);
    }

    public function test_can_delete_book()
    {
        $book = Book::factory()->create();

        $response = $this->actingAs($this->librarian, 'librarian_api')
            ->deleteJson("/books/{$book->id}");

        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'Book successfully deleted'
                 ]);

        $this->assertDatabaseMissing('books', [
            'id' => $book->id,
        ]);
    }
}