<main class="mx-auto max-w-3xl px-4 py-10 sm:px-6 lg:px-8">
    <section class="rounded-lg border border-[#dfe7e1] bg-white p-6 sm:p-8">
        <div class="mb-8 flex items-center justify-between gap-3">
            <div>
                <p class="text-xs font-bold text-[#d25e43]">حساب کاربری</p>
                <h1 class="mt-2 text-2xl font-bold text-[#18342d]">ویرایش پروفایل</h1>
            </div>
            <a href="{{ route('profile') }}" aria-label="بازگشت به پروفایل" title="بازگشت به پروفایل" class="inline-flex size-10 items-center justify-center rounded border border-[#dce5dd] text-[#1d5547] transition hover:border-[#1d5547]">
                <x-icon name="back" />
            </a>
        </div>

        <form wire:submit="save" class="space-y-6">
            <div class="flex flex-col items-center gap-4 sm:flex-row sm:items-end">
                <div class="grid size-24 place-items-center overflow-hidden rounded-full border border-[#dfe7e1] bg-[#edf2ea] text-lg font-bold text-[#18342d]">
                    @if ($currentAvatar)
                        <img src="{{ asset('storage/'.$currentAvatar) }}" alt="عکس پروفایل" class="size-full object-cover">
                    @else
                        {{ strtoupper(substr($name ?: 'ک', 0, 1)) }}
                    @endif
                </div>

                <div class="w-full sm:flex-1">
                    <label for="avatar" class="mb-2 block text-sm font-semibold text-[#18342d]">عکس پروفایل</label>
                    <input id="avatar" type="file" wire:model="avatar" accept="image/*" class="block w-full rounded border border-[#dfe7e1] bg-[#f8faf6] px-3 py-2 text-sm text-[#18342d] file:mr-3 file:rounded file:border-0 file:bg-[#d5ed83] file:px-3 file:py-2 file:text-sm file:font-bold file:text-[#18342d]">
                    @error('avatar')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>

            <div>
                <label for="name" class="mb-2 block text-sm font-semibold text-[#18342d]">نام نمایشی</label>
                <input id="name" type="text" wire:model="name" class="w-full rounded border border-[#dfe7e1] bg-[#f8faf6] px-4 py-3 text-base text-[#18342d] outline-none transition focus:border-[#1d5547]" placeholder="نام خودت را وارد کن">
                @error('name')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <fieldset class="space-y-4 border-t border-[#edf1eb] pt-5">
                <legend class="text-sm font-bold text-[#18342d]">تغییر رمز عبور</legend>
                <div>
                    <label for="password" class="mb-2 block text-sm font-semibold text-[#18342d]">رمز عبور جدید</label>
                    <input id="password" type="password" wire:model="password" autocomplete="new-password" class="w-full rounded border border-[#dfe7e1] bg-[#f8faf6] px-4 py-3 text-left text-[#18342d] outline-none transition focus:border-[#1d5547]" dir="ltr">
                    @error('password')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="passwordConfirmation" class="mb-2 block text-sm font-semibold text-[#18342d]">تکرار رمز عبور جدید</label>
                    <input id="passwordConfirmation" type="password" wire:model="passwordConfirmation" autocomplete="new-password" class="w-full rounded border border-[#dfe7e1] bg-[#f8faf6] px-4 py-3 text-left text-[#18342d] outline-none transition focus:border-[#1d5547]" dir="ltr">
                    @error('passwordConfirmation')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
            </fieldset>

            <div class="flex flex-wrap items-center justify-between gap-3 border-t border-[#edf1eb] pt-5">
                <a href="{{ route('profile') }}" class="inline-flex min-h-11 items-center justify-center rounded border border-[#dce5dd] px-4 py-2 text-sm font-semibold text-[#52665d]">لغو</a>
                <button type="submit" wire:loading.attr="disabled" class="inline-flex min-h-11 items-center justify-center rounded bg-[#1d5547] px-5 py-2 text-sm font-bold text-white transition hover:bg-[#133f36] disabled:opacity-60">ذخیره تغییرات</button>
            </div>
        </form>
    </section>
</main>
