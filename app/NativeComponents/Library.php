<?php

namespace App\NativeComponents;

use App\Models\Book;
use Illuminate\Database\Eloquent\Collection;
use Native\Mobile\Edge\Element;
use Native\Mobile\Edge\NativeComponent;

class Library extends NativeComponent
{
    public string $title = '';

    public string $author = '';

    public string $review = '';

    public int $rating = 5;

    public string $error = '';

    public int $formVersion = 0;

    public function setRating(int $rating): void
    {
        if ($rating >= 1 && $rating <= 5) {
            $this->rating = $rating;
        }
    }

    public function add(): void
    {
        $title = trim($this->title);
        $author = trim($this->author);
        $review = trim($this->review);

        if ($title === '' || $author === '' || $review === '') {
            $this->error = 'Add a title, author and a few thoughts.';

            return;
        }

        foreach (['title' => [$title, 160], 'author' => [$author, 120], 'review' => [$review, 2000]] as $field => [$value, $limit]) {
            if (mb_strlen($value) > $limit) {
                $this->error = ucfirst($field)." must be under {$limit} characters.";

                return;
            }
        }

        Book::query()->create(compact('title', 'author', 'review') + ['rating' => $this->rating]);

        $this->title = $this->author = $this->review = $this->error = '';
        $this->rating = 5;
        $this->formVersion++;
    }

    public function remove(int $id): void
    {
        Book::query()->findOrFail($id)->delete();
    }

    public function render(): Element
    {
        /** @var Collection<int, Book> $books */
        $books = Book::query()->latest()->get();

        return $this->view('library', [
            'books' => $books,
            'bookCount' => $books->count(),
        ]);
    }
}
