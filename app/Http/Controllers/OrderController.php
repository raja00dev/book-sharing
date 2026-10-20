<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    public function checkout(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'book_id' => ['required', 'integer', 'exists:books,id'],
            'quantity' => ['required', 'integer', 'min:1', 'max:20'],
            'recipient_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'regex:/^09[0-9]{9}$/'],
            'shipping_address' => ['required', 'string', 'max:2000'],
        ]);

        DB::transaction(function () use ($request, $validated): Order {
            $book = Book::query()->lockForUpdate()->findOrFail($validated['book_id']);

            if (! $book->is_active) {
                throw ValidationException::withMessages(['book_id' => 'این کتاب در حال حاضر قابل سفارش نیست.']);
            }

            if ($book->stock < $validated['quantity']) {
                throw ValidationException::withMessages(['quantity' => 'تعداد درخواستی از موجودی کتاب بیشتر است.']);
            }

            $book->decrement('stock', $validated['quantity']);

            $order = $request->user()->orders()->create([
                'total_price' => (float) $book->price * $validated['quantity'],
                'status' => 'pending',
                'recipient_name' => $validated['recipient_name'],
                'phone' => $validated['phone'],
                'shipping_address' => $validated['shipping_address'],
            ]);

            $order->items()->create([
                'book_id' => $book->id,
                'quantity' => $validated['quantity'],
                'unit_price' => $book->price,
            ]);

            return $order;
        });

        return redirect()->route('orders.mine')->with('orderStatus', 'سفارش با موفقیت ثبت شد و در انتظار بررسی است.');
    }
}
