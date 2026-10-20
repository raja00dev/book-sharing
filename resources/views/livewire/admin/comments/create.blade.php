<x-admin.layout section-title="افزودن نظر" active-route="admin.comments.index" :sidebar-open="$sidebarOpen">
    <section class="max-w-3xl rounded border border-[#e3eae2] bg-white p-5 sm:p-7" aria-labelledby="create-comment-title">
        <div class="mb-6 border-b border-[#e3eae2] pb-5">
            <p class="text-xs font-bold text-[#d25e43]">مدیریت محتوا</p>
            <h2 id="create-comment-title" class="mt-2 text-xl font-bold text-[#18342d]">ثبت نظر</h2>
        </div>
        <form wire:submit="save" class="grid gap-4">
            <label for="comment-book" class="text-sm font-semibold text-[#35574a]">کتاب
                <select id="comment-book" wire:model="bookId" class="mt-2 h-12 w-full rounded border border-[#dce5dd] bg-white px-3 font-normal text-[#18342d] outline-none focus:border-[#39836e]"><option value="">انتخاب کتاب</option>@foreach ($books as $book)<option value="{{ $book->id }}">{{ $book->title }}</option>@endforeach</select>
                @error('bookId')<span class="mt-1 block text-xs text-[#bd493e]">{{ $message }}</span>@enderror
            </label>
            <label for="comment-user" class="text-sm font-semibold text-[#35574a]">کاربر
                <select id="comment-user" wire:model="userId" class="mt-2 h-12 w-full rounded border border-[#dce5dd] bg-white px-3 font-normal text-[#18342d] outline-none focus:border-[#39836e]"><option value="">انتخاب کاربر</option>@foreach ($users as $user)<option value="{{ $user->id }}">{{ $user->name ?: $user->phone }} ({{ $user->phone }})</option>@endforeach</select>
                @error('userId')<span class="mt-1 block text-xs text-[#bd493e]">{{ $message }}</span>@enderror
            </label>
            <label for="comment-body" class="text-sm font-semibold text-[#35574a]">متن نظر
                <textarea id="comment-body" wire:model="body" rows="5" class="mt-2 w-full rounded border border-[#dce5dd] p-3 font-normal leading-7 text-[#18342d] outline-none focus:border-[#39836e]"></textarea>
                @error('body')<span class="mt-1 block text-xs text-[#bd493e]">{{ $message }}</span>@enderror
            </label>
            <label class="flex items-center gap-2 text-sm font-semibold text-[#35574a]"><input wire:model="isBlocked" type="checkbox" class="size-4 accent-[#1d5547]"> این نظر پس از ثبت مسدود باشد</label>
            <div class="flex items-center gap-3">
                <button type="submit" wire:loading.attr="disabled" class="min-h-12 rounded bg-[#1d5547] px-5 py-3 text-sm font-bold text-white hover:bg-[#143f35] disabled:opacity-60">ثبت نظر</button>
                <a href="{{ route('admin.comments.index') }}" class="inline-flex min-h-12 items-center rounded border border-[#dce5dd] px-5 py-3 text-sm font-bold text-[#35574a]">بازگشت</a>
            </div>
        </form>
    </section>
</x-admin.layout>
