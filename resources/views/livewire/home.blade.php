<main class="min-h-screen bg-[#f3f6f1]">
    <header class="border-b border-[#e3eae2] bg-white">
        <nav class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-4 sm:px-6 lg:px-8" aria-label="ناوبری اصلی">
            <a href="{{ route('home') }}" class="flex items-center gap-3 text-[#18342d] no-underline">
                <span class="grid size-10 place-items-center rounded-[7px_7px_7px_2px] bg-[#e3694d] text-xl font-extrabold text-white" aria-hidden="true">ک</span>
                <span>
                    <span class="block text-lg font-extrabold">{{ config('app.name') }}</span>
                    <span class="block text-[11px] text-[#7a8b83]">بروز ترین کتابخانه در کل کشور</span>
                </span>
            </a>
            <div class="flex items-center gap-3 sm:gap-6">
                <a href="{{ route('books.catalog') }}" aria-label="جست‌وجوی کتاب" title="جست‌ و جوی کتاب" class="inline-flex size-10 items-center justify-center gap-2 rounded border border-[#e3eae2] text-[#52665d] transition hover:border-[#1d5547] hover:text-[#1d5547] sm:h-10 sm:w-auto sm:px-3">
                    <x-icon name="search" />
                    <span class="sr-only sm:not-sr-only sm:inline">جست‌ و جوی کتاب</span>
                </a>
                <a href="{{ route('profile') }}" class="flex items-center gap-3 rounded-full border border-[#e3eae2] px-2 py-1.5 text-sm text-[#52665d] transition hover:border-[#1d5547] hover:text-[#1d5547]">
                    <span class="grid size-8 place-items-center overflow-hidden rounded-full bg-[#edf2ea] text-[10px] font-bold text-[#18342d]">
                        @if (auth()->user()->avatar)
                            <img src="{{ asset('storage/'.auth()->user()->avatar) }}" alt="{{ auth()->user()->name ?: 'پروفایل' }}" class="size-full object-cover">
                        @else
                            {{ strtoupper(substr(auth()->user()->name ?: 'ک', 0, 1)) }}
                        @endif
                    </span>
                    <span class="hidden sm:inline">{{ auth()->user()->name ?: 'کتاب‌خوان عزیز' }}</span>
                </a>
                <button type="button" wire:click="logout" wire:loading.attr="disabled" aria-label="خروج" title="خروج" class="inline-flex size-10 items-center justify-center gap-2 rounded border border-[#dce5dd] text-xs font-bold text-[#52665d] transition hover:border-[#1d5547] hover:text-[#1d5547] focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#e3694d] disabled:opacity-60 sm:h-10 sm:w-auto sm:px-4 sm:text-sm">
                    <x-icon name="logout" />
                    <span class="sr-only sm:not-sr-only sm:inline">خروج</span>
                </button>
            </div>
        </nav>
    </header>

    @if (session('orderStatus'))
        <div class="mx-auto mt-4 max-w-7xl px-4 sm:px-6 lg:px-8">
            <p class="rounded border border-[#b8d3c0] bg-[#edf7ef] px-4 py-3 text-sm font-semibold text-[#245a48]" role="status">{{ session('orderStatus') }}</p>
        </div>
    @endif

    @if ($banners->isNotEmpty())
        <section class="mx-auto max-w-7xl px-3 pt-3 sm:px-6 sm:pt-6 lg:px-8" aria-label="پیشنهادهای {{ config('app.name') }}">
            <div class="relative isolate h-[390px] overflow-hidden rounded-lg bg-[#18342d] text-white sm:h-[460px] lg:h-[530px]" data-carousel role="region" aria-roledescription="carousel" aria-label="بنرهای {{ config('app.name') }}">
                @foreach ($banners as $index => $banner)
                    <article wire:key="banner-{{ $banner->id }}" data-carousel-slide class="absolute inset-0 transition-opacity duration-700 {{ $index === 0 ? 'opacity-100' : 'pointer-events-none opacity-0' }}" aria-hidden="{{ $index === 0 ? 'false' : 'true' }}" aria-roledescription="اسلاید" aria-label="{{ $index + 1 }} از {{ $banners->count() }}">
                        <img src="{{ str_starts_with($banner->image, 'http') ? $banner->image : (str_starts_with($banner->image, '/') ? $banner->image : asset('storage/'.$banner->image)) }}" alt="" class="absolute inset-0 size-full object-cover">
                        <div class="absolute inset-0 bg-[#10251f]/60"></div>
                        <div class="relative z-10 mx-auto flex h-full max-w-7xl flex-col items-start justify-center px-7 py-12 sm:px-14 lg:px-20">
                            <p class="mb-4 text-xs font-bold text-[#d5ed83] sm:text-sm">یک کتاب تازه انتخاب کن</p>
                            <h1 class="max-w-3xl text-3xl font-bold leading-[1.6] sm:text-5xl lg:text-6xl {{ $banner->isBig ? 'max-w-3xl' : 'max-w-2xl' }}">{{ $banner->text ?: 'کتاب بعدی‌ات منتظر توست' }}</h1>
                            <p class="mt-4 max-w-xl text-sm leading-8 text-white/85 sm:text-base">از میان {{ number_format($bookCount) }} کتاب فعال، قصه‌ای را پیدا کن که با تو همراه شود.</p>
                            <a href="#books" class="mt-7 inline-flex min-h-12 items-center gap-3 rounded bg-[#d5ed83] px-5 py-3 text-sm font-bold text-[#18342d] transition hover:bg-[#e2f3a7] focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white">
                                دیدن کتاب‌ها <span aria-hidden="true">←</span>
                            </a>
                        </div>
                    </article>
                @endforeach

                @if ($banners->count() > 1)
                    <div class="absolute inset-x-0 bottom-0 z-20 flex items-center justify-between px-5 pb-5 sm:px-8 sm:pb-7">
                        <div class="flex items-center gap-2" aria-label="انتخاب اسلاید">
                            @foreach ($banners as $index => $banner)
                                <button type="button" data-carousel-dot="{{ $index }}" aria-label="رفتن به اسلاید {{ $index + 1 }}" aria-current="{{ $index === 0 ? 'true' : 'false' }}" class="h-2 rounded-full bg-white/50 transition-all {{ $index === 0 ? 'w-8 bg-[#d5ed83]' : 'w-2' }}"></button>
                            @endforeach
                        </div>
                        <div class="flex items-center gap-2">
                            <button type="button" data-carousel-prev aria-label="اسلاید قبلی" class="grid size-10 place-items-center rounded-full border border-white/50 bg-[#10251f]/30 text-lg transition hover:bg-white hover:text-[#18342d] focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white">→</button>
                            <button type="button" data-carousel-next aria-label="اسلاید بعدی" class="grid size-10 place-items-center rounded-full border border-white/50 bg-[#10251f]/30 text-lg transition hover:bg-white hover:text-[#18342d] focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white">←</button>
                        </div>
                    </div>
                @endif
            </div>
        </section>
    @endif

    <section class="mx-auto flex max-w-7xl flex-col justify-between gap-5 px-5 py-9 sm:flex-row sm:items-center sm:px-8 lg:px-12">
        <div>
            <p class="text-sm font-semibold text-[#d25e43]">خوش آمدی، {{ auth()->user()->name ?: 'کتاب‌خوان عزیز' }}</p>
            <h2 class="mt-2 text-2xl font-bold leading-relaxed text-[#18342d]">امروز دلت چه قصه‌ای می‌خواهد؟</h2>
        </div>
        <div class="flex items-center gap-4 border-r-2 border-[#d5ed83] pr-4">
            <span class="text-3xl font-bold tabular-nums text-[#1d5547]">{{ number_format($bookCount) }}</span>
            <span class="max-w-28 text-xs leading-6 text-[#718078]">کتاب فعال در {{ config('app.name') }}</span>
        </div>
    </section>

    @if ($categories->isNotEmpty())
        <section id="categories" class="border-y border-[#e2e9e1] bg-white py-10 sm:py-12">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="mb-6 flex items-end justify-between gap-4">
                    <div>
                        <p class="mb-1 text-xs font-bold text-[#d25e43]">قفسه‌گردی</p>
                        <h2 class="text-xl font-bold text-[#18342d] sm:text-2xl">از حال‌ و هوایت انتخاب کن</h2>
                    </div>
                    <span class="text-xs text-[#89958e]">{{ number_format($categories->count()) }} دسته‌بندی</span>
                </div>
                <div class="grid grid-cols-2 gap-3 sm:grid-cols-4">
                    @foreach ($categories as $category)
                        <a wire:key="category-{{ $category->id }}" href="{{ route('books.catalog', ['category' => $category->id]) }}" aria-label="دیدن کتاب‌های دسته‌بندی {{ $category->name }}" class="flex min-h-28 flex-col justify-between rounded border border-[#e3eae2] bg-[#f8faf6] p-4 text-inherit no-underline transition hover:border-[#aabda8] focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#e3694d] sm:min-h-32 sm:p-5">
                            <h3 class="text-sm font-bold text-[#254b3c] sm:text-base">{{ $category->name }}</h3>
                            <p class="mt-4 text-xs text-[#7a8b83]">{{ number_format($category->active_books_count) }} کتاب</p>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <section id="books" class="mx-auto max-w-7xl px-4 py-12 sm:px-6 sm:py-16 lg:px-8">
        <div class="mb-7 flex items-end justify-between gap-4">
            <div>
                <p class="mb-2 text-xs font-bold text-[#d25e43]">برای خواندن بعدی‌ات</p>
                <h2 class="text-2xl font-bold text-[#18342d] sm:text-3xl">تازه در قفسه‌ها</h2>
            </div>
            <div class="flex items-center gap-4">
                <span class="pb-1 text-xs text-[#718078]">{{ number_format($bookCount) }} عنوان</span>
                <a href="{{ route('books.catalog') }}" class="text-sm font-bold text-[#1d5547] underline decoration-[#d5ed83] decoration-2 underline-offset-4">جست‌ و جوی کتاب</a>
            </div>
        </div>

        @if ($books->isNotEmpty())
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($books as $book)
                    <article wire:key="book-{{ $book->id }}" class="overflow-hidden rounded-md border border-[#e1e8e0] bg-white">
                        <a href="{{ route('books.show', $book) }}" class="block transition hover:shadow-md">
                            <div class="flex h-48 items-center justify-center overflow-hidden bg-[#e7eddf]">
                                @if ($book->cover_image)
                                    <img src="{{ asset('storage/'.$book->cover_image) }}" alt="جلد کتاب {{ $book->title }}" class="h-full w-full object-cover">
                                @else
                                    <div class="flex h-full w-full items-end bg-[#dce8cf] p-5">
                                        <span class="max-w-48 border-r-2 border-[#d87355] pr-3 text-lg font-bold leading-8 text-[#254b3c]">{{ $book->title }}</span>
                                    </div>
                                @endif
                            </div>
                            <div class="p-4">
                                <h3 class="truncate font-bold text-[#18342d]">{{ $book->title }}</h3>
                                <p class="mt-2 text-sm text-[#718078]">{{ $book->author?->name ?? 'نویسنده نامشخص' }}</p>
                                <div class="mt-3 flex items-center justify-between gap-2">
                                    @if ($book->publication_year)
                                        <p class="text-xs text-[#89958e]">چاپ {{ $book->publication_year }}</p>
                                    @else
                                        <span></span>
                                    @endif
                                    <span class="text-sm font-bold text-[#1d5547]">{{ number_format((float) $book->price) }} تومان</span>
                                </div>
                            </div>
                        </a>
                    </article>
                @endforeach
            </div>
        @else
            <div class="border-y border-[#dce5dd] py-12 text-center sm:py-16">
                <p class="text-lg font-semibold text-[#35574a]">هنوز کتابی در قفسه نیست.</p>
                <p class="mt-2 text-sm leading-7 text-[#718078]">به‌زودی کتاب‌های تازه برای گردش اضافه می‌شوند.</p>
            </div>
        @endif
    </section>

    <section class="bg-[#e6eddd]">
        <div class="mx-auto flex max-w-7xl flex-col gap-6 px-5 py-10 sm:px-8 md:flex-row md:items-center md:justify-between lg:px-12">
            <div class="max-w-2xl">
                <p class="mb-2 text-xs font-bold text-[#d25e43]">قصه‌ها را زنده نگه داریم</p>
                <h2 class="text-xl font-bold leading-relaxed text-[#18342d] sm:text-2xl">کتابی که خوانده‌ای، می‌تواند شروع ماجرای نفر بعدی باشد.</h2>
                <p class="mt-2 text-sm leading-7 text-[#61736a]">کتاب‌ها با اشتراک‌ گذاشته‌ شدن، راهشان را به قفسه‌های تازه پیدا می‌کنند.</p>
            </div>
            <a href="#categories" class="inline-flex min-h-11 shrink-0 items-center justify-center rounded border border-[#1d5547] px-5 py-3 text-sm font-bold text-[#1d5547] transition hover:bg-[#1d5547] hover:text-white focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#e3694d]">گشتن در دسته‌ها</a>
        </div>
    </section>
</main>

<script>
    const carousel = document.querySelector('[data-carousel]');

    if (carousel) {
        const slides = [...carousel.querySelectorAll('[data-carousel-slide]')];
        const dots = [...carousel.querySelectorAll('[data-carousel-dot]')];
        let activeSlide = 0;
        let autoplayTimer;

        const showSlide = (index) => {
            activeSlide = (index + slides.length) % slides.length;

            slides.forEach((slide, slideIndex) => {
                const isActive = slideIndex === activeSlide;

                slide.classList.toggle('opacity-100', isActive);
                slide.classList.toggle('opacity-0', !isActive);
                slide.classList.toggle('pointer-events-none', !isActive);
                slide.setAttribute('aria-hidden', String(!isActive));
            });

            dots.forEach((dot, dotIndex) => {
                const isActive = dotIndex === activeSlide;

                dot.classList.toggle('w-8', isActive);
                dot.classList.toggle('w-2', !isActive);
                dot.classList.toggle('bg-[#d5ed83]', isActive);
                dot.classList.toggle('bg-white/50', !isActive);
                dot.setAttribute('aria-current', String(isActive));
            });
        };

        const stopAutoplay = () => {
            window.clearInterval(autoplayTimer);
            autoplayTimer = undefined;
        };

        const startAutoplay = () => {
            if (slides.length > 1 && !autoplayTimer && !window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
                autoplayTimer = window.setInterval(() => showSlide(activeSlide + 1), 6500);
            }
        };

        carousel.querySelector('[data-carousel-prev]')?.addEventListener('click', () => showSlide(activeSlide - 1));
        carousel.querySelector('[data-carousel-next]')?.addEventListener('click', () => showSlide(activeSlide + 1));
        dots.forEach((dot) => dot.addEventListener('click', () => showSlide(Number(dot.dataset.carouselDot))));
        carousel.addEventListener('mouseenter', stopAutoplay);
        carousel.addEventListener('mouseleave', startAutoplay);
        carousel.addEventListener('focusin', stopAutoplay);
        carousel.addEventListener('focusout', (event) => {
            if (!carousel.contains(event.relatedTarget)) {
                startAutoplay();
            }
        });

        startAutoplay();
    }
</script>
