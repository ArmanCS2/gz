<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Ad;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Shetabit\Payment\Facade\Payment as PaymentFacade;

class PaymentController extends Controller
{
    public function verify(Request $request, Payment $payment)
    {
        try {
            $settings = SiteSetting::getSettings();
            
            // Set merchant ID from settings
            $merchantId = $settings->zarinpal_merchant_id;
            $mode = 'normal'; // Default to normal (real gateway)
            
            if (empty($merchantId)) {
                // Use Zarinpal's official test merchant ID for sandbox
                // This is the official test merchant ID provided by Zarinpal for sandbox testing
                $merchantId = '71c705f8-bd37-11e6-aa0c-000c295eb8fc';
                $mode = 'sandbox'; // Use sandbox if merchant ID is not set
            }
            
            config(['payment.drivers.zarinpal.merchantId' => $merchantId]);
            config(['payment.drivers.zarinpal.mode' => $mode]);
            
            $receipt = PaymentFacade::amount($payment->amount)
                ->via('zarinpal')
                ->transactionId($payment->authority)
                ->verify();

            if ($receipt) {
                $payment->update([
                    'status' => 'paid',
                    'ref_id' => $receipt->getReferenceId(),
                    'paid_at' => now(),
                ]);

                $ad = $payment->ad;
                if ($ad) {
                    $ad->update([
                        'is_active' => true,
                        'status' => 'active',
                        'expire_at' => now()->addDays($payment->days),
                        'paid_at' => now(),
                    ]);
                }

                // Clear session after successful payment
                session()->forget(['pending_ad_id', 'pending_payment_days']);

                return redirect()->route('panel.payments.index')
                    ->with('success', 'پرداخت با موفقیت انجام شد.');
            } else {
                $payment->update(['status' => 'failed']);
                
                // غیرفعال کردن آگهی در صورت پرداخت ناموفق
                $ad = $payment->ad;
                if ($ad) {
                    $ad->update([
                        'is_active' => false,
                        'status' => 'pending',
                    ]);
                }
                
                return redirect()->route('panel.payments.index')
                    ->with('error', 'پرداخت ناموفق بود.');
            }
        } catch (\Exception $e) {
            $payment->update(['status' => 'failed']);
            
            // غیرفعال کردن آگهی در صورت خطا در پرداخت
            $ad = $payment->ad;
            if ($ad) {
                $ad->update([
                    'is_active' => false,
                    'status' => 'pending',
                ]);
            }
            
            return redirect()->route('panel.payments.index')
                ->with('error', 'خطا در پردازش پرداخت: ' . $e->getMessage());
        }
    }
}

