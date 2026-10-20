<?php

namespace App\Livewire\Admin\Books;

use App\Livewire\Admin\Concerns\HasAdminShell;
use App\Models\Author;
use App\Models\Book;
use App\Models\Categories;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;

class Create extends Component
{
    use HasAdminShell, WithFileUploads;

    public string $title = '';

    public string $description = '';

    public string $authorId = '';

    public string $price = '';

    public string $stock = '1';

    public string $publisher = '';

    public string $publicationYear = '';

    public string $pages = '';

    public mixed $coverImage = null;

    public array $categoryIds = [];

    public string $statusMessage = '';

    public function mount(): void
    {
        $this->ensureAdmin();
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
            'coverImage' => ['required', 'image', 'max:5120'],
            'categoryIds' => ['array'],
            'categoryIds.*' => ['integer', 'exists:categories,id'],
        ]);

        $coverImagePath = $this->coverImage->storePublicly('books', 'public');

        $book = Book::create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?: null,
            'author_id' => $validated['authorId'],
            'price' => $validated['price'],
            'stock' => $validated['stock'],
            'publisher' => $validated['publisher'] ?: null,
            'publication_year' => $validated['publicationYear'] ?: null,
            'pages' => $validated['pages'] ?: null,
            'cover_image' => $coverImagePath,
            'is_active' => true,
        ]);

        $book->categories()->sync($validated['categoryIds'] ?? []);
        $this->reset('title', 'description', 'authorId', 'price', 'stock', 'publisher', 'publicationYear', 'pages', 'coverImage', 'categoryIds');
        $this->stock = '1';
        $this->statusMessage = 'کتاب با موفقیت اضافه شد.';
    }

    public function render(): View
    {
        $this->ensureAdmin();

        return view('livewire.admin.books.create', [
            'authors' => Author::query()->orderBy('name')->get(['id', 'name']),
            'categories' => Categories::query()->orderBy('name')->get(['id', 'name']),
        ])->layout('layouts.app', ['title' => 'افزودن کتاب | پنل مدیریت']);
    }
}
