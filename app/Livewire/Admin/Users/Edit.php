<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Livewire\Component;

class Edit extends Component
{
    public User $user;
    public $name = '';
    public $is_admin = false;
    public $is_verified = false;

    public function mount($user)
    {
        $this->user = $user instanceof User ? $user : User::findOrFail($user);
        $this->name = $this->user->name;
        $this->is_admin = $this->user->is_admin;
        $this->is_verified = $this->user->is_verified;
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'is_admin' => 'boolean',
            'is_verified' => 'boolean',
        ]);

        $this->user->update([
            'name' => $this->name,
            'is_admin' => $this->is_admin,
            'is_verified' => $this->is_verified,
        ]);

        // Refresh user and reload values
        $this->user->refresh();
        $this->name = $this->user->name;
        $this->is_admin = $this->user->is_admin;
        $this->is_verified = $this->user->is_verified;

        $this->dispatch('showToast', ['message' => 'کاربر به‌روزرسانی شد.', 'type' => 'success']);
    }

    public function render()
    {
        return view('livewire.admin.users.edit')->layout('layouts.admin');
    }
}

