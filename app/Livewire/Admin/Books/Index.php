<?php

namespace App\Livewire\Admin\Books;

use App\Livewire\Admin\Concerns\HasAdminShell;
use App\Models\Book;
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

    public function delete(int $id): void
    {
        $this->ensureAdmin();
        Book::query()->findOrFail($id)->delete();
        session()->flash('resourceStatus', 'مورد انتخابی با موفقیت حذف شد.');
        $this->resetPage();
    }

    public function render(): View
    {
        $this->ensureAdmin();

        $query = Book::query()->with('author');
        $search = trim($this->search);

        if ($search !== '') {
            $term = '%'.$search.'%';
            $query->where(fn (Builder $builder) => $builder
                ->where('title', 'like', $term)
                ->orWhereHas('author', fn (Builder $authorQuery) => $authorQuery->where('name', 'like', $term)));
        }

        return view('livewire.admin.shared.index', [
            'resource' => 'books',
            'resourceTitle' => 'کتاب‌ها',
            'createRoute' => 'admin.books.create',
            'editRoute' => 'admin.books.edit',
            'items' => $query->orderByDesc('id')->paginate(15),
        ])->layout('layouts.app', ['title' => 'کتاب‌ها | پنل مدیریت']);
    }
}
