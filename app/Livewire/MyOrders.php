<?php

namespace App\Livewire;

use App\Models\Book;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class MyOrders extends Component
{
    use WithPagination;

    public function delete(int $id): void
    {
        $user = auth()->user();
        abort_unless($user, 403);

        $deleted = DB::transaction(function () use ($user, $id): bool {
            $order = $user->orders()
                ->with('items')
                ->lockForUpdate()
                ->findOrFail($id);

            if ($order->status !== 'pending') {
                return false;
            }

            foreach ($order->items as $item) {
                Book::query()
                    ->lockForUpdate()
                    ->findOrFail($item->book_id)
                    ->increment('stock');
            }

            $order->delete();

            return true;
        });

        session()->flash(
            'orderStatus',
            $deleted
                ? 'سفارش حذف شد و موجودی کتاب‌ها بازگشت داده شد.'
                : 'فقط سفارش‌های در انتظار بررسی قابل حذف هستند.'
        );
    }

    public function render(): View
    {
        $orders = auth()->user()
            ->orders()
            ->with('items.book')
            ->latest()
            ->paginate(10);

        return view('livewire.my-orders', ['orders' => $orders])
            ->layout('layouts.app', ['title' => 'سفارش‌های من | '.config('app.name')]);
    }
}
