<?php

namespace App\Livewire\Admin;

use App\Models\Ad;
use App\Models\User;
use App\Models\Payment;
use App\Models\Bid;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $stats = [
            'users_count' => User::count(),
            'ads_count' => Ad::count(),
            'active_ads' => Ad::where('status', 'active')->where('is_active', true)->count(),
            'pending_ads' => Ad::where('status', 'pending')->count(),
            'total_payments' => Payment::where('status', 'paid')->sum('amount'),
            'bids_count' => Bid::count(),
        ];

        $recent_ads = Ad::latest()->take(10)->get();
        $recent_payments = Payment::where('status', 'paid')->latest()->take(10)->get();

        return view('livewire.admin.dashboard', [
            'stats' => $stats,
            'recent_ads' => $recent_ads,
            'recent_payments' => $recent_payments,
        ])->layout('layouts.admin');
    }
}











