<x-admin.layout section-title="افزودن نویسنده" active-route="admin.authors.create" :sidebar-open="$sidebarOpen">
    <section class="max-w-3xl rounded border border-[#e3eae2] bg-white p-5 sm:p-7" aria-labelledby="create-author-title">
        <div class="mb-6 border-b border-[#e3eae2] pb-5"><p class="text-xs font-bold text-[#d25e43]">کتابخانه</p><h2 id="create-author-title" class="mt-2 text-xl font-bold text-[#18342d]">مشخصات نویسنده</h2><p class="mt-2 text-sm text-[#718078]">بعد از ثبت، این نویسنده برای اتصال به کتاب‌ها در دسترس است.</p></div>
        @if ($statusMessage)<p class="mb-5 border-r-[3px] border-[#438266] bg-[#edf7ef] px-4 py-3 text-sm text-[#245a48]" role="status">{{ $statusMessage }}</p>@endif
        <form wire:submit="save" class="grid gap-4">
            <label class="text-sm font-semibold text-[#35574a]">نام نویسنده<input id="author-name" wire:model="name" class="mt-2 h-12 w-full rounded border border-[#dce5dd] px-3 font-normal text-[#18342d] outline-none focus:border-[#39836e]" type="text">@error('name')<span class="mt-1 block text-xs text-[#bd493e]">{{ $message }}</span>@enderror</label>
            <div>
                <label for="author-image" class="text-sm font-semibold text-[#35574a]">تصویر نویسنده</label>
                <input id="author-image" wire:model="image" class="mt-2 block w-full cursor-pointer rounded border border-[#dce5dd] bg-[#fbfcfa] text-sm text-[#52665d] file:me-4 file:min-h-12 file:border-0 file:bg-[#e6eddd] file:px-4 file:font-semibold file:text-[#35574a] hover:file:bg-[#dce8cf]" type="file" accept="image/*">
                <p class="mt-2 text-xs text-[#89958e]">اختیاری، حداکثر ۵ مگابایت</p>
                @error('image')<span class="mt-1 block text-xs text-[#bd493e]">{{ $message }}</span>@enderror
                <div wire:loading wire:target="image" class="mt-2 text-xs text-[#718078]">در حال بارگذاری تصویر...</div>
                @if ($image && $image->isPreviewable())<img src="{{ $image->temporaryUrl() }}" alt="پیش‌نمایش تصویر {{ $name }}" class="mt-4 size-28 rounded border border-[#dce5dd] object-cover">@endif
            </div>
            <label class="text-sm font-semibold text-[#35574a]">زندگی‌نامه<textarea wire:model="bio" rows="4" class="mt-2 w-full rounded border border-[#dce5dd] p-3 font-normal text-[#18342d] outline-none focus:border-[#39836e]"></textarea></label>
            <button type="submit" wire:loading.attr="disabled" class="min-h-12 justify-self-start rounded bg-[#1d5547] px-5 py-3 text-sm font-bold text-white hover:bg-[#143f35] disabled:opacity-60">ثبت نویسنده</button>
        </form>
    </section>
</x-admin.layout>
