<?php

namespace App\Livewire\Auth;

use App\Models\OtpLog;
use App\Models\User;
use App\Models\SiteSetting;
use App\Http\Services\Message\SMS\MeliPayamakService;
use Livewire\Component;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class Login extends Component
{
    public $mobile = '';
    public $otp = ['', '', '', '']; // آرایه ۴ رقمی
    public $step = 'mobile';

    protected $rules = [
        'mobile' => 'required|digits:11|regex:/^09\d{9}$/',
        'otp.*'  => 'nullable|digits:1'
    ];

    public function sendOtp()
    {
        $this->validate(['mobile' => 'required|digits:11|regex:/^09\d{9}$/']);

        // Rate limiting: حداکثر 3 درخواست در هر 10 دقیقه
        $key = 'otp_send_' . $this->mobile;
        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);
            $this->addError('mobile', "تعداد درخواست‌های شما بیش از حد مجاز است. لطفا {$seconds} ثانیه دیگر تلاش کنید.");
            return;
        }

        // حالت تست
        if ($this->mobile === '09123456789') {
            $this->step = 'verify';
            $this->dispatch('otpSent');
            RateLimiter::hit($key, 600); // 10 minutes
            return;
        }

        // تولید کد OTP
        $otp = rand(1000, 9999);

        // ذخیره OTP در دیتابیس
        $otpLog = OtpLog::create([
            'mobile' => $this->mobile,
            'code' => $otp,
            'expires_at' => now()->addMinutes(5),
            'is_used' => false,
            'ip' => request()->ip(),
        ]);

        // ارسال پیامک از طریق MeliPayamak
        try {
            $settings = SiteSetting::getSettings();
            $smsService = new MeliPayamakService();
            
            // شماره خط ارسال کننده از تنظیمات یا مقدار پیش‌فرض
            $fromNumber = $settings->melipayamak_from_number ?? '50004001';
            
            // متن پیامک
            $message = "کد تایید شما: {$otp}\n\nگروه باز - GroohBaz";
            
            // ارسال پیامک
            $result = $smsService->send($fromNumber, $this->mobile, $message, false);
            
            if ($result) {
                Log::info('OTP SMS sent successfully', [
                    'mobile' => $this->mobile,
                    'otp_log_id' => $otpLog->id,
                    'rec_id' => $result
                ]);
                
                $this->step = 'verify';
                $this->dispatch('otpSent');
                RateLimiter::hit($key, 600); // 10 minutes
            } else {
                Log::error('OTP SMS sending failed', [
                    'mobile' => $this->mobile,
                    'otp_log_id' => $otpLog->id
                ]);
                
                // حذف OTP در صورت عدم ارسال موفق
                $otpLog->delete();
                
                $this->addError('mobile', 'خطا در ارسال پیامک. لطفا دوباره تلاش کنید.');
            }
        } catch (\Exception $e) {
            Log::error('OTP sending exception', [
                'mobile' => $this->mobile,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // حذف OTP در صورت خطا
            if (isset($otpLog)) {
                $otpLog->delete();
            }
            
            $this->addError('mobile', 'خطا در ارسال پیامک. لطفا دوباره تلاش کنید.');
        }
    }

    public function verifyOtp()
    {
        // Validate that all OTP digits are filled
        $otp_code = implode('', $this->otp);
        
        if (strlen($otp_code) !== 4) {
            $this->addError('otp', 'لطفا کد ۴ رقمی را کامل وارد کنید.');
            return;
        }

        // حالت تست
        if ($this->mobile === '09123456789' && $otp_code === '1234') {
            return $this->loginUser();
        }

        // حالت واقعی
        $otp = OtpLog::where('mobile', $this->mobile)
            ->where('code', $otp_code)
            ->where('is_used', false)
            ->where('expires_at', '>', now())
            ->latest()
            ->first();

        if (!$otp) {
            $this->addError('otp', 'کد تایید اشتباه یا منقضی شده است.');
            return;
        }

        $otp->update(['is_used' => true]);

        return $this->loginUser();
    }

    private function loginUser()
    {
        $user = User::firstOrCreate(
            ['mobile' => $this->mobile],
            ['name' => 'کاربر ' . substr($this->mobile, -4), 'is_verified' => true]
        );

        if (!$user->is_verified) {
            $user->update(['is_verified' => true]);
        }

        auth()->login($user);

        // Regenerate session to prevent CSRF token issues
        Session::regenerate();

        $this->resetForm();

        // Store success message in session to show after page refresh
        Session::flash('login_success', true);
        
        // Close modal via JavaScript after redirect
        $this->js('
            setTimeout(() => {
                const modalElement = document.getElementById("loginModal");
                if (modalElement) {
                    const modal = bootstrap.Modal.getInstance(modalElement);
                    if (modal) {
                        modal.hide();
                    }
                }
            }, 100);
        ');
        
        // Refresh the page to update CSRF token and show SweetAlert
        // Use standard redirect to avoid CSRF issues
        return redirect()->to(request()->header('Referer') ?: route('home'));
    }

    public function resetForm()
    {
        $this->mobile = '';
        $this->otp = ['', '', '', ''];
        $this->step = 'mobile';
        $this->resetErrorBag();
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
