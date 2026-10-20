<?php

namespace App\Livewire\Admin;

use App\Livewire\Auth\PhoneLogin as PhoneLoginComponent;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Livewire\Component;

class Login extends Component
{
    public string $phone = '';

    public string $password = '';

    public string $otp = '';

    public string $mode = 'password';

    public bool $otpReady = false;

    public function mount(): void
    {
        if (Auth::user()?->is_admin) {
            $this->redirectRoute('admin.dashboard');
        }
    }

    public function switchMode(string $mode): void
    {
        if (! in_array($mode, ['password', 'otp'], true)) {
            return;
        }

        $this->mode = $mode;
        $this->reset('password', 'otp');
        $this->otpReady = false;
        session()->forget('admin_otp_phone');
        $this->resetValidation();
    }

    public function updatedPhone(): void
    {
        if ($this->mode === 'otp') {
            $this->otpReady = false;
            $this->otp = '';
            session()->forget('admin_otp_phone');
            $this->resetValidation('otp');
        }
    }

    public function continueToOtp(): void
    {
        $validated = $this->validate([
            'phone' => ['required', 'regex:/^09[0-9]{9}$/'],
        ]);

        $adminExists = User::query()
            ->where('phone', $validated['phone'])
            ->where('is_admin', true)
            ->exists();

        if (! $adminExists) {
            $this->addError('phone', __('auth.admin_failed'));
            $this->otpReady = false;
            session()->forget('admin_otp_phone');

            return;
        }

        $this->resetValidation('phone', 'otp');
        $this->otp = '';
        $this->otpReady = true;
        session()->put('admin_otp_phone', $validated['phone']);
    }

    public function authenticate(): void
    {
        if (! in_array($this->mode, ['password', 'otp'], true)) {
            $this->addError('mode', __('auth.admin_failed'));

            return;
        }

        if ($this->mode === 'otp' && (! $this->otpReady || session('admin_otp_phone') !== $this->phone)) {
            $this->otpReady = false;
            $this->addError('phone', __('auth.admin_otp_send_first'));

            return;
        }

        $validated = $this->validate([
            'phone' => ['required', 'regex:/^09[0-9]{9}$/'],
            'password' => $this->mode === 'password' ? ['required', 'string'] : ['nullable', 'string'],
            'otp' => $this->mode === 'otp' ? ['required', 'digits:6'] : ['nullable', 'string'],
        ]);

        $admin = User::query()
            ->where('phone', $validated['phone'])
            ->where('is_admin', true)
            ->first();

        $credentialsAreValid = $this->mode === 'password'
            ? $admin !== null && $admin->password !== null && Hash::check($validated['password'], $admin->password)
            : $admin !== null && $validated['otp'] === PhoneLoginComponent::DEFAULT_OTP;

        if (! $credentialsAreValid) {
            $this->addError($this->mode === 'password' ? 'credentials' : 'otp', $this->mode === 'password'
                ? __('auth.admin_failed')
                : __('validation.custom.otp.incorrect'));

            return;
        }

        if ($this->mode === 'otp' && $admin->phone_verified_at === null) {
            $admin->forceFill(['phone_verified_at' => now()])->save();
        }

        Auth::login($admin);
        session()->regenerate();
        session()->forget('admin_otp_phone');
        $this->redirectRoute('admin.dashboard');
    }

    public function render(): View
    {
        return view('livewire.admin.login')
            ->layout('layouts.app', ['title' => 'ورود مدیر | '.config('app.name')]);
    }
}
