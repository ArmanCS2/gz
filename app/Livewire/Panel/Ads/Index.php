<?php

namespace App\Livewire\Panel\Ads;

use App\Models\Ad;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public function delete($id)
    {
        $ad = Ad::where('user_id', auth()->id())->findOrFail($id);
        $ad->update(['is_active' => false]);
        $this->dispatch('showToast', ['message' => 'آگهی غیرفعال شد.', 'type' => 'success']);
    }

    public function render()
    {
        $ads = Ad::where('user_id', auth()->id())
            ->latest()
            ->paginate(10);
        
        $ads->setPath('/panel/ads');

        return view('livewire.panel.ads.index', [
            'ads' => $ads,
        ])->layout('layouts.panel');
    }
}






