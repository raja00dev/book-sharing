<?php

namespace App\Livewire\Admin\Admins;

use App\Livewire\Admin\Concerns\HasAdminShell;
use App\Models\User;
use Illuminate\View\View;
use Livewire\Component;

class Create extends Component
{
    use HasAdminShell;

    public string $name = '';

    public string $phone = '';

    public string $password = '';

    public string $passwordConfirmation = '';

    public string $statusMessage = '';

    public function mount(): void
    {
        $this->ensureAdmin();
    }

    public function save(): void
    {
        $this->ensureAdmin();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'regex:/^09[0-9]{9}$/', 'unique:users,phone'],
            'password' => ['required', 'string', 'min:8', 'same:passwordConfirmation'],
            'passwordConfirmation' => ['required', 'string'],
        ]);

        $admin = User::create([
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'password' => $validated['password'],
        ]);
        $admin->forceFill(['is_admin' => true])->save();

        $this->reset('name', 'phone', 'password', 'passwordConfirmation');
        $this->statusMessage = 'ادمین جدید با موفقیت اضافه شد.';
    }

    public function render(): View
    {
        $this->ensureAdmin();

        return view('livewire.admin.admins.create')
            ->layout('layouts.app', ['title' => 'افزودن ادمین | پنل مدیریت']);
    }
}
