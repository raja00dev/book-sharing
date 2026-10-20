<?php

namespace App\Livewire;

use Illuminate\View\View;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;

class ProfileEdit extends Component
{
    use WithFileUploads;

    public string $name = '';

    public string $password = '';

    public string $passwordConfirmation = '';

    public $avatar = null;

    public ?string $currentAvatar = null;

    public function mount(): void
    {
        $user = auth()->user();
        abort_unless($user, 403);

        $this->name = $user->name ?? '';
        $this->currentAvatar = $user->avatar;
    }

    public function save(): void
    {
        $user = auth()->user();
        abort_unless($user, 403);

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];

        if ($this->password !== '') {
            $rules['password'] = ['required', 'string', 'min:8', 'same:passwordConfirmation'];
            $rules['passwordConfirmation'] = ['required', 'string'];
        }

        $validated = $this->validate($rules);

        if ($this->avatar) {
            $user->avatar = $this->avatar->store('users', 'public');
        }

        $user->name = $this->name;
        if (isset($validated['password'])) {
            $user->password = $validated['password'];
        }
        $user->save();

        $this->redirectRoute('profile');
    }

    public function render(): View
    {
        return view('livewire.profile-edit')
            ->layout('layouts.app', ['title' => 'ویرایش پروفایل | '.config('app.name')]);
    }
}
