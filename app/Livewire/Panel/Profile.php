<?php

namespace App\Livewire\Panel;

use Livewire\Component;

class Profile extends Component
{
    public $name = '';

    public function mount()
    {
        $this->name = auth()->user()->name;
    }

    public function save()
    {
        $this->validate(['name' => 'required|string|max:255']);
        auth()->user()->update(['name' => $this->name]);
        
        // Refresh user and reload name
        auth()->user()->refresh();
        $this->name = auth()->user()->name;
        
        $this->dispatch('showToast', ['message' => 'پروفایل به‌روزرسانی شد.', 'type' => 'success']);
    }

    public function render()
    {
        return view('livewire.panel.profile')->layout('layouts.panel');
    }
}







