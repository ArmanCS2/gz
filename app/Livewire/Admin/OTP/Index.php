<?php

namespace App\Livewire\Admin\OTP;

use App\Models\OtpLog;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public function render()
    {
        $logs = OtpLog::latest()->paginate(50);
        $logs->setPath('/admin/otp-logs');
        return view('livewire.admin.otp.index', [
            'logs' => $logs,
        ])->layout('layouts.admin');
    }
}







