<x-admin.layout section-title="افزودن بنر" active-route="admin.banners.create" :sidebar-open="$sidebarOpen">
    <section class="max-w-3xl rounded border border-[#e3eae2] bg-white p-5 sm:p-7" aria-labelledby="create-banner-title">
        <div class="mb-6 border-b border-[#e3eae2] pb-5"><p class="text-xs font-bold text-[#d25e43]">صفحه‌ی اصلی</p><h2 id="create-banner-title" class="mt-2 text-xl font-bold text-[#18342d]">مشخصات بنر</h2><p class="mt-2 text-sm text-[#718078]">بنرهای فعال در اسلایدر صفحه‌ی اصلی نمایش داده می‌شوند.</p></div>
        @if ($statusMessage)<p class="mb-5 border-r-[3px] border-[#438266] bg-[#edf7ef] px-4 py-3 text-sm text-[#245a48]" role="status">{{ $statusMessage }}</p>@endif
        <form wire:submit="save" class="grid gap-4">
            <label class="text-sm font-semibold text-[#35574a]">متن بنر<input wire:model="text" class="mt-2 h-12 w-full rounded border border-[#dce5dd] px-3 font-normal text-[#18342d] outline-none focus:border-[#39836e]" type="text">@error('text')<span class="mt-1 block text-xs text-[#bd493e]">{{ $message }}</span>@enderror</label>
            <div>
                <label for="banner-image" class="text-sm font-semibold text-[#35574a]">تصویر بنر</label>
                <input id="banner-image" wire:model="image" class="mt-2 block w-full cursor-pointer rounded border border-[#dce5dd] bg-[#fbfcfa] text-sm text-[#52665d] file:me-4 file:min-h-12 file:border-0 file:bg-[#e6eddd] file:px-4 file:font-semibold file:text-[#35574a] hover:file:bg-[#dce8cf]" type="file" accept="image/*">
                <p class="mt-2 text-xs text-[#89958e]">تصویر الزامی، حداکثر ۵ مگابایت</p>
                @error('image')<span class="mt-1 block text-xs text-[#bd493e]">{{ $message }}</span>@enderror
                <div wire:loading wire:target="image" class="mt-2 text-xs text-[#718078]">در حال بارگذاری تصویر...</div>
                @if ($image && $image->isPreviewable())<img src="{{ $image->temporaryUrl() }}" alt="پیش‌نمایش بنر {{ $text }}" class="mt-4 h-44 w-full max-w-2xl rounded border border-[#dce5dd] object-cover">@endif
            </div>
            <div class="flex flex-wrap gap-6"><label class="flex items-center gap-2 text-sm text-[#52665d]"><input wire:model="isBig" type="checkbox" class="size-4 accent-[#1d5547]">بنر بزرگ</label><label class="flex items-center gap-2 text-sm text-[#52665d]"><input wire:model="status" type="checkbox" class="size-4 accent-[#1d5547]">نمایش در سایت</label></div>
            <button type="submit" wire:loading.attr="disabled" class="min-h-12 justify-self-start rounded bg-[#1d5547] px-5 py-3 text-sm font-bold text-white hover:bg-[#143f35] disabled:opacity-60">ثبت بنر</button>
        </form>
    </section>
</x-admin.layout>
