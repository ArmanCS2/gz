<?php

namespace App\Livewire\Panel;

use App\Models\Ad;
use App\Models\Payment;
use App\Models\Bid;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $user = auth()->user();
        
        $stats = [
            'ads_count' => Ad::where('user_id', $user->id)->count(),
            'active_ads' => Ad::where('user_id', $user->id)
                ->where('status', 'active')
                ->where('is_active', true)
                ->where(function($q) {
                    $q->whereNull('expire_at')->orWhere('expire_at', '>', now());
                })
                ->count(),
            'total_payments' => Payment::where('user_id', $user->id)->where('status', 'paid')->sum('amount'),
            'bids_count' => Bid::where('user_id', $user->id)->count(),
        ];

        $recent_ads = Ad::where('user_id', $user->id)->latest()->take(5)->get();
        $recent_payments = Payment::where('user_id', $user->id)->latest()->take(5)->get();

        return view('livewire.panel.dashboard', [
            'stats' => $stats,
            'recent_ads' => $recent_ads,
            'recent_payments' => $recent_payments,
        ])->layout('layouts.panel');
    }
}











