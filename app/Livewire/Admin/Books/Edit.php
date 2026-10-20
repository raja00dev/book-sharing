<?php

namespace App\Livewire\Admin\Books;

use App\Livewire\Admin\Concerns\HasAdminShell;
use App\Models\Author;
use App\Models\Book;
use App\Models\Categories;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;

class Edit extends Component
{
    use HasAdminShell, WithFileUploads;

    public string $resource = 'books';

    public int $recordId = 0;

    public string $title = '';

    public string $description = '';

    public string $authorId = '';

    public string $price = '';

    public string $stock = '1';

    public string $publisher = '';

    public string $publicationYear = '';

    public string $pages = '';

    public array $categoryIds = [];

    public mixed $coverImage = null;

    public ?string $currentCoverImage = null;

    public function mount(int $id): void
    {
        $this->ensureAdmin();
        $book = Book::query()->findOrFail($id);
        $this->recordId = $book->id;
        $this->title = $book->title;
        $this->description = (string) ($book->description ?? '');
        $this->authorId = (string) $book->author_id;
        $this->price = (string) $book->price;
        $this->stock = (string) $book->stock;
        $this->publisher = (string) ($book->publisher ?? '');
        $this->publicationYear = (string) ($book->publication_year ?? '');
        $this->pages = (string) ($book->pages ?? '');
        $this->currentCoverImage = $book->cover_image;
        $this->categoryIds = $book->categories()->pluck('categories.id')->map(fn ($categoryId) => (string) $categoryId)->all();
    }

    public function save(): void
    {
        $this->ensureAdmin();
        $validated = $this->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'authorId' => ['required', 'integer', 'exists:authors,id'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'publisher' => ['nullable', 'string', 'max:255'],
            'publicationYear' => ['nullable', 'integer', 'min:1000', 'max:9999'],
            'pages' => ['nullable', 'integer', 'min:1'],
            'coverImage' => ['nullable', 'image', 'max:5120'],
            'categoryIds' => ['array'],
            'categoryIds.*' => ['integer', 'exists:categories,id'],
        ]);

        $book = Book::query()->findOrFail($this->recordId);
        $book->forceFill([
            'title' => $validated['title'],
            'description' => $validated['description'] ?: null,
            'author_id' => (int) $validated['authorId'],
            'price' => $validated['price'],
            'stock' => (int) $validated['stock'],
            'publisher' => $validated['publisher'] ?: null,
            'publication_year' => $validated['publicationYear'] ?: null,
            'pages' => $validated['pages'] ?: null,
            'cover_image' => $this->coverImage?->storePublicly('books', 'public') ?? $book->cover_image,
        ])->save();
        $book->categories()->sync($validated['categoryIds'] ?? []);

        session()->flash('resourceStatus', 'تغییرات با موفقیت ذخیره شد.');
        $this->redirectRoute('admin.books.index');
    }

    public function render(): View
    {
        $this->ensureAdmin();

        return view('livewire.admin.shared.edit', [
            'resource' => $this->resource,
            'resourceTitle' => 'کتاب‌ها',
            'authors' => Author::query()->orderBy('name')->get(['id', 'name']),
            'categories' => Categories::query()->orderBy('name')->get(['id', 'name']),
        ])->layout('layouts.app', ['title' => 'ویرایش کتاب | پنل مدیریت']);
    }
}
