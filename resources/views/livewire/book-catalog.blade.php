<main class="min-h-screen bg-[#f3f6f1]">
    <header class="border-b border-[#e3eae2] bg-white">
        <nav class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-4 sm:px-6 lg:px-8" aria-label="ناوبری اصلی">
            <a href="{{ route('home') }}" class="flex items-center gap-3 text-[#18342d] no-underline">
                <span class="grid size-10 place-items-center rounded-[7px_7px_7px_2px] bg-[#e3694d] text-xl font-extrabold text-white" aria-hidden="true">ک</span>
                <span><span class="block text-lg font-extrabold">{{ config('app.name') }}</span><span class="block text-[11px] text-[#7a8b83]">جست‌وجوی کتاب‌ها</span></span>
            </a>
            <div class="flex items-center gap-2 sm:gap-4">
                <a href="{{ route('profile') }}" aria-label="پروفایل" title="پروفایل" class="inline-flex size-10 items-center justify-center gap-2 rounded border border-[#dce5dd] text-xs font-semibold text-[#52665d] transition hover:border-[#1d5547] hover:text-[#1d5547] sm:w-auto sm:px-3 sm:text-sm">
                    <x-icon name="user" />
                    <span class="sr-only sm:not-sr-only sm:inline">پروفایل</span>
                </a>
                <a href="{{ route('home') }}" aria-label="خانه" title="خانه" class="inline-flex size-10 items-center justify-center gap-2 rounded border border-[#dce5dd] text-xs font-semibold text-[#1d5547] transition hover:border-[#1d5547] sm:w-auto sm:px-3 sm:text-sm">
                    <x-icon name="home" />
                    <span class="sr-only sm:not-sr-only sm:inline">خانه</span>
                </a>
            </div>
        </nav>
    </header>

    <section class="mx-auto max-w-7xl px-4 py-8 sm:px-6 sm:py-10 lg:px-8">
        <header class="mb-6">
            <p class="text-xs font-bold text-[#d25e43]">قفسه‌ی {{ config('app.name') }}</p>
            <h1 class="mt-2 text-2xl font-bold text-[#18342d] sm:text-3xl">جست‌وجوی کتاب‌ها</h1>
        </header>

        <section class="mb-8 border-y border-[#dfe7e1] bg-white px-4 py-5 sm:px-5" aria-label="فیلتر کتاب‌ها">
            <div class="grid gap-4 md:grid-cols-[minmax(0,1fr)_14rem]">
                <label for="book-search" class="text-sm font-semibold text-[#35574a]">عنوان کتاب یا نام نویسنده
                    <input id="book-search" type="search" wire:model.live.debounce.300ms="search" placeholder="مثلاً نام کتاب یا نویسنده" class="mt-2 h-12 w-full rounded border border-[#dce5dd] bg-[#fbfcfa] px-4 text-sm font-normal text-[#18342d] outline-none transition focus:border-[#39836e]">
                </label>
                <label for="book-category" class="text-sm font-semibold text-[#35574a]">دسته‌بندی
                    <select id="book-category" wire:model.live="categoryId" class="mt-2 h-12 w-full rounded border border-[#dce5dd] bg-[#fbfcfa] px-3 text-sm font-normal text-[#18342d] outline-none focus:border-[#39836e]">
                        <option value="">همه‌ی دسته‌ها</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>

                </label>
            </div>
        </section>

        <div class="mb-5 flex items-center justify-between gap-3">
            <p class="text-sm text-[#718078]">{{ number_format($books->total()) }} کتاب پیدا شد</p>
            <div wire:loading class="text-xs font-semibold text-[#1d5547]">در حال جست‌وجو...</div>
        </div>

        @if ($books->isNotEmpty())
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                @foreach ($books as $book)
                    <article wire:key="catalog-book-{{ $book->id }}" class="overflow-hidden rounded-md border border-[#e1e8e0] bg-white">
                        <a href="{{ route('books.show', $book) }}" class="block h-full transition hover:shadow-md">
                            <div class="flex h-56 items-center justify-center overflow-hidden bg-[#e7eddf]">
                                @if ($book->cover_image)
                                    <img src="{{ asset('storage/'.$book->cover_image) }}" alt="جلد کتاب {{ $book->title }}" class="h-full w-full object-cover">
                                @else
                                    <div class="flex h-full w-full items-end bg-[#dce8cf] p-5">
                                        <span class="max-w-48 border-r-2 border-[#d87355] pr-3 text-lg font-bold leading-8 text-[#254b3c]">{{ $book->title }}</span>
                                    </div>
                                @endif
                            </div>
                            <div class="p-4">
                                <h2 class="truncate font-bold text-[#18342d]">{{ $book->title }}</h2>
                                <p class="mt-2 text-sm text-[#718078]">{{ $book->author?->name ?? 'نویسنده نامشخص' }}</p>
                                <div class="mt-3 flex flex-wrap gap-1.5">
                                    @foreach ($book->categories as $category)
                                        <span class="rounded bg-[#f3f6f1] px-2 py-1 text-[11px] text-[#61736a]">{{ $category->name }}</span>
                                    @endforeach
                                </div>
                                <div class="mt-4 flex items-center justify-between gap-2 border-t border-[#edf1eb] pt-3">
                                    <span class="text-sm font-bold text-[#1d5547]">{{ number_format((float) $book->price) }} تومان</span>
                                    <span class="text-xs {{ $book->stock > 0 ? 'text-[#61736a]' : 'font-semibold text-[#a34031]' }}">{{ $book->stock > 0 ? 'موجود' : 'ناموجود' }}</span>
                                </div>
                            </div>
                        </a>
                    </article>
                @endforeach
            </div>

            @if ($books->hasPages())
                <div class="mt-7">{{ $books->links('vendor.pagination.custom') }}</div>
            @endif
        @else
            <section class="border-y border-dashed border-[#d5dfd6] bg-white px-5 py-14 text-center">
                <h2 class="text-lg font-bold text-[#18342d]">کتابی با این مشخصات پیدا نشد.</h2>
                <p class="mt-2 text-sm text-[#718078]">عبارت جست‌وجو یا دسته‌بندی را تغییر بده.</p>
                @if ($search !== '' || $categoryId !== '')
                    <button type="button" wire:click="$set('search', '')" class="mt-4 text-sm font-bold text-[#1d5547] underline underline-offset-4">پاک‌کردن عبارت جست‌وجو</button>
                @endif
            </section>
        @endif
    </section>
</main>
