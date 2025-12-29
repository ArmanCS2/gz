<?php

namespace App\Livewire\Admin\Ads;

use App\Models\Ad;
use App\Models\Category;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public function approve($id)
    {
        $ad = Ad::findOrFail($id);
        $ad->update(['status' => 'active', 'is_active' => true]);
        $this->dispatch('showToast', ['message' => 'آگهی تایید شد.', 'type' => 'success']);
    }

    public function reject($id)
    {
        $ad = Ad::findOrFail($id);
        $ad->update(['status' => 'rejected']);
        $this->dispatch('showToast', ['message' => 'آگهی رد شد.', 'type' => 'success']);
    }

    public function delete($id)
    {
        $ad = Ad::findOrFail($id);
        $ad->delete(); // Soft delete
        $this->dispatch('showToast', ['message' => 'آگهی حذف شد.', 'type' => 'success']);
    }

    public function render()
    {
        $ads = Ad::with('user')->latest()->paginate(20);
        $ads->setPath('/admin/ads');
        $users = User::orderBy('name')->get();
        $categories = Category::where('is_active', true)->orderBy('order')->orderBy('name')->get();
        
        return view('livewire.admin.ads.index', [
            'ads' => $ads,
            'users' => $users,
            'categories' => $categories,
        ])->layout('layouts.admin');
    }
}

