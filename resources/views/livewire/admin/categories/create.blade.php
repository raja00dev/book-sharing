<x-admin.layout section-title="افزودن دسته‌بندی" active-route="admin.categories.create" :sidebar-open="$sidebarOpen">
    <section class="max-w-3xl rounded border border-[#e3eae2] bg-white p-5 sm:p-7" aria-labelledby="create-category-title">
        <div class="mb-6 border-b border-[#e3eae2] pb-5">
            <p class="text-xs font-bold text-[#d25e43]">کتابخانه</p>
            <h2 id="create-category-title" class="mt-2 text-xl font-bold text-[#18342d]">مشخصات دسته‌بندی</h2>
            <p class="mt-2 text-sm text-[#718078]">نام تازه‌ای برای دسته‌بندی کتاب‌ها ثبت کن.</p>
        </div>
        @if ($statusMessage)
            <p class="mb-5 border-r-[3px] border-[#438266] bg-[#edf7ef] px-4 py-3 text-sm text-[#245a48]" role="status">{{ $statusMessage }}</p>
        @endif
        <form wire:submit="save" class="grid gap-4">
            <label for="category-name" class="text-sm font-semibold text-[#35574a]">نام دسته‌بندی
                <input id="category-name" wire:model="name" class="mt-2 h-12 w-full rounded border border-[#dce5dd] px-3 font-normal text-[#18342d] outline-none focus:border-[#39836e]" type="text" required>
                @error('name')<span class="mt-1 block text-xs text-[#bd493e]">{{ $message }}</span>@enderror
            </label>
            <div class="flex items-center gap-3">
                <button type="submit" wire:loading.attr="disabled" class="min-h-12 rounded bg-[#1d5547] px-5 py-3 text-sm font-bold text-white transition hover:bg-[#143f35] disabled:opacity-60">ثبت دسته‌بندی</button>
                <a href="{{ route('admin.categories.index') }}" class="inline-flex min-h-12 items-center rounded border border-[#dce5dd] px-5 py-3 text-sm font-bold text-[#35574a]">بازگشت به فهرست</a>
            </div>
        </form>
    </section>
</x-admin.layout>
