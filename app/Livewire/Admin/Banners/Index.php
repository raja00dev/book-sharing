<?php

namespace App\Livewire\Admin\Banners;

use App\Livewire\Admin\Concerns\HasAdminShell;
use App\Models\Banner;
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
        Banner::query()->findOrFail($id)->delete();
        session()->flash('resourceStatus', 'مورد انتخابی با موفقیت حذف شد.');
        $this->resetPage();
    }

    public function render(): View
    {
        $this->ensureAdmin();

        $query = Banner::query();
        if (trim($this->search) !== '') {
            $query->where('text', 'like', '%'.trim($this->search).'%');
        }

        return view('livewire.admin.shared.index', [
            'resource' => 'banners',
            'resourceTitle' => 'بنرها',
            'createRoute' => 'admin.banners.create',
            'editRoute' => 'admin.banners.edit',
            'items' => $query->orderByDesc('id')->paginate(15),
        ])->layout('layouts.app', ['title' => 'بنرها | پنل مدیریت']);
    }
}
