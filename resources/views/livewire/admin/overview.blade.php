<x-admin.layout section-title="نمای کلی" active-route="admin.dashboard" :sidebar-open="$sidebarOpen">
    <section class="mb-8 rounded border border-[#e3eae2] bg-white px-5 py-6 sm:px-7">
        <p class="text-xs font-bold text-[#d25e43]">خلاصه‌ی {{ config('app.name') }}</p>
        <h2 class="mt-2 text-2xl font-bold text-[#18342d] sm:text-3xl">سلام، {{ auth()->user()->name ?: 'مدیر' }}</h2>
        <p class="mt-2 text-sm text-[#718078]">وضعیت کلی محتوای سایت را از اینجا پیگیری کن.</p>
    </section>

    <section class="rounded border border-[#e3eae2] bg-white p-4 sm:p-6">
        <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
            <article class="rounded border border-[#e1e8e0] bg-[#fbfcfa] p-5"><p class="text-sm text-[#718078]">کتاب‌ها</p><p class="mt-4 text-3xl font-bold tabular-nums text-[#1d5547]">{{ number_format($bookCount) }}</p></article>
            <article class="rounded border border-[#e1e8e0] bg-[#fbfcfa] p-5"><p class="text-sm text-[#718078]">نویسندگان</p><p class="mt-4 text-3xl font-bold tabular-nums text-[#1d5547]">{{ number_format($authorCount) }}</p></article>
            <article class="rounded border border-[#e1e8e0] bg-[#fbfcfa] p-5"><p class="text-sm text-[#718078]">دسته‌بندی‌ها</p><p class="mt-4 text-3xl font-bold tabular-nums text-[#1d5547]">{{ number_format($categoryCount) }}</p></article>
            <article class="rounded border border-[#e1e8e0] bg-[#fbfcfa] p-5"><p class="text-sm text-[#718078]">بنرهای فعال</p><p class="mt-4 text-3xl font-bold tabular-nums text-[#1d5547]">{{ number_format($bannerCount) }}</p></article>
        </div>
        <div class="mt-4 grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
            <article class="rounded border border-[#e1e8e0] bg-[#fbfcfa] p-5"><p class="text-sm text-[#718078]">کاربران</p><p class="mt-4 text-3xl font-bold tabular-nums text-[#1d5547]">{{ number_format($userCount) }}</p></article>
            <article class="rounded border border-[#e1e8e0] bg-[#fbfcfa] p-5"><p class="text-sm text-[#718078]">ادمین‌ها</p><p class="mt-4 text-3xl font-bold tabular-nums text-[#1d5547]">{{ number_format($adminCount) }}</p></article>
            <article class="rounded border border-[#e1e8e0] bg-[#fbfcfa] p-5"><p class="text-sm text-[#718078]">سفارش‌ها</p><p class="mt-4 text-3xl font-bold tabular-nums text-[#1d5547]">{{ number_format($orderCount) }}</p></article>
            <article class="rounded border border-[#e1e8e0] bg-[#fbfcfa] p-5"><p class="text-sm text-[#718078]">نظرات</p><p class="mt-4 text-3xl font-bold tabular-nums text-[#1d5547]">{{ number_format($commentCount) }}</p></article>
        </div>
        <div class="mt-8 border-y border-[#dce5dd] py-7">
            <h2 class="text-lg font-bold text-[#18342d]">لیست‌ها</h2>
            <div class="mt-4 flex flex-wrap gap-3 text-sm font-semibold"><span class="rounded bg-[#e6eddd] px-4 py-2 text-[#35574a]">دسترسی سریع به لیست‌ها</span></div>
        </div>
        <div class="mt-6 grid gap-3 sm:grid-cols-2 xl:grid-cols-3">
            @foreach ($resourceLinks as $resource)
                <a href="{{ route($resource['route']) }}" class="rounded border border-[#e1e8e0] bg-white p-5 transition hover:border-[#aabda8] hover:bg-[#f9faf7]">
                    <div class="flex items-center justify-between gap-3">
                        <p class="text-sm font-bold text-[#35574a]">{{ $resource['label'] }}</p>
                        <span class="rounded-full bg-[#e6eddd] px-2.5 py-1 text-xs font-bold text-[#35574a]">{{ number_format($resource['count']) }}</span>
                    </div>
                    <p class="mt-2 text-sm leading-6 text-[#718078]">{{ $resource['description'] }}</p>
                </a>
            @endforeach
        </div>
        <div class="mt-8 border-y border-[#dce5dd] py-7">
            <h2 class="text-lg font-bold text-[#18342d]">دسترسی‌های پنل</h2>
            <div class="mt-4 flex flex-wrap gap-3 text-sm font-semibold"><span class="rounded bg-[#e6eddd] px-4 py-2 text-[#35574a]">مدیریت کتاب‌ها</span><span class="rounded bg-[#e6eddd] px-4 py-2 text-[#35574a]">دسته‌بندی‌ها</span><span class="rounded bg-[#e6eddd] px-4 py-2 text-[#35574a]">بنرهای صفحه‌ی اصلی</span></div>
        </div>
        <div class="grid gap-3 sm:grid-cols-2">
            <a href="{{ route('admin.books.create') }}" class="rounded border border-[#e1e8e0] bg-white p-5 transition hover:border-[#aabda8]"><p class="text-sm font-bold text-[#35574a]">افزودن کتاب</p><p class="mt-2 text-sm leading-6 text-[#718078]">ثبت عنوان، نویسنده، دسته‌بندی و مشخصات چاپ.</p></a>
            <a href="{{ route('admin.authors.create') }}" class="rounded border border-[#e1e8e0] bg-white p-5 transition hover:border-[#aabda8]"><p class="text-sm font-bold text-[#35574a]">افزودن نویسنده</p><p class="mt-2 text-sm leading-6 text-[#718078]">ساخت پرونده‌ی نویسنده برای اتصال به کتاب‌ها.</p></a>
            <a href="{{ route('admin.categories.create') }}" class="rounded border border-[#e1e8e0] bg-white p-5 transition hover:border-[#aabda8]"><p class="text-sm font-bold text-[#35574a]">افزودن دسته‌بندی</p><p class="mt-2 text-sm leading-6 text-[#718078]">ساخت دسته‌بندی تازه برای مرتب‌کردن کتاب‌ها.</p></a>
            <a href="{{ route('admin.banners.create') }}" class="rounded border border-[#e1e8e0] bg-white p-5 transition hover:border-[#aabda8]"><p class="text-sm font-bold text-[#35574a]">افزودن بنر</p><p class="mt-2 text-sm leading-6 text-[#718078]">افزودن اسلاید تازه به صفحه‌ی اصلی.</p></a>
            <a href="{{ route('admin.admins.create') }}" class="rounded border border-[#e1e8e0] bg-white p-5 transition hover:border-[#aabda8]"><p class="text-sm font-bold text-[#35574a]">افزودن ادمین</p><p class="mt-2 text-sm leading-6 text-[#718078]">ساخت حساب تازه با دسترسی مدیریت.</p></a>
        </div>
    </section>
</x-admin.layout>
