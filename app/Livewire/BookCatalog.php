<?php

namespace App\Livewire;

use App\Models\Book;
use App\Models\Categories;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class BookCatalog extends Component
{
    use WithPagination;

    public string $search = '';

    #[Url(as: 'category')]
    public string $categoryId = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingCategoryId(): void
    {
        $this->resetPage();
    }

    public function render(): View
    {
        $query = Book::query()
            ->with(['author', 'categories'])
            ->where('is_active', true);

        $search = trim($this->search);
        if ($search !== '') {
            $term = '%'.$search.'%';
            $query->where(fn (Builder $builder) => $builder
                ->where('title', 'like', $term)
                ->orWhereHas('author', fn (Builder $authorQuery) => $authorQuery->where('name', 'like', $term)));
        }

        if ($this->categoryId !== '') {
            $query->whereHas('categories', fn (Builder $categoryQuery) => $categoryQuery->whereKey($this->categoryId));
        }

        $query->latest('id');

        return view('livewire.book-catalog', [
            'books' => $query->paginate(12),
            'categories' => Categories::query()->orderBy('name')->get(['id', 'name']),
        ])->layout('layouts.app', ['title' => 'جست‌وجوی کتاب‌ها | '.config('app.name')]);
    }
}
