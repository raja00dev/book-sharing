<?php

namespace App\Livewire;

use App\Models\Book;
use Illuminate\View\View;
use Livewire\Component;

class BookOrder extends Component
{
    public Book $book;

    public function mount(Book $book): void
    {
        abort_unless($book->is_active, 404);
        $this->book = $book->load('author');
    }

    public function render(): View
    {
        return view('livewire.book-order', ['book' => $this->book])
            ->layout('layouts.app', ['title' => 'سفارش '.$this->book->title.' | '.config('app.name')]);
    }
}
