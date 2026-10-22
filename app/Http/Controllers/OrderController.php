<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Shetabit\Multipay\Invoice;
use Shetabit\Payment\Facade\Payment;

class OrderController extends Controller
{
    public function checkout(Request $request): RedirectResponse
    {

        $validated = $request->validate([
            'book_id' => ['required', 'integer', 'exists:books,id'],
            'recipient_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'regex:/^09[0-9]{9}$/'],
            'shipping_address' => ['required', 'string', 'max:2000'],
        ]);

        DB::transaction(function () use ($request, $validated): Order {
            $book = Book::query()->lockForUpdate()->findOrFail($validated['book_id']);

            if (! $book->is_active) {
                throw ValidationException::withMessages(['book_id' => 'این کتاب در حال حاضر قابل سفارش نیست.']);
            }

            if ($book->stock < 1) {
                throw ValidationException::withMessages(['book_id' => 'این کتاب موجودی ندارد.']);
            }

            $book->decrement('stock');

            $order = $request->user()->orders()->create([
                'total_price' => (int) $book->price,
                'status' => 'pending',
                'recipient_name' => $validated['recipient_name'],
                'phone' => $validated['phone'],
                'shipping_address' => $validated['shipping_address'],
            ]);
            $order->items()->create([
                'book_id' => $book->id,
            ]);

            return $order;
        });

        return redirect()->route('orders.mine')->with('orderStatus', 'سفارش با موفقیت ثبت شد و در انتظار بررسی است.');
    }

    public function pay(Request $request, int $orderId): Response|RedirectResponse
    {
        $order = $request->user()
            ->orders()
            ->findOrFail($orderId);

        if ($order->status !== 'pending') {
            return redirect()
                ->route('orders.mine')
                ->with('orderErrorStatus', 'این سفارش در وضعیت فعلی قابل پرداخت نیست.');
        }

        $invoice = (new Invoice)
            ->amount((int) $order->total_price)
            ->detail('orderId', $order->id);

        $result = Payment::via('local')
            ->callbackUrl(route('payment.verify'))
            ->purchase($invoice, function (object $driver, string|int|null $transactionId) use ($order): void {
                $order->update(['payment_token' => (string) $transactionId]);
            })
            ->pay()
            ->render();

        return response($result, 200)
            ->header('Content-Type', 'text/html; charset=UTF-8');
    }

    public function callback(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'transactionId' => ['required', 'string'],
            'cancel' => ['sometimes', 'in:true,1'],
        ]);

        $order = Order::query()
            ->where('payment_token', $validated['transactionId'])
            ->firstOrFail();

        if ($request->boolean('cancel')) {
            return redirect()
                ->route('orders.mine')
                ->with('orderErrorStatus', 'پرداخت توسط شما لغو شد.');
        }

        Payment::via('local')
            ->transactionId($validated['transactionId'])
            ->amount((int) $order->total_price)
            ->verify();

        DB::transaction(function () use ($validated): void {
            $order = Order::query()
                ->where('payment_token', $validated['transactionId'])
                ->lockForUpdate()
                ->firstOrFail();

            if ($order->status === 'pending') {
                $order->update(['status' => 'paid']);
            }
        });

        return redirect()
            ->route('orders.mine')
            ->with('orderStatus', 'پرداخت با موفقیت انجام شد.');
    }
}
