<?php

namespace App\Livewire;

use App\Models\Book;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class BookDetail extends Component
{
    use WithPagination;

    public Book $book;

    public string $newComment = '';

    protected $paginationTheme = 'tailwind';

    public function mount(Book $book): void
    {
        $this->book = $book->loadMissing('author');
    }

    public function addComment(): void
    {
        $this->validate([
            'newComment' => ['required', 'string', 'min:3', 'max:1000'],
        ]);

        auth()->user()->comments()->create([
            'book_id' => $this->book->id,
            'body' => $this->newComment,
        ]);

        $this->newComment = '';
        $this->resetPage();
    }

    public function render(): View
    {
        $comments = $this->book->comments()
            ->where('is_blocked', false)
            ->with('user')
            ->latest()
            ->paginate(10)
            ->withPath(route('books.show', $this->book));

        return view('livewire.book-detail', [
            'book' => $this->book,
            'comments' => $comments,
        ])->layout('layouts.app', ['title' => $this->book->title.' | '.config('app.name')]);
    }
}
