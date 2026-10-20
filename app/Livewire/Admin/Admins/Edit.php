<?php

namespace App\Livewire\Admin\Admins;

use App\Livewire\Admin\Concerns\HasAdminShell;
use App\Models\User as UserModel;
use Illuminate\View\View;
use Livewire\Component;

class Edit extends Component
{
    use HasAdminShell;

    public string $resource = 'admins';

    public int $recordId = 0;

    public string $name = '';

    public string $phone = '';

    public bool $isAdmin = true;

    public function mount(int $id): void
    {
        $this->ensureAdmin();
        $user = UserModel::query()->where('is_admin', true)->findOrFail($id);
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
            'isAdmin' => ['boolean'],
        ]);

        UserModel::query()->where('is_admin', true)->findOrFail($this->recordId)->forceFill([
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'is_admin' => (bool) $validated['isAdmin'],
        ])->save();

        session()->flash('resourceStatus', 'تغییرات با موفقیت ذخیره شد.');
        $this->redirectRoute('admin.admins.index');
    }

    public function render(): View
    {
        $this->ensureAdmin();

        return view('livewire.admin.shared.edit', [
            'resource' => $this->resource,
            'resourceTitle' => 'ادمین‌ها',
            'authors' => collect(),
            'categories' => collect(),
        ])->layout('layouts.app', ['title' => 'ویرایش ادمین | پنل مدیریت']);
    }
}
