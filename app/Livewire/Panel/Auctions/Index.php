<?php

namespace App\Livewire\Panel\Auctions;

use App\Models\Ad;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public function render()
    {
        $auctions = Ad::where('user_id', auth()->id())
            ->where('type', 'auction')
            ->latest()
            ->paginate(10);
        
        $auctions->setPath('/panel/auctions');

        return view('livewire.panel.auctions.index', [
            'auctions' => $auctions,
        ])->layout('layouts.panel');
    }
}







