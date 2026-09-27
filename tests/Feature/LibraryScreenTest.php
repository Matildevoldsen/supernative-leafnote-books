<?php

namespace Tests\Feature;

use App\Models\Book;
use App\NativeComponents\Library;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Native\Mobile\Testing\Native;
use Tests\TestCase;

class LibraryScreenTest extends TestCase
{
    use RefreshDatabase;

    public function test_book_review_can_be_saved_with_selected_rating(): void
    {
        Native::test(Library::class)
            ->input('title-input', 'The Left Hand of Darkness')
            ->input('author-input', 'Ursula K. Le Guin')
            ->input('review-input', 'A story about trust and belonging.')
            ->tap('rating-4')
            ->tap('save-book')
            ->assertSee('The Left Hand of Darkness');

        $book = Book::query()->sole();

        $this->assertSame(4, $book->rating);
        $this->assertSame('A story about trust and belonging.', $book->review);
    }

    public function test_incomplete_review_is_not_saved(): void
    {
        Native::test(Library::class)
            ->tap('save-book')
            ->assertSee('Add a title, author and a few thoughts.');

        $this->assertDatabaseCount('books', 0);
    }

    public function test_book_title_limit_has_specific_feedback(): void
    {
        Native::test(Library::class)
            ->input('title-input', str_repeat('a', 161))
            ->input('author-input', 'An author')
            ->input('review-input', 'A short review')
            ->tap('save-book')
            ->assertSee('Title must be under 160 characters.');

        $this->assertDatabaseCount('books', 0);
    }
}
