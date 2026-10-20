<?php

namespace App\Livewire\Admin\Categories;

use App\Livewire\Admin\Concerns\HasAdminShell;
use App\Models\Categories;
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
        Categories::query()->findOrFail($id)->delete();
        session()->flash('resourceStatus', 'مورد انتخابی با موفقیت حذف شد.');
        $this->resetPage();
    }

    public function render(): View
    {
        $this->ensureAdmin();

        $query = Categories::query()->withCount([
            'books as active_books_count' => fn (Builder $builder) => $builder->where('is_active', true),
        ]);
        if (trim($this->search) !== '') {
            $query->where('name', 'like', '%'.trim($this->search).'%');
        }

        return view('livewire.admin.shared.index', [
            'resource' => 'categories',
            'resourceTitle' => 'دسته‌بندی‌ها',
            'createRoute' => 'admin.categories.create',
            'editRoute' => 'admin.categories.edit',
            'items' => $query->orderByDesc('id')->paginate(15),
        ])->layout('layouts.app', ['title' => 'دسته‌بندی‌ها | پنل مدیریت']);
    }
}
