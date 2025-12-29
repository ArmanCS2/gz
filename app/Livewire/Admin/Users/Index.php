<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $showModal = false;
    public $user_id = null;
    public $name = '';
    public $mobile = '';
    public $is_admin = false;
    public $is_verified = false;

    public function openCreateModal()
    {
        $this->resetForm();
        $this->user_id = null;
        $this->showModal = true;
        $this->dispatch('openModal', ['modalId' => 'userModal']);
    }

    public function openEditModal($id)
    {
        $this->resetForm();
        $user = User::findOrFail($id);
        $this->fillEditForm($user);
        $this->user_id = $user->id;
        $this->showModal = true;
        $this->dispatch('openModal', ['modalId' => 'userModal']);
    }

    public function openModal($id = null)
    {
        if ($id) {
            $this->openEditModal($id);
        } else {
            $this->openCreateModal();
        }
    }

    protected function resetForm()
    {
        // Reset all form-related properties to their default values
        $this->reset(['user_id', 'name', 'mobile', 'is_admin', 'is_verified']);
        
        // Set create defaults explicitly
        $this->user_id = null;
        $this->name = '';
        $this->mobile = '';
        $this->is_admin = false;
        $this->is_verified = false;
        
        $this->resetErrorBag();
        $this->resetValidation();
    }

    protected function fillEditForm($user)
    {
        // Explicitly assign each form field from the model
        $this->name = $user->name;
        $this->mobile = $user->mobile;
        $this->is_admin = $user->is_admin;
        $this->is_verified = $user->is_verified;
    }

    public function closeModal()
    {
        $this->resetForm();
        $this->showModal = false;
        $this->dispatch('closeModal', ['modalId' => 'userModal']);
    }

    public function save()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'is_admin' => 'boolean',
            'is_verified' => 'boolean',
        ];

        if ($this->user_id) {
            $rules['mobile'] = 'required|string|size:11|unique:users,mobile,' . $this->user_id;
        } else {
            $rules['mobile'] = 'required|string|size:11|unique:users,mobile';
        }

        $this->validate($rules);

        if ($this->user_id) {
            $user = User::findOrFail($this->user_id);
            $user->update([
                'name' => $this->name,
                'mobile' => $this->mobile,
                'is_admin' => $this->is_admin,
                'is_verified' => $this->is_verified,
            ]);
            $message = 'کاربر به‌روزرسانی شد.';
        } else {
            User::create([
                'name' => $this->name,
                'mobile' => $this->mobile,
                'is_admin' => $this->is_admin,
                'is_verified' => $this->is_verified,
            ]);
            $message = 'کاربر ایجاد شد.';
        }

        // Reset form BEFORE closing modal to ensure clean state
        $this->resetForm();
        $this->showModal = false;
        
        // Dispatch events
        $this->dispatch('closeModal', ['modalId' => 'userModal']);
        $this->dispatch('showToast', ['message' => $message, 'type' => 'success']);
    }

    public function render()
    {
        $users = User::latest()->paginate(20);
        
        // Set pagination path to prevent duplicate 'admin' in URL
        $users->setPath('/admin/users');
        
        return view('livewire.admin.users.index', [
            'users' => $users,
        ])->layout('layouts.admin');
    }
}

