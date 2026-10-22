<x-admin.layout :section-title="'ویرایش '.$resourceTitle" active-route="admin.{{ $resource }}.index" :sidebar-open="$sidebarOpen">
    <section class="max-w-4xl rounded border border-[#e3eae2] bg-white p-5 sm:p-7" aria-labelledby="edit-resource-title">
        <div class="mb-6 border-b border-[#e3eae2] pb-5">
            <p class="text-xs font-bold text-[#d25e43]">مدیریت پنل</p>
            <h2 id="edit-resource-title" class="mt-2 text-xl font-bold text-[#18342d]">ویرایش {{ $resourceTitle }}</h2>
            <p class="mt-2 text-sm text-[#718078]">تغییرات این مورد را اعمال کن و ذخیره را فشار بده.</p>
        </div>

        <form wire:submit="save" class="grid gap-4 sm:grid-cols-2">
            @switch($resource)
                @case('books')
                    <label class="text-sm font-semibold text-[#35574a]">عنوان کتاب<input wire:model="title" class="mt-2 h-12 w-full rounded border border-[#dce5dd] px-3 font-normal text-[#18342d] outline-none focus:border-[#39836e]" type="text">@error('title')<span class="mt-1 block text-xs text-[#bd493e]">{{ $message }}</span>@enderror</label>
                    <label class="text-sm font-semibold text-[#35574a]">نویسنده<select wire:model="authorId" class="mt-2 h-12 w-full rounded border border-[#dce5dd] bg-white px-3 font-normal text-[#18342d] outline-none focus:border-[#39836e]"><option value="">انتخاب نویسنده</option>@foreach ($authors as $author)<option value="{{ $author->id }}">{{ $author->name }}</option>@endforeach</select>@error('authorId')<span class="mt-1 block text-xs text-[#bd493e]">{{ $message }}</span>@enderror</label>
                    <label class="text-sm font-semibold text-[#35574a]">قیمت (تومان)<input wire:model="price" class="mt-2 h-12 w-full rounded border border-[#dce5dd] px-3 font-normal text-[#18342d] outline-none focus:border-[#39836e]" type="number" min="0" step="1000" dir="ltr">@error('price')<span class="mt-1 block text-xs text-[#bd493e]">{{ $message }}</span>@enderror</label>
                    <label class="text-sm font-semibold text-[#35574a]">موجودی<input wire:model="stock" class="mt-2 h-12 w-full rounded border border-[#dce5dd] px-3 font-normal text-[#18342d] outline-none focus:border-[#39836e]" type="number" min="0" dir="ltr">@error('stock')<span class="mt-1 block text-xs text-[#bd493e]">{{ $message }}</span>@enderror</label>
                    <label class="text-sm font-semibold text-[#35574a]">ناشر<input wire:model="publisher" class="mt-2 h-12 w-full rounded border border-[#dce5dd] px-3 font-normal text-[#18342d] outline-none focus:border-[#39836e]" type="text"></label>
                    <label class="text-sm font-semibold text-[#35574a]">سال انتشار<input wire:model="publicationYear" class="mt-2 h-12 w-full rounded border border-[#dce5dd] px-3 font-normal text-[#18342d] outline-none focus:border-[#39836e]" type="number" min="1000" max="9999" dir="ltr"></label>
                    <label class="text-sm font-semibold text-[#35574a]">تعداد صفحه<input wire:model="pages" class="mt-2 h-12 w-full rounded border border-[#dce5dd] px-3 font-normal text-[#18342d] outline-none focus:border-[#39836e]" type="number" min="1" dir="ltr"></label>
                    <div class="sm:col-span-2">
                        <label for="book-cover-image" class="text-sm font-semibold text-[#35574a]">تصویر جلد کتاب</label>
                        <input id="book-cover-image" wire:model="coverImage" class="mt-2 block w-full cursor-pointer rounded border border-[#dce5dd] bg-[#fbfcfa] text-sm text-[#52665d] file:me-4 file:min-h-12 file:border-0 file:bg-[#e6eddd] file:px-4 file:font-semibold file:text-[#35574a] hover:file:bg-[#dce8cf]" type="file" accept="image/*">
                        @if ($currentCoverImage)<img src="{{ asset('storage/'.$currentCoverImage) }}" alt="تصویر کنونی جلد" class="mt-4 h-48 w-36 rounded border border-[#dce5dd] object-cover">@endif
                        @error('coverImage')<span class="mt-1 block text-xs text-[#bd493e]">{{ $message }}</span>@enderror
                    </div>
                    <label class="text-sm font-semibold text-[#35574a] sm:col-span-2">توضیحات<textarea wire:model="description" rows="3" class="mt-2 w-full rounded border border-[#dce5dd] p-3 font-normal text-[#18342d] outline-none focus:border-[#39836e]"></textarea></label>
                    @if ($categories->isNotEmpty())
                        <fieldset class="sm:col-span-2">
                            <legend class="text-sm font-semibold text-[#35574a]">دسته‌بندی‌ها</legend>
                            <div class="mt-3 flex flex-wrap gap-3">
                                @foreach ($categories as $category)
                                    <label wire:key="category-{{ $category->id }}" class="flex items-center gap-2 text-sm text-[#52665d]"><input wire:model="categoryIds" type="checkbox" value="{{ $category->id }}" class="size-4 accent-[#1d5547]">{{ $category->name }}</label>
                                @endforeach
                            </div>
                        </fieldset>
                    @endif
                    @break
                @case('authors')
                    <label class="text-sm font-semibold text-[#35574a]">نام نویسنده<input wire:model="name" class="mt-2 h-12 w-full rounded border border-[#dce5dd] px-3 font-normal text-[#18342d] outline-none focus:border-[#39836e]" type="text">@error('name')<span class="mt-1 block text-xs text-[#bd493e]">{{ $message }}</span>@enderror</label>
                    <div class="sm:col-span-2">
                        <label for="author-image" class="text-sm font-semibold text-[#35574a]">تصویر نویسنده</label>
                        <input id="author-image" wire:model="image" class="mt-2 block w-full cursor-pointer rounded border border-[#dce5dd] bg-[#fbfcfa] text-sm text-[#52665d] file:me-4 file:min-h-12 file:border-0 file:bg-[#e6eddd] file:px-4 file:font-semibold file:text-[#35574a] hover:file:bg-[#dce8cf]" type="file" accept="image/*">
                        @if ($currentImage)<img src="{{ asset('storage/'.$currentImage) }}" alt="تصویر کنونی نویسنده" class="mt-4 h-24 w-24 rounded-full border border-[#dce5dd] object-cover">@endif
                        @error('image')<span class="mt-1 block text-xs text-[#bd493e]">{{ $message }}</span>@enderror
                    </div>
                    <label class="text-sm font-semibold text-[#35574a] sm:col-span-2">زندگی‌نامه<textarea wire:model="bio" rows="3" class="mt-2 w-full rounded border border-[#dce5dd] p-3 font-normal text-[#18342d] outline-none focus:border-[#39836e]"></textarea>@error('bio')<span class="mt-1 block text-xs text-[#bd493e]">{{ $message }}</span>@enderror</label>
                    @break
                @case('categories')
                    <label class="text-sm font-semibold text-[#35574a] sm:col-span-2">نام دسته‌بندی<input wire:model="name" class="mt-2 h-12 w-full rounded border border-[#dce5dd] px-3 font-normal text-[#18342d] outline-none focus:border-[#39836e]" type="text">@error('name')<span class="mt-1 block text-xs text-[#bd493e]">{{ $message }}</span>@enderror</label>
                    @break
                @case('banners')
                    <label class="text-sm font-semibold text-[#35574a] sm:col-span-2">متن بنر<input wire:model="text" class="mt-2 h-12 w-full rounded border border-[#dce5dd] px-3 font-normal text-[#18342d] outline-none focus:border-[#39836e]" type="text">@error('text')<span class="mt-1 block text-xs text-[#bd493e]">{{ $message }}</span>@enderror</label>
                    <div class="sm:col-span-2">
                        <label for="banner-image" class="text-sm font-semibold text-[#35574a]">تصویر بنر</label>
                        <input id="banner-image" wire:model="image" class="mt-2 block w-full cursor-pointer rounded border border-[#dce5dd] bg-[#fbfcfa] text-sm text-[#52665d] file:me-4 file:min-h-12 file:border-0 file:bg-[#e6eddd] file:px-4 file:font-semibold file:text-[#35574a] hover:file:bg-[#dce8cf]" type="file" accept="image/*">
                        @if ($currentImage)<img src="{{ asset('storage/'.$currentImage) }}" alt="تصویر کنونی بنر" class="mt-4 h-24 w-48 rounded border border-[#dce5dd] object-cover">@endif
                        @error('image')<span class="mt-1 block text-xs text-[#bd493e]">{{ $message }}</span>@enderror
                    </div>
                    <label class="flex items-center gap-2 text-sm font-semibold text-[#35574a]"><input wire:model="isBig" type="checkbox" class="size-4 accent-[#1d5547]"> بنر بزرگ</label>
                    <label class="flex items-center gap-2 text-sm font-semibold text-[#35574a]"><input wire:model="status" type="checkbox" class="size-4 accent-[#1d5547]"> فعال</label>
                    @break
                @case('users')
                @case('admins')
                    <label class="text-sm font-semibold text-[#35574a]">نام<input wire:model="name" class="mt-2 h-12 w-full rounded border border-[#dce5dd] px-3 font-normal text-[#18342d] outline-none focus:border-[#39836e]" type="text">@error('name')<span class="mt-1 block text-xs text-[#bd493e]">{{ $message }}</span>@enderror</label>
                    <label class="text-sm font-semibold text-[#35574a]">شماره موبایل<input wire:model="phone" class="mt-2 h-12 w-full rounded border border-[#dce5dd] px-3 font-normal text-[#18342d] outline-none focus:border-[#39836e]" type="tel" dir="ltr">@error('phone')<span class="mt-1 block text-xs text-[#bd493e]">{{ $message }}</span>@enderror</label>
                    @if ($resource === 'users')
                        <label class="text-sm font-semibold text-[#35574a]">رمز عبور جدید (اختیاری)<input wire:model="password" class="mt-2 h-12 w-full rounded border border-[#dce5dd] px-3 font-normal text-[#18342d] outline-none focus:border-[#39836e]" type="password" dir="ltr" autocomplete="new-password">@error('password')<span class="mt-1 block text-xs text-[#bd493e]">{{ $message }}</span>@enderror</label>
                        <label class="text-sm font-semibold text-[#35574a]">تکرار رمز عبور<input wire:model="passwordConfirmation" class="mt-2 h-12 w-full rounded border border-[#dce5dd] px-3 font-normal text-[#18342d] outline-none focus:border-[#39836e]" type="password" dir="ltr" autocomplete="new-password">@error('passwordConfirmation')<span class="mt-1 block text-xs text-[#bd493e]">{{ $message }}</span>@enderror</label>
                    @endif
                    <label class="flex items-center gap-2 text-sm font-semibold text-[#35574a] sm:col-span-2"><input wire:model="isAdmin" type="checkbox" class="size-4 accent-[#1d5547]"> این حساب مدیر باشد</label>
                    @break
                @case('orders')
                    <label class="text-sm font-semibold text-[#35574a]">نام گیرنده<input wire:model="recipientName" class="mt-2 h-12 w-full rounded border border-[#dce5dd] px-3 font-normal text-[#18342d] outline-none focus:border-[#39836e]" type="text">@error('recipientName')<span class="mt-1 block text-xs text-[#bd493e]">{{ $message }}</span>@enderror</label>
                    <label class="text-sm font-semibold text-[#35574a]">شماره موبایل<input wire:model="phone" class="mt-2 h-12 w-full rounded border border-[#dce5dd] px-3 font-normal text-[#18342d] outline-none focus:border-[#39836e]" type="tel" dir="ltr">@error('phone')<span class="mt-1 block text-xs text-[#bd493e]">{{ $message }}</span>@enderror</label>
                    <label class="text-sm font-semibold text-[#35574a]">مبلغ (تومان)<input wire:model="totalPrice" class="mt-2 h-12 w-full rounded border border-[#dce5dd] px-3 font-normal text-[#18342d] outline-none focus:border-[#39836e]" type="number" min="0" step="1" dir="ltr">@error('totalPrice')<span class="mt-1 block text-xs text-[#bd493e]">{{ $message }}</span>@enderror</label>
                    <label class="text-sm font-semibold text-[#35574a]">وضعیت سفارش<select wire:model="statusValue" class="mt-2 h-12 w-full rounded border border-[#dce5dd] bg-white px-3 font-normal text-[#18342d] outline-none focus:border-[#39836e]"><option value="pending">در انتظار پرداخت</option><option value="paid">پرداخت‌شده</option><option value="processing">در حال آماده‌سازی</option><option value="shipped">ارسال‌شده</option><option value="delivered">تحویل‌شده</option><option value="cancelled">لغوشده</option></select>@error('statusValue')<span class="mt-1 block text-xs text-[#bd493e]">{{ $message }}</span>@enderror</label>
                    <label class="text-sm font-semibold text-[#35574a] sm:col-span-2">آدرس تحویل<textarea wire:model="shippingAddress" rows="3" class="mt-2 w-full rounded border border-[#dce5dd] p-3 font-normal text-[#18342d] outline-none focus:border-[#39836e]"></textarea></label>
                    @break
            @endswitch

            <div class="flex items-center gap-3 sm:col-span-2">
                <button type="submit" wire:loading.attr="disabled" class="inline-flex min-h-12 items-center justify-center rounded bg-[#1d5547] px-5 py-3 text-sm font-bold text-white transition hover:bg-[#143f35] disabled:opacity-60">ذخیره تغییرات</button>
                <a href="{{ route('admin.'.$resource.'.index') }}" class="inline-flex min-h-12 items-center justify-center rounded border border-[#dce5dd] bg-white px-5 py-3 text-sm font-bold text-[#35574a] transition hover:bg-[#f5f7f4]">بازگشت</a>
            </div>
        </form>
    </section>
</x-admin.layout>
