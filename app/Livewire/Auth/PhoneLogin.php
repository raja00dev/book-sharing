<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Component;

class PhoneLogin extends Component
{
    public const DEFAULT_OTP = '123456';

    public string $phone = '';

    public string $otp = '';

    public string $step = 'phone';

    public function mount(): void
    {
        if (Auth::check()) {
            $this->redirectRoute('home');
        }
    }

    public function continueToCode(): void
    {
        $this->validate([
            'phone' => ['required', 'regex:/^09[0-9]{9}$/'],
        ]);

        $this->resetValidation('phone');
        $this->step = 'code';
    }

    public function backToPhone(): void
    {
        $this->step = 'phone';
        $this->otp = '';
        $this->resetValidation();
    }

    public function login(): void
    {
        $validated = $this->validate([
            'phone' => ['required', 'regex:/^09[0-9]{9}$/'],
            'otp' => ['required', 'digits:6'],
        ]);

        if ($validated['otp'] !== self::DEFAULT_OTP) {
            $this->addError('otp', __('validation.custom.otp.incorrect'));

            return;
        }

        $user = User::firstOrCreate(
            ['phone' => $validated['phone']],
            ['name' => 'کاربر']
        );

        if ($user->phone_verified_at === null) {
            $user->forceFill(['phone_verified_at' => now()])->save();
        }

        Auth::login($user);
        session()->regenerate();
        $this->redirectRoute('home');
    }

    public function render(): View
    {
        return view('livewire.auth.phone-login')
            ->layout('layouts.app', ['title' => 'ورود | '.config('app.name')]);
    }
}
