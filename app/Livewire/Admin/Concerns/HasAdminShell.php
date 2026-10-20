<?php

namespace App\Livewire\Admin\Concerns;

use Illuminate\Support\Facades\Auth;

trait HasAdminShell
{
    public bool $sidebarOpen = false;

    public function toggleSidebar(): void
    {
        $this->ensureAdmin();
        $this->sidebarOpen = ! $this->sidebarOpen;
    }

    public function closeSidebar(): void
    {
        $this->ensureAdmin();
        $this->sidebarOpen = false;
    }

    public function logout(): void
    {
        $this->ensureAdmin();
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();

        $this->redirectRoute('admin.login');
    }

    protected function ensureAdmin(): void
    {
        abort_unless(Auth::user()?->is_admin, 403);
    }
}
