<?php

namespace App\Livewire\Admin\Orders;

use App\Livewire\Admin\Concerns\HasAdminShell;
use App\Models\Order;
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
        Order::query()->findOrFail($id)->delete();
        session()->flash('resourceStatus', 'مورد انتخابی با موفقیت حذف شد.');
        $this->resetPage();
    }

    public function render(): View
    {
        $this->ensureAdmin();

        $query = Order::query()->with('user');
        $search = trim($this->search);
        if ($search !== '') {
            $term = '%'.$search.'%';
            $query->where(fn (Builder $builder) => $builder
                ->where('recipient_name', 'like', $term)
                ->orWhere('phone', 'like', $term));
        }

        return view('livewire.admin.shared.index', [
            'resource' => 'orders',
            'resourceTitle' => 'سفارش‌ها',
            'createRoute' => null,
            'editRoute' => 'admin.orders.edit',
            'items' => $query->orderByDesc('id')->paginate(15),
        ])->layout('layouts.app', ['title' => 'سفارش‌ها | پنل مدیریت']);
    }
}
