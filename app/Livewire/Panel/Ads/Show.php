<?php

namespace App\Livewire\Panel\Ads;

use App\Models\Ad;
use Livewire\Component;

class Show extends Component
{
    public Ad $ad;

    public function mount($ad)
    {
        $this->ad = $ad instanceof Ad ? $ad : Ad::findOrFail($ad);
        if ($this->ad->user_id !== auth()->id()) {
            abort(403);
        }
    }

    public function render()
    {
        // Load bids count for auction ads
        if ($this->ad->type === 'auction') {
            $this->ad->loadCount('bids');
        }
        
        return view('livewire.panel.ads.show')->layout('layouts.panel');
    }
}

