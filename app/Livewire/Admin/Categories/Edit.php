<?php

namespace App\Livewire\Admin\Categories;

use App\Livewire\Admin\Concerns\HasAdminShell;
use App\Models\Categories as CategoryModel;
use Illuminate\View\View;
use Livewire\Component;

class Edit extends Component
{
    use HasAdminShell;

    public string $resource = 'categories';

    public int $recordId = 0;

    public string $name = '';

    public function mount(int $id): void
    {
        $this->ensureAdmin();
        $category = CategoryModel::query()->findOrFail($id);
        $this->recordId = $category->id;
        $this->name = $category->name;
    }

    public function save(): void
    {
        $this->ensureAdmin();
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255', 'unique:categories,name,'.$this->recordId],
        ]);

        CategoryModel::query()->findOrFail($this->recordId)->forceFill(['name' => $validated['name']])->save();

        session()->flash('resourceStatus', 'تغییرات با موفقیت ذخیره شد.');
        $this->redirectRoute('admin.categories.index');
    }

    public function render(): View
    {
        $this->ensureAdmin();

        return view('livewire.admin.shared.edit', [
            'resource' => $this->resource,
            'resourceTitle' => 'دسته‌بندی‌ها',
            'authors' => collect(),
            'categories' => collect(),
        ])->layout('layouts.app', ['title' => 'ویرایش دسته‌بندی | پنل مدیریت']);
    }
}
