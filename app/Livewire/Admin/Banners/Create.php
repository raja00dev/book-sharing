<?php

namespace App\Livewire\Admin\Banners;

use App\Livewire\Admin\Concerns\HasAdminShell;
use App\Models\Banner;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;

class Create extends Component
{
    use HasAdminShell, WithFileUploads;

    public string $text = '';

    public mixed $image = null;

    public bool $isBig = false;

    public bool $status = true;

    public string $statusMessage = '';

    public function mount(): void
    {
        $this->ensureAdmin();
    }

    public function save(): void
    {
        $this->ensureAdmin();

        $validated = $this->validate([
            'text' => ['nullable', 'string', 'max:255'],
            'image' => ['required', 'image', 'max:5120'],
            'isBig' => ['boolean'],
            'status' => ['boolean'],
        ]);

        $imagePath = $this->image->storePublicly('banners', 'public');

        Banner::create([
            'text' => $validated['text'] ?: null,
            'image' => $imagePath,
            'isBig' => $validated['isBig'],
            'status' => $validated['status'],
        ]);

        $this->reset('text', 'image', 'isBig');
        $this->status = true;
        $this->statusMessage = 'بنر با موفقیت اضافه شد.';
    }

    public function render(): View
    {
        $this->ensureAdmin();

        return view('livewire.admin.banners.create')
            ->layout('layouts.app', ['title' => 'افزودن بنر | پنل مدیریت']);
    }
}
