<?php

namespace App\Livewire\Admin\Banners;

use App\Livewire\Admin\Concerns\HasAdminShell;
use App\Models\Banner as BannerModel;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;

class Edit extends Component
{
    use HasAdminShell, WithFileUploads;

    public string $resource = 'banners';

    public int $recordId = 0;

    public string $text = '';

    public mixed $image = null;

    public ?string $currentImage = null;

    public bool $isBig = false;

    public bool $status = true;

    public function mount(int $id): void
    {
        $this->ensureAdmin();
        $banner = BannerModel::query()->findOrFail($id);
        $this->recordId = $banner->id;
        $this->text = (string) ($banner->text ?? '');
        $this->currentImage = $banner->image;
        $this->isBig = (bool) $banner->isBig;
        $this->status = (bool) $banner->status;
    }

    public function save(): void
    {
        $this->ensureAdmin();
        $validated = $this->validate([
            'text' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'max:5120'],
            'isBig' => ['boolean'],
            'status' => ['boolean'],
        ]);

        $banner = BannerModel::query()->findOrFail($this->recordId);
        $banner->forceFill([
            'text' => $validated['text'] ?: null,
            'image' => $this->image?->storePublicly('banners', 'public') ?? $banner->image,
            'isBig' => (bool) $validated['isBig'],
            'status' => (bool) $validated['status'],
        ])->save();

        session()->flash('resourceStatus', 'تغییرات با موفقیت ذخیره شد.');
        $this->redirectRoute('admin.banners.index');
    }

    public function render(): View
    {
        $this->ensureAdmin();

        return view('livewire.admin.shared.edit', [
            'resource' => $this->resource,
            'resourceTitle' => 'بنرها',
            'authors' => collect(),
            'categories' => collect(),
        ])->layout('layouts.app', ['title' => 'ویرایش بنر | پنل مدیریت']);
    }
}
