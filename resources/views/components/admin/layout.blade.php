@props(['sectionTitle', 'activeRoute', 'sidebarOpen' => false])

@php
    $menuSections = [
        ['title' => 'عمومی', 'items' => [['label' => 'نمای کلی', 'route' => 'admin.dashboard', 'icon' => 'overview']]],
        ['title' => 'کتابخانه', 'items' => [
            ['label' => 'کتاب‌ها', 'route' => 'admin.books.index', 'icon' => 'book'],
            ['label' => 'افزودن کتاب', 'route' => 'admin.books.create', 'icon' => 'book'],
            ['label' => 'نویسندگان', 'route' => 'admin.authors.index', 'icon' => 'author'],
            ['label' => 'افزودن نویسنده', 'route' => 'admin.authors.create', 'icon' => 'author'],
            ['label' => 'دسته‌بندی‌ها', 'route' => 'admin.categories.index', 'icon' => 'categories'],
            ['label' => 'افزودن دسته‌بندی', 'route' => 'admin.categories.create', 'icon' => 'categories'],
            ['label' => 'نظرات', 'route' => 'admin.comments.index', 'icon' => 'comments'],
            ['label' => 'بنرها', 'route' => 'admin.banners.index', 'icon' => 'banner'],
            ['label' => 'افزودن بنر', 'route' => 'admin.banners.create', 'icon' => 'banner'],
        ]],
        ['title' => 'فروش', 'items' => [['label' => 'سفارش‌ها', 'route' => 'admin.orders.index', 'icon' => 'orders']]],
        ['title' => 'دسترسی‌ها', 'items' => [
            ['label' => 'کاربران', 'route' => 'admin.users.index', 'icon' => 'users'],
            ['label' => 'ادمین‌ها', 'route' => 'admin.admins.index', 'icon' => 'admin'],
            ['label' => 'افزودن ادمین', 'route' => 'admin.admins.create', 'icon' => 'admin'],
        ]],
    ];
@endphp

<main class="min-h-screen bg-[#f3f6f1] lg:grid lg:grid-cols-[17rem_minmax(0,1fr)]">
    @if ($sidebarOpen)
        <button type="button" wire:click="closeSidebar" aria-label="بستن منو" class="fixed inset-0 z-30 bg-[#10251f]/50 lg:hidden"></button>
    @endif

    <aside id="admin-sidebar" class="fixed inset-y-0 right-0 z-40 flex w-72 flex-col border-l border-white/10 bg-[#183a32] text-[#eef4e9] transition-transform duration-200 lg:sticky lg:top-0 lg:h-screen lg:translate-x-0 {{ $sidebarOpen ? 'translate-x-0' : 'translate-x-full' }}">
        <div class="flex h-20 items-center justify-between border-b border-white/10 px-5">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 text-white no-underline">
                <span class="grid size-10 place-items-center rounded bg-[#d5ed83] text-lg font-extrabold text-[#18342d]" aria-hidden="true">ک</span>
                <span><span class="block font-extrabold">{{ config('app.name') }}</span><span class="block text-[11px] text-[#afc4b8]">پنل مدیریت</span></span>
            </a>
            <button type="button" wire:click="closeSidebar" aria-label="بستن منو" class="grid size-9 place-items-center rounded text-xl text-[#c0d6c8] hover:bg-white/10 lg:hidden">×</button>
        </div>

        <nav class="flex-1 space-y-7 overflow-y-auto px-3 py-6 scrollbar-thin scrollbar-thumb-[#6e8f82] scrollbar-track-transparent" aria-label="منوی مدیریت">
            @foreach ($menuSections as $section)
                <div>
                    <p class="mb-2 px-3 text-[11px] font-bold text-[#90aa9d]">{{ $section['title'] }}</p>
                    <ul class="space-y-1">
                        @foreach ($section['items'] as $item)
                            @php($isActive = $activeRoute === $item['route'])
                            <li>
                                <a href="{{ route($item['route']) }}" aria-current="{{ $isActive ? 'page' : 'false' }}" class="flex min-h-11 items-center gap-3 rounded px-3 py-2 text-sm font-semibold transition {{ $isActive ? 'bg-[#d5ed83] text-[#18342d]' : 'text-[#cfddd4] hover:bg-white/10 hover:text-white' }}">
                                    <span class="grid size-6 shrink-0 place-items-center" aria-hidden="true">
                                        <svg viewBox="0 0 24 24" class="size-5" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                                            @switch ($item['icon'])
                                                @case('overview')
                                                    <rect x="3.5" y="3.5" width="7" height="7" rx="1" />
                                                    <rect x="13.5" y="3.5" width="7" height="7" rx="1" />
                                                    <rect x="3.5" y="13.5" width="7" height="7" rx="1" />
                                                    <rect x="13.5" y="13.5" width="7" height="7" rx="1" />
                                                    @break
                                                @case('book')
                                                    <path d="M12 6.2v14.3c-1.7-1.2-3.4-1.8-5.4-1.8H4.5V5.2h2.1c2.1 0 3.8.3 5.4 1Z" />
                                                    <path d="M12 6.2v14.3c1.7-1.2 3.4-1.8 5.4-1.8h2.1V5.2h-2.1c-2.1 0-3.8.3-5.4 1Z" />
                                                    @break
                                                @case('author')
                                                    <circle cx="12" cy="8" r="3.2" />
                                                    <path d="M5.2 20v-1.4a6.8 6.8 0 0 1 13.6 0V20" />
                                                    @break
                                                @case('banner')
                                                    <rect x="3.5" y="4.5" width="17" height="15" rx="1.5" />
                                                    <circle cx="9" cy="9.3" r="1.4" />
                                                    <path d="m5 17 4.8-4.5 3.2 2.8 2.2-2 3.8 3.7" />
                                                    @break
                                                @case('admin')
                                                    <circle cx="9" cy="8" r="3" />
                                                    <path d="M3.5 20v-1.2a5.5 5.5 0 0 1 11 0V20" />
                                                    <path d="M16 5.5a3 3 0 0 1 0 5.8M17 14.5a5.2 5.2 0 0 1 3.5 4.9V20" />
                                                    @break
                                                @case('categories')
                                                    <path d="m3.5 7 8.5-3.5L20.5 7 12 10.5 3.5 7Z" />
                                                    <path d="m3.5 12 8.5 3.5 8.5-3.5M3.5 17l8.5 3.5 8.5-3.5" />
                                                    @break
                                                @case('comments')
                                                    <path d="M20 11.5a7.5 7.5 0 0 1-7.5 7.5H5l-1.5 1.5V11.5A7.5 7.5 0 0 1 11 4h1.5A7.5 7.5 0 0 1 20 11.5Z" />
                                                    <path d="M8 11h8M8 14.5h5" />
                                                    @break
                                                @case('users')
                                                    <circle cx="9" cy="8" r="3" />
                                                    <path d="M3.5 20v-1.2a5.5 5.5 0 0 1 11 0V20" />
                                                    <path d="M16 8h5M18.5 5.5v5" />
                                                    @break
                                                @case('orders')
                                                    <path d="M7 4.5h10v16l-5-3-5 3v-16Z" />
                                                    <path d="M9.5 9h5M9.5 12.5h5" />
                                                    @break
                                            @endswitch
                                        </svg>
                                    </span>
                                    <span>{{ $item['label'] }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </nav>

        <div class="border-t border-white/10 p-4">
            <div class="mb-3 flex min-w-0 items-center gap-3 px-2">
                <span class="grid size-9 shrink-0 place-items-center rounded-full bg-[#527667] text-sm font-bold text-white">{{ mb_substr(auth()->user()->name ?: 'م', 0, 1) }}</span>
                <div class="min-w-0"><p class="truncate text-sm font-semibold">{{ auth()->user()->name ?: 'مدیر' }}</p><p class="truncate text-xs text-[#afc4b8]" dir="ltr">{{ auth()->user()->phone }}</p></div>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('home') }}" class="flex min-h-10 flex-1 items-center justify-center rounded border border-white/15 px-3 text-xs font-semibold text-[#d4e2d9] hover:bg-white/10">نمایش سایت</a>
                <button type="button" wire:click="logout" wire:loading.attr="disabled" class="min-h-10 rounded border border-white/15 px-3 text-xs font-semibold text-[#d4e2d9] hover:bg-white/10 disabled:opacity-60">خروج</button>
            </div>
        </div>
    </aside>

    <div class="min-w-0">
        <header class="sticky top-0 z-20 border-b border-[#e3eae2] bg-white/95 backdrop-blur">
            <nav class="flex h-16 items-center justify-between gap-4 px-4 sm:px-6 lg:px-8" aria-label="ناوبری صفحه">
                <div class="flex min-w-0 items-center gap-3">
                    <button type="button" wire:click="toggleSidebar" aria-controls="admin-sidebar" aria-expanded="{{ $sidebarOpen ? 'true' : 'false' }}" aria-label="بازکردن منوی مدیریت" class="flex size-10 shrink-0 flex-col items-center justify-center gap-1.5 rounded border border-[#dce5dd] text-[#35574a] hover:bg-[#f3f6f1] lg:hidden"><span class="h-0.5 w-4 bg-current"></span><span class="h-0.5 w-4 bg-current"></span><span class="h-0.5 w-4 bg-current"></span></button>
                    <div class="min-w-0"><p class="truncate text-xs text-[#89958e]">پنل مدیریت / {{ $sectionTitle }}</p><h1 class="truncate text-sm font-bold text-[#18342d] sm:text-base">{{ $sectionTitle }}</h1></div>
                </div>
                <span class="hidden text-xs text-[#718078] sm:inline">{{ \App\Support\PersianDate::format(now()) }}</span>
            </nav>
        </header>
        <div class="mx-auto max-w-[1500px] p-4 sm:p-6 lg:p-8">{{ $slot }}</div>
    </div>
</main>