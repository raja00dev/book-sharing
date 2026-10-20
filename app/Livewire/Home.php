<?php

namespace App\Livewire;

use App\Models\Banner;
use App\Models\Book;
use App\Models\Categories;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Component;

class Home extends Component
{
    public function logout(): void
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();

        $this->redirectRoute('login');
    }

    public function render(): View
    {
        return view('livewire.home', [
            'bookCount' => Book::query()->where('is_active', true)->count(),
            'banners' => Banner::query()
                ->where('status', true)
                ->orderBy('id')
                ->get(),
            'books' => Book::query()
                ->with('author')
                ->where('is_active', true)
                ->latest()
                ->take(4)
                ->get(),
            'categories' => Categories::query()
                ->withCount(['books as active_books_count' => fn (Builder $query) => $query->where('is_active', true)])
                ->orderBy('name')
                ->get(),
        ])->layout('layouts.app', ['title' => 'خانه | '.config('app.name')]);
    }
}
