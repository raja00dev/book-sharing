<?php

use App\Livewire\MyOrders;
use App\Models\Author;
use App\Models\Book;
use App\Models\Order;
use App\Models\User;
use Livewire\Livewire;

test('an authenticated user can submit a book order without a payment integration', function () {
    $user = User::factory()->create(['phone' => '09120000001', 'name' => 'خریدار']);
    $author = Author::create(['name' => 'نویسنده']);
    $book = Book::create([
        'title' => 'کتاب خریدنی',
        'author_id' => $author->id,
        'price' => 125000,
        'stock' => 3,
        'is_active' => true,
    ]);

    $this->actingAs($user)
        ->get(route('books.show', $book))
        ->assertOk()
        ->assertSee(route('books.order', $book), false)
        ->assertSee('سفارش کتاب')
        ->assertDontSee('recipient-name')
        ->assertDontSee('shipping-address');

    $this->get(route('books.order', $book))
        ->assertOk()
        ->assertSee('ثبت سفارش')
        ->assertSee('recipient-name', false)
        ->assertSee('shipping-address', false)
        ->assertSee('ثبت سفارش')
        ->assertSee('موجودی پس از ثبت سفارش برای شما نگه داشته می‌شود.')
        ->assertDontSee('پرداخت');

    $this->post(route('orders.checkout'), [
        'book_id' => $book->id,
        'quantity' => 2,
        'recipient_name' => 'خریدار',
        'phone' => '09120000001',
        'shipping_address' => 'تهران، خیابان نمونه',
    ])
        ->assertRedirect(route('orders.mine'));

    $order = Order::query()->firstOrFail();
    expect((float) $order->total_price)->toBe(250000.0)
        ->and($order->status)->toBe('pending')
        ->and($order->items->first()->quantity)->toBe(2)
        ->and((float) $order->items->first()->unit_price)->toBe(125000.0);

    $this->assertDatabaseHas('books', ['id' => $book->id, 'stock' => 1]);

});

test('a user sees only their own orders and payment details', function () {
    $user = User::factory()->create(['phone' => '09120000004']);
    $otherUser = User::factory()->create(['phone' => '09120000005']);
    $author = Author::create(['name' => 'نویسنده‌ی سفارش‌ها']);
    $ownBook = Book::create([
        'title' => 'کتاب سفارش من',
        'author_id' => $author->id,
        'price' => 40000,
        'stock' => 2,
        'is_active' => true,
    ]);
    $otherBook = Book::create([
        'title' => 'کتاب سفارش دیگران',
        'author_id' => $author->id,
        'price' => 70000,
        'stock' => 1,
        'is_active' => true,
    ]);

    $ownOrder = $user->orders()->create([
        'total_price' => 80000,
        'status' => 'processing',
        'recipient_name' => 'گیرنده‌ی من',
        'phone' => $user->phone,
        'shipping_address' => 'نشانی من',
    ]);
    $ownOrder->items()->create(['book_id' => $ownBook->id, 'quantity' => 2, 'unit_price' => 40000]);

    $otherOrder = $otherUser->orders()->create([
        'total_price' => 70000,
        'status' => 'pending',
        'recipient_name' => 'گیرنده‌ی دیگر',
        'phone' => $otherUser->phone,
        'shipping_address' => 'نشانی دیگر',
    ]);
    $otherOrder->items()->create(['book_id' => $otherBook->id, 'quantity' => 1, 'unit_price' => 70000]);

    $this->actingAs($user)
        ->get(route('orders.mine'))
        ->assertOk()
        ->assertSee('سفارش‌های من')
        ->assertSee('کتاب سفارش من')
        ->assertSee('در حال آماده‌سازی')
        ->assertDontSee('پرداخت')
        ->assertSee('بازگشت به پروفایل')
        ->assertSee(route('profile'), false)
        ->assertDontSee('کتاب سفارش دیگران')
        ->assertDontSee('نشانی دیگر');
});

test('a user can delete their pending order and restore book stock', function () {
    $user = User::factory()->create(['phone' => '09120000006']);
    $author = Author::create(['name' => 'نویسنده‌ی حذف سفارش']);
    $book = Book::create([
        'title' => 'کتاب سفارش قابل حذف',
        'author_id' => $author->id,
        'price' => 50000,
        'stock' => 0,
        'is_active' => true,
    ]);
    $order = $user->orders()->create([
        'total_price' => 100000,
        'status' => 'pending',
        'recipient_name' => 'خریدار',
        'phone' => $user->phone,
        'shipping_address' => 'نشانی نمونه',
    ]);
    $order->items()->create(['book_id' => $book->id, 'quantity' => 2, 'unit_price' => 50000]);

    Livewire::actingAs($user)
        ->test(MyOrders::class)
        ->call('delete', $order->id)
        ->assertHasNoErrors()
        ->assertSee('سفارش حذف شد');

    $this->assertDatabaseMissing('orders', ['id' => $order->id]);
    $this->assertDatabaseHas('books', ['id' => $book->id, 'stock' => 2]);
});

test('a user cannot delete an order that is not pending', function () {
    $user = User::factory()->create(['phone' => '09120000007']);
    $order = $user->orders()->create([
        'total_price' => 100000,
        'status' => 'processing',
        'recipient_name' => 'خریدار',
        'phone' => $user->phone,
        'shipping_address' => 'نشانی نمونه',
    ]);

    Livewire::actingAs($user)
        ->test(MyOrders::class)
        ->call('delete', $order->id)
        ->assertSee('فقط سفارش‌های در انتظار بررسی قابل حذف هستند.');

    $this->assertDatabaseHas('orders', ['id' => $order->id]);
});

test('a guest is redirected before opening their orders page', function () {
    $this->get(route('orders.mine'))->assertRedirect(route('login'));
});
