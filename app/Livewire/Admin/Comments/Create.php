<?php

namespace App\Livewire\Admin\Comments;

use App\Livewire\Admin\Concerns\HasAdminShell;
use App\Models\Book;
use App\Models\Comment;
use App\Models\User;
use Illuminate\View\View;
use Livewire\Component;

class Create extends Component
{
    use HasAdminShell;

    public string $bookId = '';

    public string $userId = '';

    public string $body = '';

    public bool $isBlocked = false;

    public function mount(): void
    {
        $this->ensureAdmin();
    }

    public function save(): void
    {
        $this->ensureAdmin();
        $validated = $this->validate([
            'bookId' => ['required', 'integer', 'exists:books,id'],
            'userId' => ['required', 'integer', 'exists:users,id'],
            'body' => ['required', 'string', 'min:3', 'max:1000'],
            'isBlocked' => ['boolean'],
        ]);

        Comment::query()->create([
            'book_id' => $validated['bookId'],
            'user_id' => $validated['userId'],
            'body' => $validated['body'],
            'is_blocked' => (bool) $validated['isBlocked'],
        ]);

        session()->flash('resourceStatus', 'نظر با موفقیت اضافه شد.');
        $this->redirectRoute('admin.comments.index');
    }

    public function render(): View
    {
        $this->ensureAdmin();

        return view('livewire.admin.comments.create', [
            'books' => Book::query()->orderBy('title')->get(['id', 'title']),
            'users' => User::query()->orderBy('name')->get(['id', 'name', 'phone']),
        ])->layout('layouts.app', ['title' => 'افزودن نظر | پنل مدیریت']);
    }
}
