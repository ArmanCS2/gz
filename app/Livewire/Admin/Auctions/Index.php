<?php

namespace App\Livewire\Admin\Auctions;

use App\Models\Ad;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public function render()
    {
        $auctions = Ad::where('type', 'auction')
            ->with('user')
            ->latest()
            ->paginate(20);
        
        $auctions->setPath('/admin/auctions');

        return view('livewire.admin.auctions.index', [
            'auctions' => $auctions,
        ])->layout('layouts.admin');
    }
}







