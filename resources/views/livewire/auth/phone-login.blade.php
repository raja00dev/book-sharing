<main class="grid min-h-screen place-items-center px-4 py-10">
    <section class="w-full max-w-md" aria-labelledby="login-title">
        <header class="mb-8 text-center">
            <p class="text-sm font-bold text-[#1d5547]">{{ config('app.name') }}</p>
            <h1 id="login-title" class="mt-3 text-2xl font-bold text-[#18342d]">
                {{ $step === 'phone' ? 'ورود به حساب' : 'تایید شماره موبایل' }}
            </h1>
            <p class="mt-2 text-sm leading-6 text-[#718078]">
                {{ $step === 'phone' ? 'شماره موبایلت را وارد کن.' : 'کد شش‌رقمی ارسال‌شده را وارد کن.' }}
            </p>
        </header>

        <div class="rounded-lg border border-[#dfe7e1] bg-white p-5 shadow-sm sm:p-7">
            @if ($step === 'phone')
                <form wire:submit="continueToCode" class="space-y-4">
                    <div>
                        <label for="phone" class="mb-2 block text-sm font-bold">شماره موبایل</label>
                        <input id="phone" type="tel" inputmode="numeric" autocomplete="tel-national" wire:model="phone" placeholder="09123456789" maxlength="11" class="h-12 w-full rounded border border-[#dce5dd] bg-[#fbfcfa] px-4 text-left text-[#18342d] outline-none transition placeholder:text-[#a4b0a9] focus:border-[#39836e] focus:ring-2 focus:ring-[#39836e]/15" dir="ltr" aria-describedby="phone-hint">
                        @error('phone') <p class="mt-2 text-xs text-[#bd493e]" role="alert">{{ $message }}</p> @enderror
                        <p id="phone-hint" class="mt-2 text-xs text-[#89958e]">شماره را با 09 و بدون فاصله وارد کن.</p>
                    </div>
                    <button type="submit" wire:loading.attr="disabled" wire:target="continueToCode" class="flex min-h-12 w-full items-center justify-center rounded bg-[#1d5547] px-5 py-3 text-sm font-bold text-white transition hover:bg-[#143f35] focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#e3694d] disabled:opacity-60">
                        <span wire:loading.remove wire:target="continueToCode">ادامه</span>
                        <span wire:loading wire:target="continueToCode">در حال بررسی...</span>
                    </button>
                </form>
            @else
                <form wire:submit="login" class="space-y-4">
                    <p class="text-sm text-[#718078]">شماره <bdi dir="ltr" class="font-semibold text-[#18342d]">{{ $phone }}</bdi></p>
                    <div>
                        <label for="otp" class="mb-2 block text-sm font-bold">کد یکبارمصرف</label>
                        <input id="otp" type="text" inputmode="numeric" autocomplete="one-time-code" wire:model="otp" placeholder="------" maxlength="6" class="h-12 w-full rounded border border-[#dce5dd] bg-[#fbfcfa] px-4 text-left tracking-[0.25em] text-[#18342d] outline-none transition placeholder:text-[#a4b0a9] focus:border-[#39836e] focus:ring-2 focus:ring-[#39836e]/15" dir="ltr">
                        @error('otp') <p class="mt-2 text-xs text-[#bd493e]" role="alert">{{ $message }}</p> @enderror
                    </div>
                    <button type="submit" wire:loading.attr="disabled" wire:target="login" class="flex min-h-12 w-full items-center justify-center rounded bg-[#1d5547] px-5 py-3 text-sm font-bold text-white transition hover:bg-[#143f35] focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#e3694d] disabled:opacity-60">
                        <span wire:loading.remove wire:target="login">ورود</span>
                        <span wire:loading wire:target="login">در حال ورود...</span>
                    </button>
                    <button type="button" wire:click="backToPhone" class="w-full py-2 text-sm font-semibold text-[#397764] hover:text-[#143f35]">تغییر شماره</button>
                </form>
            @endif
        </div>
    </section>
</main>
