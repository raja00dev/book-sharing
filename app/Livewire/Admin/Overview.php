<?php

namespace App\Livewire\Admin;

use App\Livewire\Admin\Concerns\HasAdminShell;
use App\Models\Author;
use App\Models\Banner;
use App\Models\Book;
use App\Models\Categories;
use App\Models\Comment;
use App\Models\Order;
use App\Models\User;
use Illuminate\View\View;
use Livewire\Component;

class Overview extends Component
{
    use HasAdminShell;

    public function mount(): void
    {
        $this->ensureAdmin();
    }

    public function render(): View
    {
        $this->ensureAdmin();

        return view('livewire.admin.overview', [
            'bookCount' => Book::query()->count(),
            'authorCount' => Author::query()->count(),
            'categoryCount' => Categories::query()->count(),
            'commentCount' => Comment::query()->count(),
            'bannerCount' => Banner::query()->where('status', true)->count(),
            'userCount' => User::query()->count(),
            'adminCount' => User::query()->where('is_admin', true)->count(),
            'orderCount' => Order::query()->count(),
            'resourceLinks' => [
                ['label' => 'کتاب‌ها', 'route' => 'admin.books.index', 'count' => Book::query()->count(), 'description' => 'لیست کتاب‌های ثبت‌شده در فروشگاه'],
                ['label' => 'نویسندگان', 'route' => 'admin.authors.index', 'count' => Author::query()->count(), 'description' => 'لیست نویسندگان و بیوگرافی‌های آنها'],
                ['label' => 'دسته‌بندی‌ها', 'route' => 'admin.categories.index', 'count' => Categories::query()->count(), 'description' => 'لیست گروه‌بندی‌های محتوایی سایت'],
                ['label' => 'نظرات', 'route' => 'admin.comments.index', 'count' => Comment::query()->count(), 'description' => 'بررسی، ویرایش و مدیریت وضعیت نظرات کتاب‌ها'],
                ['label' => 'بنرها', 'route' => 'admin.banners.index', 'count' => Banner::query()->count(), 'description' => 'لیست بنرهای صفحه‌ی اصلی و تبلیغاتی'],
                ['label' => 'کاربران', 'route' => 'admin.users.index', 'count' => User::query()->count(), 'description' => 'لیست کاربران و وضعیت حساب‌ها'],
                ['label' => 'ادمین‌ها', 'route' => 'admin.admins.index', 'count' => User::query()->where('is_admin', true)->count(), 'description' => 'لیست مدیران و حساب‌های مدیریتی'],
                ['label' => 'سفارش‌ها', 'route' => 'admin.orders.index', 'count' => Order::query()->count(), 'description' => 'لیست سفارش‌های ثبت‌شده کاربران'],
            ],
        ])->layout('layouts.app', ['title' => 'نمای کلی | پنل مدیریت']);
    }
}
