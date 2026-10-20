<?php

namespace App\Livewire\Admin\Authors;

use App\Livewire\Admin\Concerns\HasAdminShell;
use App\Models\Author;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;

class Create extends Component
{
    use HasAdminShell, WithFileUploads;

    public string $name = '';

    public string $bio = '';

    public mixed $image = null;

    public string $statusMessage = '';

    public function mount(): void
    {
        $this->ensureAdmin();
    }

    public function save(): void
    {
        $this->ensureAdmin();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'bio' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'max:5120'],
        ]);

        $imagePath = $this->image?->storePublicly('authors', 'public');

        Author::create([
            'name' => $validated['name'],
            'bio' => $validated['bio'] ?: null,
            'image' => $imagePath,
        ]);

        $this->reset('name', 'bio', 'image');
        $this->statusMessage = 'نویسنده با موفقیت اضافه شد.';
    }

    public function render(): View
    {
        $this->ensureAdmin();

        return view('livewire.admin.authors.create')
            ->layout('layouts.app', ['title' => 'افزودن نویسنده | پنل مدیریت']);
    }
}
