<?php

namespace App\Livewire\Admin\Comments;

use App\Livewire\Admin\Concerns\HasAdminShell;
use App\Models\Comment;
use Illuminate\View\View;
use Livewire\Component;

class Edit extends Component
{
    use HasAdminShell;

    public int $commentId = 0;

    public string $body = '';

    public bool $isBlocked = false;

    public function mount(int $id): void
    {
        $this->ensureAdmin();
        $comment = Comment::query()->findOrFail($id);
        $this->commentId = $comment->id;
        $this->body = $comment->body;
        $this->isBlocked = $comment->is_blocked;
    }

    public function save(): void
    {
        $this->ensureAdmin();
        $validated = $this->validate([
            'body' => ['required', 'string', 'min:3', 'max:1000'],
            'isBlocked' => ['boolean'],
        ]);

        Comment::query()->findOrFail($this->commentId)->forceFill([
            'body' => $validated['body'],
            'is_blocked' => (bool) $validated['isBlocked'],
        ])->save();

        session()->flash('resourceStatus', 'نظر با موفقیت ویرایش شد.');
        $this->redirectRoute('admin.comments.index');
    }

    public function render(): View
    {
        $this->ensureAdmin();

        return view('livewire.admin.comments.edit', [
            'comment' => Comment::query()->with(['book', 'user'])->findOrFail($this->commentId),
        ])->layout('layouts.app', ['title' => 'ویرایش نظر | پنل مدیریت']);
    }
}
