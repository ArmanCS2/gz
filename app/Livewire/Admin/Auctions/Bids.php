<?php

namespace App\Livewire\Admin\Auctions;

use App\Models\Ad;
use App\Models\Bid;
use Livewire\Component;
use Livewire\WithPagination;

class Bids extends Component
{
    use WithPagination;

    public Ad $ad;

    public function mount($ad)
    {
        $this->ad = $ad instanceof Ad ? $ad : Ad::findOrFail($ad);
    }

    public function render()
    {
        $bids = Bid::where('ad_id', $this->ad->id)
            ->with('user')
            ->latest()
            ->paginate(20);
        
        $bids->setPath('/admin/auctions/' . $this->ad->id . '/bids');

        return view('livewire.admin.auctions.bids', [
            'bids' => $bids,
        ])->layout('layouts.admin');
    }
}

