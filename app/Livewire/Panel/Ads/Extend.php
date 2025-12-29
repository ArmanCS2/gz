<?php

namespace App\Livewire\Panel\Ads;

use App\Models\Ad;
use App\Models\Payment;
use App\Models\SiteSetting;
use Livewire\Component;
use Illuminate\Support\Facades\Log;
use Shetabit\Payment\Facade\Payment as PaymentFacade;
use Shetabit\Multipay\Invoice;
use Shetabit\Multipay\Exceptions\PurchaseFailedException;
use Shetabit\Multipay\Exceptions\InvalidPaymentException;

class Extend extends Component
{
    public Ad $ad;
    public $days = 30;
    public $amount = 0;
    public $dailyPrice = 0;

    public function mount($ad)
    {
        $this->ad = $ad instanceof Ad ? $ad : Ad::findOrFail($ad);
        if ($this->ad->user_id !== auth()->id()) {
            abort(403);
        }
        $this->calculateAmount();
    }

    public function updatedDays()
    {
        $this->calculateAmount();
    }

    public function calculateAmount()
    {
        $settings = SiteSetting::getSettings();
        $this->dailyPrice = $this->ad->type === 'auction' ? $settings->auction_daily_price : $settings->ad_daily_price;
        $this->amount = $this->days * $this->dailyPrice;
    }

    public function pay()
    {
        $settings = SiteSetting::getSettings();

        // Create payment record
        try {
            $payment = Payment::create([
                'user_id' => auth()->id(),
                'ad_id' => $this->ad->id,
                'amount' => $this->amount,
                'days' => $this->days,
                'status' => 'pending',
            ]);
        } catch (\Exception $e) {
            $this->dispatch('showToast', ['message' => 'خطا در ایجاد رکورد پرداخت: ' . $e->getMessage(), 'type' => 'error']);
            return;
        }

        // Configure payment gateway
        $merchantId = $settings->zarinpal_merchant_id;
        $mode = 'normal'; // Default to normal (real gateway)
        
        if (empty($merchantId)) {
            // Use Zarinpal's official test merchant ID for sandbox
            $merchantId = '71c705f8-bd37-11e6-aa0c-000c295eb8fc';
            $mode = 'sandbox'; // Use sandbox if merchant ID is not set
        }
        
        config(['payment.drivers.zarinpal.merchantId' => $merchantId]);
        config(['payment.drivers.zarinpal.mode' => $mode]);
        config(['payment.drivers.zarinpal.callbackUrl' => route('payment.verify', $payment)]);
        
        Log::info('Payment Gateway Config', [
            'merchant_id' => $merchantId,
            'mode' => $mode,
            'amount' => $this->amount,
            'days' => $this->days,
            'ad_id' => $this->ad->id,
            'callback_url' => route('payment.verify', $payment)
        ]);

        try {
            if ($this->amount < 1000) {
                $this->dispatch('showToast', ['message' => 'حداقل مبلغ پرداخت 1000 تومان است.', 'type' => 'error']);
                $payment->update(['status' => 'failed']);
                return;
            }

            $amountInt = (int)round($this->amount);
            
            if ($amountInt < 1000) {
                $this->dispatch('showToast', ['message' => 'حداقل مبلغ پرداخت 1000 تومان است.', 'type' => 'error']);
                $payment->update(['status' => 'failed']);
                return;
            }
            
            $invoice = (new Invoice)->amount($amountInt);
            
            Log::info('Attempting payment purchase', [
                'amount' => $amountInt,
                'merchant_id' => $merchantId ?: 'empty',
                'mode' => $mode,
                'callback_url' => route('payment.verify', $payment)
            ]);
            
            $redirectionForm = PaymentFacade::via('zarinpal')
                ->callbackUrl(route('payment.verify', $payment))
                ->purchase($invoice, function($driver, $transactionId) use ($payment) {
                    Log::info('Payment purchase callback', [
                        'driver' => $driver,
                        'transaction_id' => $transactionId,
                        'payment_id' => $payment->id
                    ]);
                    $payment->update(['authority' => $transactionId]);
                })
                ->pay();
            
            $paymentUrl = $redirectionForm->getAction();
            
            Log::info('Redirecting to payment gateway', [
                'payment_url' => $paymentUrl,
                'payment_id' => $payment->id
            ]);
            
            return redirect()->to($paymentUrl);
        } catch (\Illuminate\Validation\ValidationException $e) {
            if (isset($payment)) {
                $payment->update(['status' => 'failed']);
            }
            $errors = $e->errors();
            $errorMessage = 'خطای اعتبارسنجی: ' . implode(', ', array_map(function($messages) {
                return implode(', ', $messages);
            }, $errors));
            \Log::error('Payment Validation Error', ['errors' => $errors, 'payment' => $payment->id ?? null]);
            $this->dispatch('showToast', ['message' => $errorMessage, 'type' => 'error']);
            return;
        } catch (\Shetabit\Multipay\Exceptions\PurchaseFailedException $e) {
            if (isset($payment)) {
                $payment->update(['status' => 'failed']);
            }
            \Log::error('Payment Purchase Failed Exception', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'payment' => $payment->id ?? null,
                'merchant_id' => $merchantId ?? null,
                'amount' => $amountInt ?? null
            ]);
            $this->dispatch('showToast', ['message' => 'خطا در درگاه پرداخت: ' . $e->getMessage(), 'type' => 'error']);
            return;
        } catch (\Shetabit\Multipay\Exceptions\InvalidPaymentException $e) {
            if (isset($payment)) {
                $payment->update(['status' => 'failed']);
            }
            \Log::error('Payment Invalid Exception', ['message' => $e->getMessage(), 'payment' => $payment->id ?? null]);
            $this->dispatch('showToast', ['message' => 'خطا در درگاه پرداخت: ' . $e->getMessage(), 'type' => 'error']);
            return;
        } catch (\Exception $e) {
            if (isset($payment)) {
                $payment->update(['status' => 'failed']);
            }
            
            $errorClass = get_class($e);
            $errorMsg = $e->getMessage();
            $errorFile = $e->getFile();
            $errorLine = $e->getLine();
            
            \Log::error('Payment Exception', [
                'class' => $errorClass,
                'message' => $errorMsg,
                'file' => $errorFile,
                'line' => $errorLine,
                'trace' => $e->getTraceAsString(),
                'payment' => $payment->id ?? null,
                'amount' => $this->amount ?? null,
                'days' => $this->days ?? null,
                'merchant_id' => $merchantId ?? null
            ]);
            
            $errorMessage = 'خطا در پردازش پرداخت';
            
            if (str_contains($errorMsg, 'validation') || str_contains($errorMsg, 'اعتبارسنجی') || str_contains($errorMsg, 'Validation')) {
                $errorMessage = 'خطای اعتبارسنجی: لطفا مقادیر را بررسی کنید.';
            } elseif (str_contains($errorMsg, 'merchant') || str_contains($errorMsg, 'Merchant')) {
                $errorMessage = 'خطا در تنظیمات درگاه پرداخت. لطفا با پشتیبانی تماس بگیرید.';
            } elseif (str_contains($errorMsg, 'amount') || str_contains($errorMsg, 'مبلغ')) {
                $errorMessage = 'خطا در مبلغ پرداخت. لطفا مبلغ را بررسی کنید.';
            } else {
                if (config('app.debug')) {
                    $errorMessage .= ': ' . $errorMsg . ' (خطا در خط ' . $errorLine . ')';
                } else {
                    $errorMessage .= '. لطفا دوباره تلاش کنید.';
                }
            }
            
            $this->dispatch('showToast', ['message' => $errorMessage, 'type' => 'error']);
            return;
        }
    }

    public function render()
    {
        // Ensure amount is calculated
        if ($this->dailyPrice == 0) {
            $this->calculateAmount();
        }
        
        // Calculate new expire date
        $currentExpireAt = $this->ad->expire_at;
        if ($currentExpireAt && $currentExpireAt->isFuture()) {
            $newExpireAt = $currentExpireAt->copy()->addDays($this->days);
        } else {
            $newExpireAt = now()->addDays($this->days);
        }
        
        return view('livewire.panel.ads.extend', [
            'dailyPrice' => $this->dailyPrice,
            'newExpireAt' => $newExpireAt,
        ])->layout('layouts.panel');
    }
}

