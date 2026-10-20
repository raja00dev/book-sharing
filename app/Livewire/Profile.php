<?php

namespace App\Livewire;

use Illuminate\View\View;
use Livewire\Component;

class Profile extends Component
{
    public function render(): View
    {
        $user = auth()->user();
        abort_unless($user, 403);

        return view('livewire.profile', ['user' => $user])
            ->layout('layouts.app', ['title' => 'پروفایل | '.config('app.name')]);
    }
}
