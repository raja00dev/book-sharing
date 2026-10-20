<?php

namespace App\Livewire\Admin\Authors;

use App\Livewire\Admin\Concerns\HasAdminShell;
use App\Models\Author as AuthorModel;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;

class Edit extends Component
{
    use HasAdminShell, WithFileUploads;

    public string $resource = 'authors';

    public int $recordId = 0;

    public string $name = '';

    public string $bio = '';

    public mixed $image = null;

    public ?string $currentImage = null;

    public function mount(int $id): void
    {
        $this->ensureAdmin();
        $author = AuthorModel::query()->findOrFail($id);
        $this->recordId = $author->id;
        $this->name = $author->name;
        $this->bio = (string) ($author->bio ?? '');
        $this->currentImage = $author->image;
    }

    public function save(): void
    {
        $this->ensureAdmin();
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'bio' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'max:5120'],
        ]);

        $author = AuthorModel::query()->findOrFail($this->recordId);
        $author->forceFill([
            'name' => $validated['name'],
            'bio' => $validated['bio'] ?: null,
            'image' => $this->image?->storePublicly('authors', 'public') ?? $author->image,
        ])->save();

        session()->flash('resourceStatus', 'تغییرات با موفقیت ذخیره شد.');
        $this->redirectRoute('admin.authors.index');
    }

    public function render(): View
    {
        $this->ensureAdmin();

        return view('livewire.admin.shared.edit', [
            'resource' => $this->resource,
            'resourceTitle' => 'نویسندگان',
            'authors' => collect(),
            'categories' => collect(),
        ])->layout('layouts.app', ['title' => 'ویرایش نویسنده | پنل مدیریت']);
    }
}
