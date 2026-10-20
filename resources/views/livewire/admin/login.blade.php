<main class="grid min-h-screen place-items-center px-4 py-10">
    <section class="w-full max-w-md" aria-labelledby="admin-login-title">
        <header class="mb-7 text-center">
            <p class="text-sm font-bold text-[#1d5547]">{{ config('app.name') }}</p>
            <h1 id="admin-login-title" class="mt-3 text-2xl font-bold text-[#18342d]">ورود به پنل مدیریت</h1>
            <p class="mt-2 text-sm text-[#718078]">شماره موبایل مدیر و روش ورود را انتخاب کن.</p>
        </header>

        <div class="rounded-lg border border-[#dfe7e1] bg-white p-5 shadow-sm sm:p-7">
            <div class="mb-5 grid grid-cols-2 rounded border border-[#dce5dd] bg-[#f5f8f3] p-1" role="group" aria-label="روش ورود">
                <button type="button" wire:click="switchMode('password')" aria-pressed="{{ $mode === 'password' ? 'true' : 'false' }}" class="min-h-10 rounded px-3 py-2 text-sm font-bold transition {{ $mode === 'password' ? 'bg-[#1d5547] text-white shadow-sm' : 'text-[#61736a] hover:text-[#18342d]' }}">رمز عبور</button>
                <button type="button" wire:click="switchMode('otp')" aria-pressed="{{ $mode === 'otp' ? 'true' : 'false' }}" class="min-h-10 rounded px-3 py-2 text-sm font-bold transition {{ $mode === 'otp' ? 'bg-[#1d5547] text-white shadow-sm' : 'text-[#61736a] hover:text-[#18342d]' }}">کد یکبارمصرف</button>
            </div>

            <form wire:submit="authenticate" class="space-y-4">
                <div>
                    <label for="admin-phone" class="mb-2 block text-sm font-bold text-[#18342d]">شماره موبایل</label>
                    <input id="admin-phone" type="tel" inputmode="numeric" autocomplete="username" wire:model="phone" placeholder="09123456789" maxlength="11" class="h-12 w-full rounded border border-[#dce5dd] bg-[#fbfcfa] px-4 text-left text-[#18342d] outline-none transition placeholder:text-[#a4b0a9] focus:border-[#39836e] focus:ring-2 focus:ring-[#39836e]/15" dir="ltr">
                    @error('phone') <p class="mt-2 text-xs text-[#bd493e]" role="alert">{{ $message }}</p> @enderror
                </div>

                @if ($mode === 'password')
                    <div>
                        <label for="admin-password" class="mb-2 block text-sm font-bold text-[#18342d]">رمز عبور</label>
                        <input id="admin-password" type="password" autocomplete="current-password" wire:model="password" class="h-12 w-full rounded border border-[#dce5dd] bg-[#fbfcfa] px-4 text-left text-[#18342d] outline-none transition focus:border-[#39836e] focus:ring-2 focus:ring-[#39836e]/15" dir="ltr">
                        @error('password') <p class="mt-2 text-xs text-[#bd493e]" role="alert">{{ $message }}</p> @enderror
                        @error('credentials') <p class="mt-2 text-xs text-[#bd493e]" role="alert">{{ $message }}</p> @enderror
                    </div>
                    <button type="submit" wire:loading.attr="disabled" wire:target="authenticate" class="flex min-h-12 w-full items-center justify-center rounded bg-[#1d5547] px-5 py-3 text-sm font-bold text-white transition hover:bg-[#143f35] focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#e3694d] disabled:opacity-60">
                        <span wire:loading.remove wire:target="authenticate">ورود</span>
                        <span wire:loading wire:target="authenticate">در حال بررسی...</span>
                    </button>
                @elseif ($otpReady)
                    <p class="text-sm text-[#718078]">کد ورود را برای <bdi dir="ltr" class="font-semibold text-[#18342d]">{{ $phone }}</bdi> وارد کن.</p>
                    <div>
                        <label for="admin-otp" class="mb-2 block text-sm font-bold text-[#18342d]">کد یکبارمصرف</label>
                        <input id="admin-otp" type="text" inputmode="numeric" autocomplete="one-time-code" wire:model="otp" placeholder="------" maxlength="6" class="h-12 w-full rounded border border-[#dce5dd] bg-[#fbfcfa] px-4 text-left tracking-[0.25em] text-[#18342d] outline-none transition placeholder:text-[#a4b0a9] focus:border-[#39836e] focus:ring-2 focus:ring-[#39836e]/15" dir="ltr">
                        @error('otp') <p class="mt-2 text-xs text-[#bd493e]" role="alert">{{ $message }}</p> @enderror
                    </div>
                    <button type="submit" wire:loading.attr="disabled" wire:target="authenticate" class="flex min-h-12 w-full items-center justify-center rounded bg-[#1d5547] px-5 py-3 text-sm font-bold text-white transition hover:bg-[#143f35] focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#e3694d] disabled:opacity-60">
                        <span wire:loading.remove wire:target="authenticate">ورود</span>
                        <span wire:loading wire:target="authenticate">در حال بررسی...</span>
                    </button>
                    <button type="button" wire:click="switchMode('otp')" class="w-full py-1 text-sm font-semibold text-[#397764] hover:text-[#143f35]">تغییر شماره</button>
                @else
                    <button type="button" wire:click="continueToOtp" wire:loading.attr="disabled" wire:target="continueToOtp" class="flex min-h-12 w-full items-center justify-center rounded bg-[#1d5547] px-5 py-3 text-sm font-bold text-white transition hover:bg-[#143f35] focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#e3694d] disabled:opacity-60">
                        <span wire:loading.remove wire:target="continueToOtp">ادامه</span>
                        <span wire:loading wire:target="continueToOtp">در حال بررسی...</span>
                    </button>
                @endif
            </form>
        </div>

        <p class="mt-8 text-center text-xs text-[#89958e]">این بخش فقط برای حساب‌های دارای دسترسی مدیریت است.</p>
    </section>
</main>
