<?php

namespace App\Livewire\Admin\Categories;

use App\Livewire\Admin\Concerns\HasAdminShell;
use App\Models\Categories;
use Illuminate\View\View;
use Livewire\Component;

class Create extends Component
{
    use HasAdminShell;

    public string $name = '';

    public string $statusMessage = '';

    public function mount(): void
    {
        $this->ensureAdmin();
    }

    public function save(): void
    {
        $this->ensureAdmin();
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255', 'unique:categories,name'],
        ]);

        Categories::query()->create(['name' => $validated['name']]);
        $this->reset('name');
        $this->statusMessage = 'دسته‌بندی با موفقیت اضافه شد.';
    }

    public function render(): View
    {
        $this->ensureAdmin();

        return view('livewire.admin.categories.create')
            ->layout('layouts.app', ['title' => 'افزودن دسته‌بندی | پنل مدیریت']);
    }
}
