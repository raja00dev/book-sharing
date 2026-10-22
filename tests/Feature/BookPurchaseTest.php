<?php

use App\Livewire\Admin\Orders\Edit as EditOrder;
use App\Livewire\MyOrders;
use App\Models\Author;
use App\Models\Book;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Schema;
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
        ->assertDontSee('purchase-quantity')
        ->assertDontSee('پرداخت');

    $this->post(route('orders.checkout'), [
        'book_id' => $book->id,
        'recipient_name' => 'خریدار',
        'phone' => '09120000001',
        'shipping_address' => 'تهران، خیابان نمونه',
    ])
        ->assertRedirect(route('orders.mine'));

    $order = Order::query()->firstOrFail();
    expect((float) $order->total_price)->toBe(125000.0)
        ->and($order->status)->toBe('pending')
        ->and(Schema::hasColumn('orders', 'payment_token'))->toBeTrue()
        ->and(Schema::hasColumn('orders', 'is_paid'))->toBeFalse()
        ->and(Schema::hasColumn('orders', 'shipping_status'))->toBeFalse()
        ->and((int) $order->items->first()->book->price)->toBe(125000)
        ->and(array_key_exists('quantity', $order->items->first()->getAttributes()))->toBeFalse()
        ->and(Schema::hasColumn('order_items', 'quantity'))->toBeFalse()
        ->and(Schema::hasColumn('order_items', 'price'))->toBeFalse();

    $this->assertDatabaseHas('books', ['id' => $book->id, 'stock' => 2]);

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
        'total_price' => 40000,
        'status' => 'pending',
        'recipient_name' => 'گیرنده‌ی من',
        'phone' => $user->phone,
        'shipping_address' => 'نشانی من',
    ]);
    $ownOrder->items()->create(['book_id' => $ownBook->id]);

    $otherOrder = $otherUser->orders()->create([
        'total_price' => 70000,
        'status' => 'pending',
        'recipient_name' => 'گیرنده‌ی دیگر',
        'phone' => $otherUser->phone,
        'shipping_address' => 'نشانی دیگر',
    ]);
    $otherOrder->items()->create(['book_id' => $otherBook->id]);

    $this->actingAs($user)
        ->get(route('orders.mine'))
        ->assertOk()
        ->assertSee('سفارش‌های من')
        ->assertSee('کتاب سفارش من')
        ->assertSee('در انتظار پرداخت')
        ->assertSee('پرداخت')
        ->assertSee(route('orders.pay', $ownOrder), false)
        ->assertSee('name="_token"', false)
        ->assertSee('بازگشت به پروفایل')
        ->assertSee(route('profile'), false)
        ->assertDontSee('کتاب سفارش دیگران')
        ->assertDontSee('نشانی دیگر');
});

test('an authenticated user can pay for their pending order in test mode', function () {
    $user = User::factory()->create(['phone' => '09120000008']);
    $order = $user->orders()->create([
        'total_price' => 125000,
        'status' => 'pending',
        'recipient_name' => 'خریدار',
        'phone' => $user->phone,
        'shipping_address' => 'نشانی نمونه',
    ]);

    $this->actingAs($user)
        ->post(route('orders.pay', $order))
        ->assertOk()
        ->assertSee('درگاه پرداخت تست');

    $order->refresh();
    expect($order->payment_token)->not->toBeNull()
        ->and($order->status)->toBe('pending');

    $this->post(route('payment.verify'), ['transactionId' => $order->payment_token])
        ->assertRedirect(route('orders.mine'))
        ->assertSessionHas('orderStatus', 'پرداخت با موفقیت انجام شد.');

    $this->assertDatabaseHas('orders', [
        'id' => $order->id,
        'status' => 'paid',
    ]);
});

test('a user cannot pay for an order that is already paid', function () {
    $user = User::factory()->create(['phone' => '09120000009']);
    $order = $user->orders()->create([
        'total_price' => 125000,
        'status' => 'paid',
        'recipient_name' => 'خریدار',
        'phone' => $user->phone,
        'shipping_address' => 'نشانی نمونه',
    ]);

    $this->actingAs($user)
        ->post(route('orders.pay', $order))
        ->assertRedirect(route('orders.mine'))
        ->assertSessionHas('orderErrorStatus', 'این سفارش در وضعیت فعلی قابل پرداخت نیست.');
});

test('a cancelled payment leaves the order pending', function () {
    $user = User::factory()->create(['phone' => '09120000012']);
    $order = $user->orders()->create([
        'total_price' => 125000,
        'payment_token' => 'test-payment-token',
        'status' => 'pending',
        'recipient_name' => 'خریدار',
        'phone' => $user->phone,
        'shipping_address' => 'نشانی نمونه',
    ]);

    $this->actingAs($user)
        ->post(route('payment.verify'), [
            'transactionId' => $order->payment_token,
            'cancel' => 'true',
        ])
        ->assertRedirect(route('orders.mine'))
        ->assertSessionHas('orderErrorStatus', 'پرداخت توسط شما لغو شد.');

    $this->assertDatabaseHas('orders', [
        'id' => $order->id,
        'status' => 'pending',
    ]);
});

test('an admin can update order status', function () {
    $admin = User::factory()->create([
        'phone' => '09120000013',
        'is_admin' => true,
    ]);
    $order = $admin->orders()->create([
        'total_price' => 125000,
        'status' => 'pending',
        'recipient_name' => 'خریدار',
        'phone' => $admin->phone,
        'shipping_address' => 'نشانی نمونه',
    ]);

    Livewire::actingAs($admin)
        ->test(EditOrder::class, ['id' => $order->id])
        ->set('statusValue', 'shipped')
        ->call('save')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('orders', [
        'id' => $order->id,
        'status' => 'shipped',
    ]);
});

test('a user cannot start payment for another users order', function () {
    $user = User::factory()->create(['phone' => '09120000010']);
    $otherUser = User::factory()->create(['phone' => '09120000011']);
    $order = $otherUser->orders()->create([
        'total_price' => 125000,
        'status' => 'pending',
        'recipient_name' => 'خریدار',
        'phone' => $user->phone,
        'shipping_address' => 'نشانی نمونه',
    ]);

    $this->actingAs($user)
        ->post(route('orders.pay', $order))
        ->assertNotFound();
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
        'total_price' => 50000,
        'status' => 'pending',
        'recipient_name' => 'خریدار',
        'phone' => $user->phone,
        'shipping_address' => 'نشانی نمونه',
    ]);
    $order->items()->create(['book_id' => $book->id]);

    Livewire::actingAs($user)
        ->test(MyOrders::class)
        ->call('delete', $order->id)
        ->assertHasNoErrors()
        ->assertSee('سفارش حذف شد');

    $this->assertDatabaseMissing('orders', ['id' => $order->id]);
    $this->assertDatabaseHas('books', ['id' => $book->id, 'stock' => 1]);
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
