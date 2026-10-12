<?php

namespace Tests\Feature;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_books_can_be_listed_and_viewed(): void
    {
        $book = Book::create([
            'title' => 'Belajar Laravel',
            'description' => 'Pengantar API Laravel',
            'status' => 1,
        ]);

        $this->getJson('/api/books')
            ->assertOk()
            ->assertJsonFragment(['title' => 'Belajar Laravel']);

        $this->getJson("/api/books/{$book->id}")
            ->assertOk()
            ->assertJsonPath('id', $book->id)
            ->assertJsonPath('status', 1);
    }

    public function test_a_book_can_be_created_updated_and_deleted(): void
    {
        $this->postJson('/api/books', [
            'title' => 'Belajar Laravel',
            'description' => 'Pengantar API Laravel',
            'status' => 1,
        ])
            ->assertCreated()
            ->assertJsonPath('title', 'Belajar Laravel');

        $book = Book::firstOrFail();

        $this->patchJson("/api/books/{$book->id}", [
            'title' => 'Laravel REST API',
        ])
            ->assertOk()
            ->assertJsonPath('title', 'Laravel REST API')
            ->assertJsonPath('description', 'Pengantar API Laravel');

        $this->deleteJson("/api/books/{$book->id}")
            ->assertOk()
            ->assertJson(['message' => 'Book deleted']);

        $this->assertDatabaseMissing('books', ['id' => $book->id]);
    }

    public function test_a_book_status_can_be_updated_alone_with_put(): void
    {
        $book = Book::create([
            'title' => 'Belajar Laravel',
            'description' => 'Pengantar API Laravel',
            'status' => 1,
        ]);

        $this->putJson("/api/books/{$book->id}", ['status' => 0])
            ->assertOk()
            ->assertJsonPath('id', $book->id)
            ->assertJsonPath('title', 'Belajar Laravel')
            ->assertJsonPath('description', 'Pengantar API Laravel')
            ->assertJsonPath('status', 0);
    }

    public function test_creating_a_book_requires_valid_fields(): void
    {
        $this->postJson('/api/books', [])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['title', 'description', 'status']);

        $this->postJson('/api/books', [
            'title' => 'Buku tidak valid',
            'description' => 'Status tidak boleh negatif',
            'status' => -1,
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['status']);
    }

    public function test_missing_books_return_a_json_not_found_response(): void
    {
        $this->getJson('/api/books/999')
            ->assertNotFound()
            ->assertJson(['message' => 'Book not found']);

        $this->patchJson('/api/books/999', ['title' => 'Tidak ditemukan'])
            ->assertNotFound()
            ->assertJson(['message' => 'Book not found']);

        $this->deleteJson('/api/books/999')
            ->assertNotFound()
            ->assertJson(['message' => 'Book not found']);
    }
}
