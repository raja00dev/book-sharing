<?php

namespace App\Livewire\Admin\Users;

use App\Livewire\Admin\Concerns\HasAdminShell;
use App\Models\User as UserModel;
use Illuminate\View\View;
use Livewire\Component;

class Edit extends Component
{
    use HasAdminShell;

    public string $resource = 'users';

    public int $recordId = 0;

    public string $name = '';

    public string $phone = '';

    public string $password = '';

    public string $passwordConfirmation = '';

    public bool $isAdmin = false;

    public function mount(int $id): void
    {
        $this->ensureAdmin();
        $user = UserModel::query()->findOrFail($id);
        $this->recordId = $user->id;
        $this->name = $user->name;
        $this->phone = $user->phone;
        $this->isAdmin = (bool) $user->is_admin;
    }

    public function save(): void
    {
        $this->ensureAdmin();
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'regex:/^09[0-9]{9}$/', 'unique:users,phone,'.$this->recordId],
            'password' => ['nullable', 'string', 'min:8', 'same:passwordConfirmation'],
            'passwordConfirmation' => ['required_with:password', 'string'],
            'isAdmin' => ['boolean'],
        ]);

        $user = UserModel::query()->findOrFail($this->recordId)->forceFill([
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'is_admin' => (bool) $validated['isAdmin'],
        ]);

        if (filled($validated['password'])) {
            $user->password = $validated['password'];
        }

        $user->save();

        session()->flash('resourceStatus', 'تغییرات با موفقیت ذخیره شد.');
        $this->redirectRoute('admin.users.index');
    }

    public function render(): View
    {
        $this->ensureAdmin();

        return view('livewire.admin.shared.edit', [
            'resource' => $this->resource,
            'resourceTitle' => 'کاربران',
            'authors' => collect(),
            'categories' => collect(),
        ])->layout('layouts.app', ['title' => 'ویرایش کاربر | پنل مدیریت']);
    }
}
