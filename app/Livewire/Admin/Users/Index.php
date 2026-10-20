<?php

namespace App\Livewire\Admin\Users;

use App\Livewire\Admin\Concerns\HasAdminShell;
use App\Models\User;
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
        User::query()->findOrFail($id)->delete();
        session()->flash('resourceStatus', 'مورد انتخابی با موفقیت حذف شد.');
        $this->resetPage();
    }

    public function render(): View
    {
        $this->ensureAdmin();

        $query = User::query()->select(['id', 'name', 'phone', 'is_admin', 'created_at']);
        $search = trim($this->search);
        if ($search !== '') {
            $term = '%'.$search.'%';
            $query->where(fn (Builder $builder) => $builder
                ->where('name', 'like', $term)
                ->orWhere('phone', 'like', $term));
        }

        return view('livewire.admin.shared.index', [
            'resource' => 'users',
            'resourceTitle' => 'کاربران',
            'createRoute' => null,
            'editRoute' => 'admin.users.edit',
            'items' => $query->orderByDesc('id')->paginate(15),
        ])->layout('layouts.app', ['title' => 'کاربران | پنل مدیریت']);
    }
}
