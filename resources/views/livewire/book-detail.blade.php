<main class="mx-auto max-w-6xl px-4 py-10 sm:px-6 lg:px-8">
    <div class="mb-6 flex items-center justify-between gap-3">
        <a href="{{ route('home') }}" aria-label="بازگشت به خانه" title="بازگشت به خانه" class="inline-flex min-h-10 items-center gap-2 rounded border border-[#dce5dd] px-3 py-2 text-sm font-semibold text-[#1d5547] transition hover:border-[#1d5547]">
            <x-icon name="back" />
            <span class="sr-only sm:not-sr-only sm:inline">بازگشت به خانه</span>
        </a>
        <span class="rounded-full bg-[#edf5dd] px-3 py-1 text-xs font-bold text-[#1d5547]">جزییات کتاب</span>
    </div>

    <article class="overflow-hidden rounded-2xl border border-[#dfe7e1] bg-white shadow-sm">
        <div class="grid gap-6 p-5 sm:p-8 lg:grid-cols-[280px_1fr] lg:p-10">
            <div class="flex h-[360px] items-center justify-center overflow-hidden rounded-xl bg-[#edf2ea]">
                @if ($book->cover_image)
                    <img src="{{ asset('storage/'.$book->cover_image) }}" alt="جلد {{ $book->title }}" class="h-full w-full object-cover">
                @else
                    <div class="flex h-full w-full items-end bg-[#dce8cf] p-6">
                        <span class="border-r-2 border-[#d87355] pr-3 text-2xl font-bold leading-10 text-[#254b3c]">{{ $book->title }}</span>
                    </div>
                @endif
            </div>

            <div class="space-y-5">
                <div>
                    <p class="text-xs font-bold text-[#d25e43]">{{ $book->author?->name ?? 'نویسنده نامشخص' }}</p>
                    <h1 class="mt-2 text-3xl font-bold text-[#18342d]">{{ $book->title }}</h1>
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    <span class="rounded-full bg-[#edf5dd] px-3 py-1 text-sm font-bold text-[#1d5547]">قیمت: {{ number_format((float) $book->price) }} تومان</span>
                    <span class="rounded-full bg-[#f3f6f1] px-3 py-1 text-sm text-[#52665d]">موجودی: {{ number_format($book->stock) }}</span>
                    @if ($book->publication_year)
                        <span class="rounded-full bg-[#f3f6f1] px-3 py-1 text-sm text-[#52665d]">سال انتشار: {{ $book->publication_year }}</span>
                    @endif
                </div>

                <div class="rounded-lg bg-[#f8faf6] p-4">
                    <h2 class="mb-2 text-sm font-bold text-[#18342d]">توضیحات</h2>
                    <p class="text-base leading-8 text-[#52665d]">
                        {{ $book->description ?: 'برای این کتاب توضیحی ثبت نشده است، اما می‌تواند برایتان جذاب و خواندنی باشد.' }}
                    </p>
                </div>

                @if ($book->stock > 0)
                    <a href="{{ route('books.order', $book) }}" class="inline-flex min-h-12 w-full items-center justify-center gap-2 rounded bg-[#1d5547] px-6 py-3 text-sm font-bold text-white transition hover:bg-[#133f36] focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#e3694d]">سفارش کتاب <span aria-hidden="true">←</span></a>
                @else
                    <p class="text-sm font-semibold text-[#a34031]">این کتاب فعلاً موجود نیست.</p>
                @endif
            </div>
        </div>
    </article>

    <section class="mt-10 rounded-2xl border border-[#dfe7e1] bg-white p-5 shadow-sm sm:p-6">
        <div class="mb-5 flex items-center justify-between gap-3">
            <h2 class="text-xl font-bold text-[#18342d]">نظرات خوانندگان</h2>
            <span class="text-sm text-[#718078]">{{ $comments->total() }} نظر</span>
        </div>

        <form wire:submit.prevent="addComment" class="mb-6 space-y-3">
            <textarea wire:model.defer="newComment" rows="4" class="w-full rounded-lg border border-[#dfe7e1] bg-[#f8faf6] px-4 py-3 text-base text-[#18342d] outline-none transition focus:border-[#1d5547]" placeholder="نظر خود را درباره‌ی این کتاب بنویسید..."></textarea>
            @error('newComment')
                <p class="text-sm text-red-600">{{ $message }}</p>
            @enderror
            <div class="flex justify-end">
                <button type="submit" class="inline-flex min-h-12 items-center justify-center rounded bg-[#1d5547] px-5 py-3 text-sm font-bold text-white transition hover:bg-[#133f36]">
                    ثبت نظر
                </button>
            </div>
        </form>

        @if ($comments->isNotEmpty())
            <div class="space-y-4">
                @foreach ($comments as $comment)
                    <article wire:key="comment-{{ $comment->id }}" class="rounded-xl border border-[#edf2ea] bg-[#f8faf6] p-4">
                        <div class="mb-3 flex items-center gap-3">
                            <div class="grid size-10 place-items-center overflow-hidden rounded-full bg-[#dce8cf] text-xs font-bold text-[#18342d]">
                                @if ($comment->user && $comment->user->avatar)
                                    <img src="{{ asset('storage/'.$comment->user->avatar) }}" alt="{{ $comment->user->name }}" class="size-full object-cover">
                                @else
                                    {{ strtoupper(substr($comment->user?->name ?? 'ک', 0, 1)) }}
                                @endif
                            </div>
                            <div>
                                <h3 class="text-sm font-bold text-[#18342d]">{{ $comment->user?->name ?? 'کاربر' }}</h3>
                                <time class="text-xs text-[#718078]">{{ \App\Support\PersianDate::format($comment->created_at, 'yyyy/MM/dd HH:mm') }}</time>
                            </div>
                        </div>
                        <p class="text-base leading-8 text-[#52665d]">{{ $comment->body }}</p>
                    </article>
                @endforeach
            </div>

            <div class="mt-6 flex justify-center">
                {{ $comments->links('vendor.pagination.custom') }}
            </div>
        @else
            <div class="rounded-xl border border-dashed border-[#dfe7e1] bg-[#f8faf6] px-4 py-8 text-center text-[#718078]">
                هنوز هیچ نظری برای این کتاب ثبت نشده است.
            </div>
        @endif
    </section>
</main>
