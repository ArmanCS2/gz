<?php

namespace App\Livewire\Admin\Ads;

use App\Models\Ad;
use App\Models\Payment;
use Livewire\Component;
use Livewire\WithPagination;

class Payments extends Component
{
    use WithPagination;

    public Ad $ad;

    public function mount($ad)
    {
        $this->ad = $ad instanceof Ad ? $ad : Ad::findOrFail($ad);
    }

    public function render()
    {
        $payments = Payment::where('ad_id', $this->ad->id)
            ->with('user')
            ->latest()
            ->paginate(20);
        
        $payments->setPath('/admin/ads/' . $this->ad->id . '/payments');

        return view('livewire.admin.ads.payments', [
            'payments' => $payments,
        ])->layout('layouts.admin');
    }
}

