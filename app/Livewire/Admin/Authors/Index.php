<?php

namespace App\Livewire\Admin\Authors;

use App\Livewire\Admin\Concerns\HasAdminShell;
use App\Models\Author;
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
        Author::query()->findOrFail($id)->delete();
        session()->flash('resourceStatus', 'مورد انتخابی با موفقیت حذف شد.');
        $this->resetPage();
    }

    public function render(): View
    {
        $this->ensureAdmin();

        $query = Author::query()->withCount('books');
        if (trim($this->search) !== '') {
            $query->where('name', 'like', '%'.trim($this->search).'%');
        }

        return view('livewire.admin.shared.index', [
            'resource' => 'authors',
            'resourceTitle' => 'نویسندگان',
            'createRoute' => 'admin.authors.create',
            'editRoute' => 'admin.authors.edit',
            'items' => $query->orderByDesc('id')->paginate(15),
        ])->layout('layouts.app', ['title' => 'نویسندگان | پنل مدیریت']);
    }
}
