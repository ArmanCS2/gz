<?php

namespace App\Livewire\Panel\Payments;

use App\Models\Payment;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public function render()
    {
        $payments = Payment::where('user_id', auth()->id())
            ->with('ad')
            ->latest()
            ->paginate(10);
        
        $payments->setPath('/panel/payments');

        return view('livewire.panel.payments.index', [
            'payments' => $payments,
        ])->layout('layouts.panel');
    }
}







