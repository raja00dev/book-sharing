<x-admin.layout section-title="ویرایش نظر" active-route="admin.comments.index" :sidebar-open="$sidebarOpen">
    <section class="max-w-3xl rounded border border-[#e3eae2] bg-white p-5 sm:p-7" aria-labelledby="edit-comment-title">
        <div class="mb-6 border-b border-[#e3eae2] pb-5">
            <p class="text-xs font-bold text-[#d25e43]">{{ $comment->book?->title ?? 'کتاب حذف شده' }} · {{ $comment->user?->name ?: 'کاربر' }}</p>
            <h2 id="edit-comment-title" class="mt-2 text-xl font-bold text-[#18342d]">ویرایش نظر</h2>
        </div>
        <form wire:submit="save" class="grid gap-4">
            <label for="comment-body" class="text-sm font-semibold text-[#35574a]">متن نظر
                <textarea id="comment-body" wire:model="body" rows="6" class="mt-2 w-full rounded border border-[#dce5dd] p-3 font-normal leading-7 text-[#18342d] outline-none focus:border-[#39836e]"></textarea>
                @error('body')<span class="mt-1 block text-xs text-[#bd493e]">{{ $message }}</span>@enderror
            </label>
            <label class="flex items-start gap-2 text-sm font-semibold text-[#35574a]"><input wire:model="isBlocked" type="checkbox" class="mt-0.5 size-4 accent-[#1d5547]"> مسدود کردن نظر و پنهان‌کردن آن از صفحه‌ی کتاب</label>
            <div class="flex items-center gap-3">
                <button type="submit" wire:loading.attr="disabled" class="min-h-12 rounded bg-[#1d5547] px-5 py-3 text-sm font-bold text-white hover:bg-[#143f35] disabled:opacity-60">ذخیره تغییرات</button>
                <a href="{{ route('admin.comments.index') }}" class="inline-flex min-h-12 items-center rounded border border-[#dce5dd] px-5 py-3 text-sm font-bold text-[#35574a]">بازگشت</a>
            </div>
        </form>
    </section>
</x-admin.layout>
