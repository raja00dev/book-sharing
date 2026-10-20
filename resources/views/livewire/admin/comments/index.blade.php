<x-admin.layout section-title="نظرات" active-route="admin.comments.index" :sidebar-open="$sidebarOpen">
    <section class="overflow-hidden rounded border border-[#e3eae2] bg-white" aria-labelledby="comments-title">
        <header class="flex flex-col gap-4 border-b border-[#e3eae2] px-4 py-5 sm:flex-row sm:items-center sm:justify-between sm:px-6">
            <div>
                <p class="text-xs font-bold text-[#d25e43]">مدیریت محتوا</p>
                <h2 id="comments-title" class="mt-1 text-xl font-bold text-[#18342d]">نظرات کاربران</h2>
                <p class="mt-1 text-xs text-[#89958e]">{{ number_format($comments->total()) }} نظر</p>
            </div>
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
                <label for="comment-search" class="sr-only">جست‌وجو در نظرات</label>
                <input id="comment-search" type="search" wire:model.live.debounce.300ms="search" placeholder="متن، کتاب یا کاربر..." class="h-11 w-full rounded border border-[#dce5dd] bg-[#fbfcfa] px-3 text-sm outline-none focus:border-[#39836e] sm:w-64">
                <a href="{{ route('admin.comments.create') }}" class="inline-flex min-h-11 items-center justify-center gap-2 rounded bg-[#1d5547] px-4 py-2 text-sm font-bold text-white transition hover:bg-[#143f35]">افزودن نظر <span aria-hidden="true">+</span></a>
            </div>
        </header>

        @if (session('resourceStatus'))
            <div class="border-b border-[#e3eae2] bg-[#edf7ef] px-4 py-3 text-sm text-[#245a48]" role="status">{{ session('resourceStatus') }}</div>
        @endif

        <div class="overflow-x-auto">
            <table class="w-full min-w-[58rem] border-collapse text-right text-sm">
                <thead class="bg-[#f7f9f5] text-xs font-bold text-[#718078]"><tr><th class="px-4 py-3">نظر</th><th class="px-4 py-3">کتاب</th><th class="px-4 py-3">کاربر</th><th class="px-4 py-3">وضعیت</th><th class="px-4 py-3">تاریخ</th><th class="px-4 py-3 text-center">عملیات</th></tr></thead>
                <tbody class="divide-y divide-[#edf1eb] text-[#35574a]">
                    @forelse ($comments as $comment)
                        <tr wire:key="comment-{{ $comment->id }}" class="align-top transition hover:bg-[#fbfcfa]">
                            <td class="max-w-xl whitespace-pre-line break-words px-4 py-3 leading-7">{{ $comment->body }}</td>
                            <td class="px-4 py-3">{{ $comment->book?->title ?? 'کتاب حذف شده' }}</td>
                            <td class="px-4 py-3">{{ $comment->user?->name ?: $comment->user?->phone ?: 'کاربر حذف شده' }}</td>
                            <td class="px-4 py-3"><span class="rounded px-2 py-1 text-xs {{ $comment->is_blocked ? 'bg-[#fff0ed] text-[#a34031]' : 'bg-[#edf7ef] text-[#245a48]' }}">{{ $comment->is_blocked ? 'مسدود' : 'نمایش داده می‌شود' }}</span></td>
                            <td class="whitespace-nowrap px-4 py-3 text-xs text-[#89958e]">{{ \App\Support\PersianDate::format($comment->created_at) }}</td>
                            <td class="whitespace-nowrap px-4 py-3"><div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.comments.edit', ['id' => $comment->id]) }}" class="inline-flex min-h-9 items-center justify-center rounded border border-[#dce5dd] px-3 text-xs font-bold text-[#35574a] hover:border-[#1d5547]">ویرایش</a>
                                <button type="button" wire:click="toggleBlocked({{ $comment->id }})" wire:confirm="{{ $comment->is_blocked ? 'مسدودیت این نظر برداشته شود؟' : 'این نظر مسدود شود و از صفحه‌ی کتاب پنهان بماند؟' }}" wire:loading.attr="disabled" class="inline-flex min-h-9 items-center justify-center rounded border border-[#ead9d4] px-3 text-xs font-bold text-[#a34031] hover:bg-[#fff0ed] disabled:opacity-50">{{ $comment->is_blocked ? 'رفع مسدودی' : 'مسدود کردن' }}</button>
                                <button type="button" wire:click="delete({{ $comment->id }})" wire:confirm="این نظر برای همیشه حذف شود؟" wire:loading.attr="disabled" class="inline-flex min-h-9 items-center justify-center rounded bg-[#fff0ed] px-3 text-xs font-bold text-[#a34031] hover:bg-[#ffe0d8] disabled:opacity-50">حذف</button>
                            </div></td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-4 py-12 text-center text-sm text-[#718078]">نظری برای نمایش پیدا نشد.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($comments->hasPages())<div class="border-t border-[#e3eae2] px-4 py-4">{{ $comments->links('vendor.pagination.custom') }}</div>@endif
    </section>
</x-admin.layout>
