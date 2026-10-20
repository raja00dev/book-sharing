<?php

namespace App\Livewire\Admin\Orders;

use App\Livewire\Admin\Concerns\HasAdminShell;
use App\Models\Order as OrderModel;
use Illuminate\View\View;
use Livewire\Component;

class Edit extends Component
{
    use HasAdminShell;

    public string $resource = 'orders';

    public int $recordId = 0;

    public string $recipientName = '';

    public string $phone = '';

    public string $totalPrice = '';

    public string $statusValue = 'pending';

    public string $shippingAddress = '';

    public function mount(int $id): void
    {
        $this->ensureAdmin();
        $order = OrderModel::query()->findOrFail($id);
        $this->recordId = $order->id;
        $this->recipientName = $order->recipient_name;
        $this->phone = $order->phone;
        $this->totalPrice = (string) $order->total_price;
        $this->statusValue = (string) ($order->status ?? 'pending');
        $this->shippingAddress = (string) ($order->shipping_address ?? '');
    }

    public function save(): void
    {
        $this->ensureAdmin();
        $validated = $this->validate([
            'recipientName' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'regex:/^09[0-9]{9}$/'],
            'totalPrice' => ['required', 'numeric', 'min:0'],
            'statusValue' => ['required', 'string', 'in:pending,paid,processing,shipped,delivered,cancelled'],
            'shippingAddress' => ['nullable', 'string'],
        ]);

        OrderModel::query()->findOrFail($this->recordId)->forceFill([
            'recipient_name' => $validated['recipientName'],
            'phone' => $validated['phone'],
            'total_price' => $validated['totalPrice'],
            'status' => $validated['statusValue'],
            'shipping_address' => $validated['shippingAddress'] ?: null,
        ])->save();

        session()->flash('resourceStatus', 'تغییرات با موفقیت ذخیره شد.');
        $this->redirectRoute('admin.orders.index');
    }

    public function render(): View
    {
        $this->ensureAdmin();

        return view('livewire.admin.shared.edit', [
            'resource' => $this->resource,
            'resourceTitle' => 'سفارش‌ها',
            'authors' => collect(),
            'categories' => collect(),
        ])->layout('layouts.app', ['title' => 'ویرایش سفارش | پنل مدیریت']);
    }
}
