<?php

namespace App\Livewire\Admin\Comments;

use App\Livewire\Admin\Concerns\HasAdminShell;
use App\Models\Comment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use HasAdminShell, WithPagination;

    public string $search = '';

    public function mount(): void
    {
        $this->ensureAdmin();
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function toggleBlocked(int $id): void
    {
        $this->ensureAdmin();
        $comment = Comment::query()->findOrFail($id);
        $comment->is_blocked = ! $comment->is_blocked;
        $comment->save();

        session()->flash('resourceStatus', $comment->is_blocked
            ? 'نظر مسدود شد و در صفحه‌ی کتاب نمایش داده نمی‌شود.'
            : 'مسدودیت نظر برداشته شد.');
    }

    public function delete(int $id): void
    {
        $this->ensureAdmin();
        Comment::query()->findOrFail($id)->delete();
        session()->flash('resourceStatus', 'نظر با موفقیت حذف شد.');
        $this->resetPage();
    }

    public function render(): View
    {
        $this->ensureAdmin();

        $query = Comment::query()->with(['book', 'user']);
        $search = trim($this->search);

        if ($search !== '') {
            $term = '%'.$search.'%';
            $query->where(fn (Builder $builder) => $builder
                ->where('body', 'like', $term)
                ->orWhereHas('book', fn (Builder $bookQuery) => $bookQuery->where('title', 'like', $term))
                ->orWhereHas('user', fn (Builder $userQuery) => $userQuery->where('name', 'like', $term)));
        }

        return view('livewire.admin.comments.index', [
            'comments' => $query->orderByDesc('id')->paginate(15),
        ])->layout('layouts.app', ['title' => 'مدیریت نظرات | پنل مدیریت']);
    }
}
