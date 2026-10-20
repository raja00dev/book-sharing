<main class="mx-auto max-w-5xl px-4 py-10 sm:px-6 lg:px-8">
    <header class="mb-7 flex flex-wrap items-end justify-between gap-4">
        <div>
            <p class="text-xs font-bold text-[#d25e43]">حساب کاربری</p>
            <h1 class="mt-2 text-2xl font-bold text-[#18342d] sm:text-3xl">سفارش‌های من</h1>
            <p class="mt-2 text-sm text-[#718078]">وضعیت سفارش‌ها و اقلام ثبت ‌شده</p>
        </div>
        <a href="{{ route('profile') }}" aria-label="بازگشت به پروفایل" title="بازگشت به پروفایل" class="inline-flex min-h-10 items-center gap-2 rounded border border-[#dce5dd] px-3 py-2 text-sm font-semibold text-[#1d5547] transition hover:border-[#1d5547]">
            <x-icon name="back" />
            <span class="sr-only sm:not-sr-only sm:inline">بازگشت به پروفایل</span>
        </a>
    </header>

    @if (session('orderStatus'))
        <p class="mb-5 rounded border border-[#b8d3c0] bg-[#edf7ef] px-4 py-3 text-sm font-semibold text-[#245a48]" role="status">{{ session('orderStatus') }}</p>
    @endif

    @if ($orders->isNotEmpty())
        <div class="space-y-4">
            @foreach ($orders as $order)
                <article wire:key="order-{{ $order->id }}" class="overflow-hidden rounded-lg border border-[#dfe7e1] bg-white">
                    <header class="flex flex-wrap items-center justify-between gap-3 border-b border-[#edf1eb] bg-[#f8faf6] px-4 py-4 sm:px-5">
                        <div>
                            <h2 class="font-bold text-[#18342d]">سفارش شماره {{ $order->id }}</h2>
                            <p class="mt-1 text-xs text-[#718078]">{{ \App\Support\PersianDate::format($order->created_at, 'yyyy/MM/dd HH:mm') }}</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="rounded px-2.5 py-1 text-xs font-semibold {{ $order->status === 'delivered' ? 'bg-[#edf7ef] text-[#245a48]' : 'bg-[#edf2ea] text-[#52665d]' }}">{{ __('admin.orders.'.$order->status) }}</span>
                            @if ($order->status === 'pending')
                                <button type="button" wire:click="delete({{ $order->id }})" wire:confirm="از حذف این سفارش مطمئنی؟ موجودی کتاب‌ها به قفسه بازمی‌گردد." wire:loading.attr="disabled" wire:target="delete({{ $order->id }})" class="inline-flex min-h-9 items-center justify-center rounded border border-[#ead9d4] px-3 text-xs font-bold text-[#a34031] transition hover:bg-[#fff0ed] disabled:opacity-50">حذف سفارش</button>
                            @endif
                        </div>
                    </header>

                    <div class="px-4 py-4 sm:px-5">
                        <ul class="divide-y divide-[#edf1eb]">
                            @foreach ($order->items as $item)
                                <li wire:key="order-{{ $order->id }}-item-{{ $item->id }}" class="flex flex-wrap items-center justify-between gap-2 py-3 first:pt-0 last:pb-0">
                                    <div>
                                        <p class="text-sm font-semibold text-[#35574a]">{{ $item->book?->title ?? 'کتاب حذف‌شده' }}</p>
                                        <p class="mt-1 text-xs text-[#718078]">{{ number_format($item->quantity) }} نسخه × {{ number_format((float) ($item->unit_price ?? $item->book?->price ?? 0)) }} تومان</p>
                                    </div>
                                    <p class="text-sm font-bold text-[#1d5547]">{{ number_format((float) ($item->unit_price ?? $item->book?->price ?? 0) * $item->quantity) }} تومان</p>
                                </li>
                            @endforeach
                        </ul>

                        <dl class="mt-4 grid gap-3 border-t border-[#edf1eb] pt-4 text-sm sm:grid-cols-2">
                            <div><dt class="text-xs text-[#718078]">گیرنده</dt><dd class="mt-1 font-semibold text-[#35574a]">{{ $order->recipient_name }}</dd></div>
                            <div><dt class="text-xs text-[#718078]">شماره تماس</dt><dd class="mt-1 font-semibold text-[#35574a]" dir="ltr">{{ $order->phone }}</dd></div>
                            <div class="sm:col-span-2"><dt class="text-xs text-[#718078]">نشانی تحویل</dt><dd class="mt-1 leading-7 text-[#35574a]">{{ $order->shipping_address }}</dd></div>
                        </dl>

                        <div class="mt-4 flex items-center justify-between gap-3 border-t border-[#edf1eb] pt-4">
                            <span class="text-sm text-[#718078]">مبلغ کل</span>
                            <strong class="text-lg text-[#18342d]">{{ number_format((float) $order->total_price) }} تومان</strong>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>

        @if ($orders->hasPages())
            <div class="mt-5">{{ $orders->links() }}</div>
        @endif
    @else
        <section class="rounded-lg border border-dashed border-[#d5dfd6] bg-white px-5 py-14 text-center">
            <h2 class="text-lg font-bold text-[#18342d]">هنوز سفارشی ثبت نکرده‌ای.</h2>
            <p class="mt-2 text-sm text-[#718078]">از میان کتاب‌های موجود، انتخابت را شروع کن.</p>
            <a href="{{ route('home') }}#books" class="mt-5 inline-flex min-h-11 items-center justify-center rounded bg-[#1d5547] px-5 py-3 text-sm font-bold text-white transition hover:bg-[#133f36]">دیدن کتاب‌ها</a>
        </section>
    @endif
</main>
