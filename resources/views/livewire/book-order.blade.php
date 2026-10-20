<main class="mx-auto max-w-5xl px-4 py-10 sm:px-6 lg:px-8">
    <div class="mb-6 flex items-center justify-between gap-3">
        <a href="{{ route('books.show', $book) }}" aria-label="بازگشت به جزئیات کتاب" title="بازگشت به جزئیات کتاب" class="inline-flex min-h-10 items-center gap-2 rounded border border-[#dce5dd] px-3 py-2 text-sm font-semibold text-[#1d5547] transition hover:border-[#1d5547]">
            <x-icon name="back" />
            <span class="sr-only sm:not-sr-only sm:inline">بازگشت به جزئیات کتاب</span>
        </a>
        <span class="rounded-full bg-[#edf5dd] px-3 py-1 text-xs font-bold text-[#1d5547]">تکمیل سفارش</span>
    </div>

    <div class="grid gap-8 lg:grid-cols-[minmax(0,1fr)_20rem]">
        <section class="rounded-lg border border-[#dfe7e1] bg-white p-5 sm:p-7" aria-labelledby="order-form-title">
            <p class="text-xs font-bold text-[#d25e43]">اطلاعات تحویل</p>
            <h1 id="order-form-title" class="mt-2 text-2xl font-bold text-[#18342d]">ثبت سفارش</h1>
            <p class="mt-2 text-sm leading-7 text-[#718078]">نشانی و اطلاعات گیرنده را وارد کن تا سفارش برای بررسی ثبت شود.</p>

            <form method="POST" action="{{ route('orders.checkout') }}" class="mt-7 grid gap-5 sm:grid-cols-2">
                @csrf
                <input type="hidden" name="book_id" value="{{ $book->id }}">

                <label for="purchase-quantity" class="text-sm font-semibold text-[#35574a]">تعداد
                    <input id="purchase-quantity" name="quantity" type="number" min="1" max="{{ $book->stock }}" value="{{ old('quantity', 1) }}" required class="mt-2 h-12 w-full rounded border border-[#dce5dd] px-3 text-[#18342d] outline-none focus:border-[#39836e]" dir="ltr">
                    @error('quantity')<span class="mt-1 block text-xs text-[#bd493e]">{{ $message }}</span>@enderror
                    @error('book_id')<span class="mt-1 block text-xs text-[#bd493e]">{{ $message }}</span>@enderror
                </label>

                <label for="recipient-name" class="text-sm font-semibold text-[#35574a]">نام گیرنده
                    <input id="recipient-name" name="recipient_name" type="text" value="{{ old('recipient_name', auth()->user()->name) }}" required maxlength="255" class="mt-2 h-12 w-full rounded border border-[#dce5dd] px-3 text-[#18342d] outline-none focus:border-[#39836e]">
                    @error('recipient_name')<span class="mt-1 block text-xs text-[#bd493e]">{{ $message }}</span>@enderror
                </label>

                <label for="recipient-phone" class="text-sm font-semibold text-[#35574a]">شماره تماس
                    <input id="recipient-phone" name="phone" type="tel" value="{{ old('phone', auth()->user()->phone) }}" required maxlength="11" dir="ltr" class="mt-2 h-12 w-full rounded border border-[#dce5dd] px-3 text-[#18342d] outline-none focus:border-[#39836e]">
                    @error('phone')<span class="mt-1 block text-xs text-[#bd493e]">{{ $message }}</span>@enderror
                </label>

                <label for="shipping-address" class="text-sm font-semibold text-[#35574a] sm:col-span-2">نشانی تحویل
                    <textarea id="shipping-address" name="shipping_address" rows="4" required maxlength="2000" class="mt-2 w-full rounded border border-[#dce5dd] p-3 leading-7 text-[#18342d] outline-none focus:border-[#39836e]">{{ old('shipping_address') }}</textarea>
                    @error('shipping_address')<span class="mt-1 block text-xs text-[#bd493e]">{{ $message }}</span>@enderror
                </label>

                <button type="submit" class="inline-flex min-h-12 items-center justify-center gap-2 rounded bg-[#1d5547] px-5 py-3 text-sm font-bold text-white transition hover:bg-[#133f36] focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#e3694d] sm:col-span-2">ثبت سفارش</button>
            </form>
        </section>

        <aside class="h-fit rounded-lg border border-[#dfe7e1] bg-[#f8faf6] p-5" aria-label="خلاصه سفارش">
            <p class="text-xs font-bold text-[#d25e43]">خلاصه سفارش</p>
            <div class="mt-4 flex gap-4">
                @if ($book->cover_image)
                    <img src="{{ asset('storage/'.$book->cover_image) }}" alt="جلد {{ $book->title }}" class="h-28 w-20 shrink-0 rounded border border-[#dfe7e1] object-cover">
                @else
                    <div class="grid h-28 w-20 shrink-0 place-items-center rounded border border-[#dfe7e1] bg-[#e6eddd] p-2 text-center text-xs font-bold text-[#35574a]">{{ $book->title }}</div>
                @endif
                <div class="min-w-0">
                    <h2 class="font-bold leading-7 text-[#18342d]">{{ $book->title }}</h2>
                    <p class="mt-1 text-sm text-[#718078]">{{ $book->author?->name ?? 'نویسنده نامشخص' }}</p>
                </div>
            </div>
            <dl class="mt-5 space-y-3 border-t border-[#dfe7e1] pt-4 text-sm">
                <div class="flex justify-between gap-3"><dt class="text-[#718078]">قیمت هر نسخه</dt><dd class="font-semibold text-[#35574a]">{{ number_format((float) $book->price) }} تومان</dd></div>
                <div class="flex justify-between gap-3"><dt class="text-[#718078]">موجودی</dt><dd class="font-semibold text-[#35574a]">{{ number_format($book->stock) }} نسخه</dd></div>
            </dl>
            <p class="mt-4 text-xs leading-6 text-[#718078]">مبلغ نهایی بر اساس تعداد انتخابی محاسبه می‌شود. موجودی پس از ثبت سفارش برای شما نگه داشته می‌شود.</p>
        </aside>
    </div>
</main>
