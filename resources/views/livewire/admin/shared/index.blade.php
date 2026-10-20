<x-admin.layout :section-title="$resourceTitle" active-route="admin.{{ $resource }}.index" :sidebar-open="$sidebarOpen">
    <section class="overflow-hidden rounded border border-[#e3eae2] bg-white" aria-labelledby="resource-list-title">
        <header class="flex flex-col gap-4 border-b border-[#e3eae2] px-4 py-5 sm:flex-row sm:items-center sm:justify-between sm:px-6">
            <div>
                <p class="text-xs font-bold text-[#d25e43]">مدیریت پنل</p>
                <h2 id="resource-list-title" class="mt-1 text-xl font-bold text-[#18342d]">فهرست {{ $resourceTitle }}</h2>
                <p class="mt-1 text-xs text-[#89958e]">{{ number_format($items->total()) }} مورد</p>
            </div>
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
                <label for="resource-search" class="sr-only">جست‌وجو در {{ $resourceTitle }}</label>
                <input id="resource-search" type="search" wire:model.live.debounce.300ms="search" placeholder="جست‌وجو..." class="h-11 w-full rounded border border-[#dce5dd] bg-[#fbfcfa] px-3 text-sm outline-none focus:border-[#39836e] sm:w-64">
                @if ($createRoute)
                    <a href="{{ route($createRoute) }}" class="inline-flex min-h-11 items-center justify-center gap-2 rounded bg-[#1d5547] px-4 py-2 text-sm font-bold text-white transition hover:bg-[#143f35]">افزودن {{ $resource === 'books' ? 'کتاب' : ($resource === 'authors' ? 'نویسنده' : ($resource === 'categories' ? 'دسته‌بندی' : ($resource === 'banners' ? 'بنر' : 'ادمین'))) }} <span aria-hidden="true">+</span></a>
                @endif
            </div>
        </header>
        @if (session('resourceStatus'))
            <div class="border-b border-[#e3eae2] bg-[#edf7ef] px-4 py-3 text-sm text-[#245a48]" role="status">{{ session('resourceStatus') }}</div>
        @endif
        <div class="overflow-x-auto">
            <table class="w-full min-w-[42rem] border-collapse text-right text-sm">
                <thead class="bg-[#f7f9f5] text-xs font-bold text-[#718078]">
                    <tr>
                        @if ($resource === 'books')
                            <th class="px-4 py-3">کتاب</th><th class="px-4 py-3">نویسنده</th><th class="px-4 py-3">قیمت</th><th class="px-4 py-3">موجودی</th>
                        @elseif ($resource === 'authors')
                            <th class="px-4 py-3">نویسنده</th><th class="px-4 py-3">تعداد کتاب</th><th class="px-4 py-3">زندگی‌نامه</th>
                        @elseif ($resource === 'categories')
                            <th class="px-4 py-3">دسته‌بندی</th><th class="px-4 py-3">کتاب‌های فعال</th>
                        @elseif ($resource === 'banners')
                            <th class="px-4 py-3">بنر</th><th class="px-4 py-3">اندازه</th><th class="px-4 py-3">وضعیت</th>
                        @elseif ($resource === 'orders')
                            <th class="px-4 py-3">سفارش</th><th class="px-4 py-3">گیرنده</th><th class="px-4 py-3">مبلغ</th><th class="px-4 py-3">وضعیت</th>
                        @else
                            <th class="px-4 py-3">نام</th><th class="px-4 py-3">شماره موبایل</th><th class="px-4 py-3">دسترسی</th>
                        @endif
                        <th class="px-4 py-3">تاریخ ثبت</th><th class="px-4 py-3 text-center">عملیات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#edf1eb] text-[#35574a]">
                    @forelse ($items as $item)
                        <tr wire:key="{{ $resource }}-{{ $item->id }}" class="transition hover:bg-[#fbfcfa]">
                            @if ($resource === 'books')
                                <td class="px-4 py-3"><div class="flex items-center gap-3">@if ($item->cover_image)<img src="{{ asset('storage/'.$item->cover_image) }}" alt="" class="size-10 rounded border border-[#e1e8e0] object-cover">@else<span class="grid size-10 place-items-center rounded bg-[#e6eddd] text-xs text-[#718078]" aria-hidden="true">—</span>@endif<span class="font-semibold">{{ $item->title }}</span></div></td>
                                <td class="px-4 py-3">{{ $item->author?->name ?? '—' }}</td><td class="whitespace-nowrap px-4 py-3">{{ number_format((float) $item->price) }} تومان</td><td class="px-4 py-3">{{ number_format($item->stock) }}</td>
                            @elseif ($resource === 'authors')
                                <td class="px-4 py-3"><div class="flex items-center gap-3">@if ($item->image)<img src="{{ asset('storage/'.$item->image) }}" alt="" class="size-10 rounded-full border border-[#e1e8e0] object-cover">@endif<span class="font-semibold">{{ $item->name }}</span></div></td><td class="px-4 py-3">{{ number_format($item->books_count) }}</td><td class="max-w-xs truncate px-4 py-3">{{ $item->bio ?: '—' }}</td>
                            @elseif ($resource === 'categories')
                                <td class="px-4 py-3 font-semibold">{{ $item->name }}</td><td class="px-4 py-3">{{ number_format($item->active_books_count) }}</td>
                            @elseif ($resource === 'banners')
                                <td class="px-4 py-3"><div class="flex items-center gap-3">@if (str_starts_with($item->image, 'http'))<img src="{{ $item->image }}" alt="" class="size-12 rounded border border-[#e1e8e0] object-cover">@elseif (str_starts_with($item->image, '/'))<img src="{{ $item->image }}" alt="" class="size-12 rounded border border-[#e1e8e0] object-cover">@else<img src="{{ asset('storage/'.$item->image) }}" alt="" class="size-12 rounded border border-[#e1e8e0] object-cover">@endif<span class="font-semibold">{{ $item->text ?: 'بدون عنوان' }}</span></div></td><td class="px-4 py-3">{{ $item->isBig ? 'بزرگ' : 'معمولی' }}</td><td class="px-4 py-3"><span class="rounded px-2 py-1 text-xs {{ $item->status ? 'bg-[#edf7ef] text-[#245a48]' : 'bg-[#f4efeb] text-[#8a5644]' }}">{{ $item->status ? 'فعال' : 'غیرفعال' }}</span></td>
                            @elseif ($resource === 'orders')
                                <td class="px-4 py-3 font-semibold">#{{ $item->id }}</td><td class="px-4 py-3">{{ $item->recipient_name }}<span class="mt-1 block text-xs text-[#89958e]" dir="ltr">{{ $item->phone }}</span></td><td class="whitespace-nowrap px-4 py-3">{{ number_format((float) $item->total_price) }} تومان</td><td class="px-4 py-3">{{ __('admin.orders.'.$item->status) }}</td>
                            @else
                                <td class="px-4 py-3 font-semibold">{{ $item->name ?: '—' }}</td><td class="px-4 py-3" dir="ltr">{{ $item->phone }}</td><td class="px-4 py-3">{{ $item->is_admin ? 'ادمین' : 'کاربر' }}</td>
                            @endif
                            <td class="whitespace-nowrap px-4 py-3 text-xs text-[#89958e]">{{ \App\Support\PersianDate::format($item->created_at) }}</td>
                            <td class="whitespace-nowrap px-4 py-3"><div class="flex items-center justify-center gap-2">
                                <a href="{{ route($editRoute, ['id' => $item->id]) }}" title="ویرایش" aria-label="ویرایش" class="inline-flex size-9 items-center justify-center rounded-lg bg-[#edf7ef] text-[#245a48] transition hover:bg-[#d8efde]"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L9 17l-4 1 1-4Z"/></svg></a>
                                <button type="button" wire:click="delete({{ $item->id }})" wire:confirm="آیا از حذف این مورد اطمینان دارید؟" wire:loading.attr="disabled" wire:target="delete({{ $item->id }})" title="حذف" aria-label="حذف" class="inline-flex size-9 items-center justify-center rounded-lg bg-[#fff0ed] text-[#d25e43] transition hover:bg-[#ffe0d8] disabled:cursor-not-allowed disabled:opacity-50"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M8 6V4h8v2"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v5"/><path d="M14 11v5"/></svg></button>
                            </div></td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-4 py-12 text-center text-sm text-[#718078]">موردی برای نمایش پیدا نشد.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($items->hasPages())<div class="border-t border-[#e3eae2] px-4 py-4">{{ $items->links('vendor.pagination.custom') }}</div>@endif
    </section>
</x-admin.layout>
