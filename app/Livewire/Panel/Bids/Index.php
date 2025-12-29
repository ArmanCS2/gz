<?php

namespace App\Livewire\Panel\Bids;

use App\Models\Bid;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public function render()
    {
        $bids = Bid::where('user_id', auth()->id())
            ->with('ad')
            ->latest()
            ->paginate(10);
        
        $bids->setPath('/panel/bids');

        return view('livewire.panel.bids.index', [
            'bids' => $bids,
        ])->layout('layouts.panel');
    }
}







