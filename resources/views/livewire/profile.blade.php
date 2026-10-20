<main class="mx-auto max-w-4xl px-4 py-10 sm:px-6 lg:px-8">
    <header class="mb-7 flex items-end justify-between gap-4">
        <div>
            <p class="text-xs font-bold text-[#d25e43]">حساب کاربری</p>
            <h1 class="mt-2 text-2xl font-bold text-[#18342d] sm:text-3xl">پروفایل شما</h1>
        </div>
        <a href="{{ route('home') }}" aria-label="خانه" title="خانه" class="inline-flex size-10 items-center justify-center rounded border border-[#dce5dd] text-[#1d5547] transition hover:border-[#1d5547]">
            <x-icon name="home" />
        </a>
    </header>

    <section class="overflow-hidden rounded-lg border border-[#dfe7e1] bg-white">
        <div class="flex items-center gap-4 border-b border-[#edf1eb] bg-[#f8faf6] p-5 sm:p-7">
            <div class="grid size-16 shrink-0 place-items-center overflow-hidden rounded-full border border-[#dfe7e1] bg-[#edf2ea] text-lg font-bold text-[#18342d]">
                @if ($user->avatar)
                    <img src="{{ asset('storage/'.$user->avatar) }}" alt="عکس پروفایل {{ $user->name }}" class="size-full object-cover">
                @else
                    {{ strtoupper(substr($user->name ?: 'ک', 0, 1)) }}
                @endif
            </div>
            <div class="min-w-0">
                <h2 class="truncate text-lg font-bold text-[#18342d]">{{ $user->name ?: 'کتاب‌خوان عزیز' }}</h2>
                <p class="mt-1 text-sm text-[#718078]" dir="ltr">{{ $user->phone }}</p>
            </div>
        </div>

        <nav aria-label="تنظیمات حساب" class="divide-y divide-[#edf1eb]">
            <a href="{{ route('profile.edit') }}" class="flex min-h-20 items-center gap-4 px-5 py-4 transition hover:bg-[#fbfcfa] sm:px-7">
                <span class="grid size-11 shrink-0 place-items-center rounded bg-[#edf7ef] text-[#245a48]"><x-icon name="user" /></span>
                <span class="min-w-0 flex-1">
                    <span class="block font-bold text-[#18342d]">ویرایش نام و عکس پروفایل</span>
                    <span class="mt-1 block text-xs text-[#718078]">اطلاعات نمایشی حسابت را تغییر بده</span>
                </span>
                <span class="text-lg text-[#718078]" aria-hidden="true">←</span>
            </a>
            <a href="{{ route('orders.mine') }}" class="flex min-h-20 items-center gap-4 px-5 py-4 transition hover:bg-[#fbfcfa] sm:px-7">
                <span class="grid size-11 shrink-0 place-items-center rounded bg-[#edf5dd] text-[#35574a]"><x-icon name="orders" /></span>
                <span class="min-w-0 flex-1">
                    <span class="block font-bold text-[#18342d]">سفارش‌ها و پرداخت‌های من</span>
                    <span class="mt-1 block text-xs text-[#718078]">پیگیری وضعیت سفارش‌ها و پرداخت‌ها</span>
                </span>
                <span class="text-lg text-[#718078]" aria-hidden="true">←</span>
            </a>
        </nav>
    </section>
</main>
