<?php

namespace App\Livewire\Panel\Ads;

use App\Models\Ad;
use App\Models\Bid;
use Livewire\Component;
use Livewire\WithPagination;

class Bids extends Component
{
    use WithPagination;

    public Ad $ad;
    public $selectedBidId = null;

    public function mount($ad)
    {
        $this->ad = $ad instanceof Ad ? $ad : Ad::findOrFail($ad);
        
        // Check if user owns this ad
        if ($this->ad->user_id !== auth()->id()) {
            abort(403);
        }
        
        // Check if ad is auction type
        if ($this->ad->type !== 'auction') {
            abort(404);
        }
    }

    public function acceptBid($bidId)
    {
        $bid = Bid::findOrFail($bidId);
        
        if ($bid->ad_id !== $this->ad->id) {
            $this->dispatch('showToast', ['message' => 'پیشنهاد معتبر نیست.', 'type' => 'error']);
            return;
        }

        // Update ad current bid
        $this->ad->update(['current_bid' => $bid->amount]);
        
        $this->dispatch('showSwal', [
            'title' => 'موفقیت',
            'text' => 'پیشنهاد با موفقیت پذیرفته شد.',
            'icon' => 'success',
            'confirmButtonText' => 'باشه'
        ]);
    }

    public function rejectBid($bidId)
    {
        $bid = Bid::findOrFail($bidId);
        
        if ($bid->ad_id !== $this->ad->id) {
            $this->dispatch('showToast', ['message' => 'پیشنهاد معتبر نیست.', 'type' => 'error']);
            return;
        }

        // You can add a rejected status field later
        $this->dispatch('showToast', ['message' => 'پیشنهاد رد شد.', 'type' => 'info']);
    }

    public function render()
    {
        $bids = Bid::where('ad_id', $this->ad->id)
            ->with('user')
            ->orderBy('amount', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        $bids->setPath('/panel/ads/' . $this->ad->id . '/bids');

        return view('livewire.panel.ads.bids', [
            'bids' => $bids,
        ])->layout('layouts.panel');
    }
}
