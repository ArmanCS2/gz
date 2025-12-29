<?php

namespace App\Livewire;

use App\Models\Ad;
use App\Models\SiteRule;
use App\Models\SiteSetting;
use App\Models\Payment;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Traits\HandlesFileUploads;
use Illuminate\Support\Facades\Log;
use Hekmatinasser\Verta\Verta;
use Shetabit\Payment\Facade\Payment as PaymentFacade;
use Shetabit\Multipay\Invoice;
use Shetabit\Multipay\Exceptions\PurchaseFailedException;
use Illuminate\Validation\ValidationException;

class CreateAdWizard extends Component
{
    use WithFileUploads, HandlesFileUploads;

    // Step management
    public $currentStep = 1;
    public $totalSteps = 5;

    // Step 1: Basic Information
    public $title = '';
    public $category = '';
    public $type = 'normal';
    public $ad_type = 'telegram';
    public $description = '';
    public $telegram_link = '';
    public $telegram_id = '';
    public $member_count = 0;
    public $construction_year = '';
    public $construction_year_calendar = 'solar'; // 'solar' or 'gregorian'

    // Extra fields for different ad types
    // Instagram
    public $instagram_followers = '';
    public $instagram_category = '';
    public $instagram_engagement_rate = '';
    public $instagram_monetized = false;
    
    // Website
    public $website_url = '';
    public $website_type = '';
    public $website_monthly_visits = '';
    public $website_monthly_income = '';
    public $website_tech = '';
    
    // Domain
    public $domain_name = '';
    public $domain_extension = '';
    public $domain_expire_date = '';
    
    // YouTube
    public $youtube_subscribers = '';
    public $youtube_watch_hours = '';
    public $youtube_monetized = false;

    // Step 2: Pricing
    public $price = '';
    public $base_price = '';

    // Step 3: Contact & Details
    public $show_contact = true;

    // Step 4: Images
    public $images = [];
    public $imagePreviews = [];

    // Step 5: Payment
    public $payment_days = 30;
    public $payment_amount = 0;
    public $pending_ad_id = null;

    // Rules
    public $rules_accepted_in_session = false;
    public $show_rules_modal = false;

    protected $rules = [
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'ad_type' => 'required|in:telegram,instagram,website,domain,youtube',
        'telegram_link' => 'nullable|url',
        'telegram_id' => 'nullable|string|max:255',
        'member_count' => 'required_if:ad_type,telegram|nullable|integer|min:0',
        'construction_year' => 'nullable|integer|min:1300|max:2100',
        'construction_year_calendar' => 'nullable|in:solar,gregorian',
        'type' => 'required|in:normal,auction',
        'price' => 'required_if:type,normal|nullable|numeric|min:0|max:99999999999999.99',
        'base_price' => 'required_if:type,auction|nullable|numeric|min:0|max:99999999999999.99',
        'images.*' => 'image|max:2048',
        'payment_days' => 'nullable|integer|min:1|max:365',
        // Instagram fields
        'instagram_followers' => 'required_if:ad_type,instagram|nullable|integer|min:0',
        'instagram_category' => 'required_if:ad_type,instagram|nullable|string|max:255',
        'instagram_engagement_rate' => 'nullable|numeric|min:0|max:100',
        'instagram_monetized' => 'nullable|boolean',
        // Website fields
        'website_url' => 'required_if:ad_type,website|nullable|url',
        'website_type' => 'required_if:ad_type,website|nullable|in:store,blog,service',
        'website_monthly_visits' => 'required_if:ad_type,website|nullable|integer|min:0',
        'website_monthly_income' => 'nullable|numeric|min:0',
        'website_tech' => 'nullable|string|max:255',
        // Domain fields
        'domain_name' => 'required_if:ad_type,domain|nullable|string|max:255',
        'domain_extension' => 'required_if:ad_type,domain|nullable|string|max:10',
        'domain_expire_date' => 'required_if:ad_type,domain|nullable|date',
        // YouTube fields
        'youtube_subscribers' => 'required_if:ad_type,youtube|nullable|integer|min:0',
        'youtube_watch_hours' => 'required_if:ad_type,youtube|nullable|integer|min:0',
        'youtube_monetized' => 'nullable|boolean',
    ];

    protected $messages = [
        'title.required' => 'عنوان آگهی الزامی است.',
        'description.required' => 'توضیحات الزامی است.',
        'telegram_link.url' => 'لینک تلگرام باید معتبر باشد.',
        'price.required_if' => 'قیمت الزامی است.',
        'base_price.required_if' => 'قیمت پایه مزایده الزامی است.',
    ];

    public function mount()
    {
        $user = auth()->user();
        
        if (!$user) {
            return redirect()->route('home');
        }
        
        // Try to restore pending_ad_id from session if component was reset
        if (!$this->pending_ad_id && session('pending_ad_id')) {
            $this->pending_ad_id = session('pending_ad_id');
            Log::info('Restored pending_ad_id from session in mount', ['ad_id' => $this->pending_ad_id]);
        }
        
        // Rules will be checked when user tries to save the ad
    }

    public function updatedAdType()
    {
        // Reset extra fields when ad_type changes
        $this->instagram_followers = '';
        $this->instagram_category = '';
        $this->instagram_engagement_rate = '';
        $this->instagram_monetized = false;
        $this->website_url = '';
        $this->website_type = '';
        $this->website_monthly_visits = '';
        $this->website_monthly_income = '';
        $this->website_tech = '';
        $this->domain_name = '';
        $this->domain_extension = '';
        $this->domain_expire_date = '';
        $this->youtube_subscribers = '';
        $this->youtube_watch_hours = '';
        $this->youtube_monetized = false;
        
        // Dispatch event to reinitialize jalali datepicker
        $this->dispatch('ad-type-changed');
    }

    public function nextStep()
    {
        if ($this->validateStep()) {
            if ($this->currentStep < $this->totalSteps) {
                $this->currentStep++;
                $this->resetErrorBag();
            }
        }
    }

    public function previousStep()
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }

    public function goToStep($step)
    {
        // Only allow going to completed steps or current step
        if ($step >= 1 && $step <= $this->totalSteps) {
            // Allow going back or to current step
            if ($step <= $this->currentStep) {
                $this->currentStep = $step;
            } elseif ($step === $this->currentStep + 1 && $this->validateStep()) {
                $this->currentStep = $step;
            }
        }
    }

    protected function validateStep()
    {
        try {
            switch ($this->currentStep) {
                case 1:
                    $rules = [
                        'title' => 'required|string|max:255',
                        'description' => 'required|string',
                        'ad_type' => 'required|in:telegram,instagram,website,domain,youtube',
                    ];
                    
                    // Add conditional rules based on ad_type
                    if ($this->ad_type === 'telegram') {
                        $rules['telegram_link'] = 'nullable|url';
                        $rules['member_count'] = 'required|integer|min:0';
                    } elseif ($this->ad_type === 'instagram') {
                        $rules['instagram_followers'] = 'required|integer|min:0';
                        $rules['instagram_category'] = 'required|string|max:255';
                    } elseif ($this->ad_type === 'website') {
                        $rules['website_url'] = 'required|url';
                        $rules['website_type'] = 'required|in:store,blog,service';
                        $rules['website_monthly_visits'] = 'required|integer|min:0';
                    } elseif ($this->ad_type === 'domain') {
                        $rules['domain_name'] = 'required|string|max:255';
                        $rules['domain_extension'] = 'required|string|max:10';
                        $rules['domain_expire_date'] = 'required|date';
                    } elseif ($this->ad_type === 'youtube') {
                        $rules['youtube_subscribers'] = 'required|integer|min:0';
                        $rules['youtube_watch_hours'] = 'required|integer|min:0';
                    }
                    
                    $rules['construction_year'] = 'nullable|integer|min:1300|max:2100';
                    
                    $this->validate($rules, $this->messages);
                    return true;
                case 2:
                    if ($this->type === 'normal') {
                        $this->validate([
                            'price' => 'required|numeric|min:0'
                        ], $this->messages);
                    } else {
                        $this->validate([
                            'base_price' => 'required|numeric|min:0',
                        ], $this->messages);
                    }
                    return true;
                case 3:
                    return true; // No validation needed for contact visibility
                case 4:
                    return true; // Images are optional
                case 5:
                    return true; // Payment step - no validation needed
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Validation failed, errors are already set
            $this->dispatch('showToast', ['message' => 'لطفا تمام فیلدهای الزامی را پر کنید.', 'type' => 'error']);
            return false;
        }
        return false;
    }

    public function updatedImages()
    {
        $this->imagePreviews = [];
        if (!empty($this->images)) {
            foreach ($this->images as $key => $image) {
                try {
                    $this->imagePreviews[$key] = [
                        'file' => $image,
                        'preview' => $image->temporaryUrl(),
                        'name' => $image->getClientOriginalName(),
                    ];
                } catch (\Exception $e) {
                    // Handle error
                }
            }
        }
        $this->dispatch('images-updated');
    }

    public function removeImage($index)
    {
        unset($this->images[$index]);
        unset($this->imagePreviews[$index]);
        $this->images = array_values($this->images);
        $this->imagePreviews = array_values($this->imagePreviews);
    }

    public function acceptRules()
    {
        // قبول قوانین فقط برای همین session ذخیره می‌شود
        session(['rules_accepted_for_ad_creation' => true]);
        
        $this->rules_accepted_in_session = true;
        $this->show_rules_modal = false;
        $this->dispatch('showToast', ['message' => 'قوانین پذیرفته شد.', 'type' => 'success']);
    }

    public function convertJalaliToGregorian($jalaliDate, $hour = '00:00')
    {
        try {
            if (empty($jalaliDate)) {
                return null;
            }
            
            $parts = explode('/', $jalaliDate);
            if (count($parts) !== 3) {
                return null;
            }
            
            $year = (int)$parts[0];
            $month = (int)$parts[1];
            $day = (int)$parts[2];
            
            $hourParts = explode(':', $hour);
            $hourValue = isset($hourParts[0]) ? (int)$hourParts[0] : 0;
            $minuteValue = isset($hourParts[1]) ? (int)$hourParts[1] : 0;
            
            $verta = Verta::createJalali($year, $month, $day, $hourValue, $minuteValue, 0);
            return $verta->datetime();
        } catch (\Exception $e) {
            return null;
        }
    }

    public function save()
    {
        // Validate only the fields that are needed for ad creation
        // Don't validate payment_days here as it's for payment step
        $this->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'telegram_link' => 'nullable|url',
            'telegram_id' => 'nullable|string|max:255',
            'member_count' => 'required|integer|min:0',
            'construction_year' => 'nullable|integer|min:1300|max:2100',
            'construction_year_calendar' => 'nullable|in:solar,gregorian',
            'type' => 'required|in:normal,auction',
            'price' => 'required_if:type,normal|nullable|numeric|min:0|max:99999999999999.99',
            'base_price' => 'required_if:type,auction|nullable|numeric|min:0|max:99999999999999.99',
            'images.*' => 'image|max:2048',
        ], $this->messages);

        // Check if user has accepted rules in this session
        // هر بار ثبت آگهی باید قوانین را دوباره بپذیرد
        if (!session('rules_accepted_for_ad_creation') && !$this->rules_accepted_in_session) {
            $this->show_rules_modal = true;
            $this->dispatch('showToast', ['message' => 'لطفا قوانین را مطالعه کرده و بپذیرید.', 'type' => 'info']);
            return;
        }
        
        $settings = SiteSetting::getSettings();
        
        // Convert construction year if needed
        $constructionYear = null;
        if (!empty($this->construction_year)) {
            $year = (int)$this->construction_year;
            if ($this->construction_year_calendar === 'solar') {
                // Convert Solar to Gregorian for storage
                try {
                    $verta = Verta::createJalali($year, 1, 1, 0, 0, 0);
                    $gregorianDate = $verta->toCarbon();
                    $constructionYear = $gregorianDate->year; // Store as Gregorian year
                } catch (\Exception $e) {
                    // If conversion fails, use approximate conversion
                    $constructionYear = $year + 621; // Approximate conversion
                }
            } else {
                // Already Gregorian, store as-is
                $constructionYear = $year;
            }
        }
        
        // Build ad_extra based on ad_type
        $adExtra = [];
        if ($this->ad_type === 'instagram') {
            $adExtra = [
                'instagram_followers' => (int)$this->instagram_followers,
                'instagram_category' => $this->instagram_category,
                'instagram_engagement_rate' => $this->instagram_engagement_rate ? (float)$this->instagram_engagement_rate : null,
                'instagram_monetized' => (bool)$this->instagram_monetized,
            ];
        } elseif ($this->ad_type === 'website') {
            $adExtra = [
                'website_url' => $this->website_url,
                'website_type' => $this->website_type,
                'website_monthly_visits' => (int)$this->website_monthly_visits,
                'website_monthly_income' => $this->website_monthly_income ? (float)$this->website_monthly_income : null,
                'website_tech' => $this->website_tech,
            ];
        } elseif ($this->ad_type === 'domain') {
            // Convert Jalali date to Gregorian for storage
            $domainExpireDate = null;
            if (!empty($this->domain_expire_date)) {
                try {
                    // Check if date is in Jalali format (YYYY/MM/DD)
                    if (preg_match('/^\d{4}\/\d{1,2}\/\d{1,2}$/', $this->domain_expire_date)) {
                        $parts = explode('/', $this->domain_expire_date);
                        $year = (int)$parts[0];
                        $month = (int)$parts[1];
                        $day = (int)$parts[2];
                        $verta = Verta::createJalali($year, $month, $day, 0, 0, 0);
                        $domainExpireDate = $verta->datetime()->format('Y-m-d');
                    } else {
                        // Already in Gregorian format or invalid
                        $domainExpireDate = $this->domain_expire_date;
                    }
                } catch (\Exception $e) {
                    $domainExpireDate = $this->domain_expire_date;
                }
            }
            
            $adExtra = [
                'domain_name' => $this->domain_name,
                'domain_extension' => $this->domain_extension,
                'domain_expire_date' => $domainExpireDate,
            ];
        } elseif ($this->ad_type === 'youtube') {
            $adExtra = [
                'youtube_subscribers' => (int)$this->youtube_subscribers,
                'youtube_watch_hours' => (int)$this->youtube_watch_hours,
                'youtube_monetized' => (bool)$this->youtube_monetized,
            ];
        }

        $ad = Ad::create([
            'user_id' => auth()->id(),
            'title' => $this->title,
            'description' => $this->description,
            'ad_type' => $this->ad_type,
            'ad_extra' => !empty($adExtra) ? $adExtra : null,
            'telegram_link' => $this->ad_type === 'telegram' ? $this->telegram_link : null,
            'telegram_id' => $this->ad_type === 'telegram' ? ($this->telegram_id ?: null) : null,
            'member_count' => $this->ad_type === 'telegram' ? (int)$this->member_count : 0,
            'construction_year' => $constructionYear,
            'type' => $this->type,
            'price' => $this->type === 'normal' ? (float)$this->price : null,
            'base_price' => $this->type === 'auction' ? (float)$this->base_price : null,
            'current_bid' => $this->type === 'auction' ? (float)$this->base_price : null,
            'auction_end_time' => null, // مزایده بدون تاریخ پایان
            'show_contact' => $this->show_contact,
            // بررسی تنظیمات: اگر خودکار تایید فعال باشد، آگهی active می‌شود، در غیر این صورت pending و نیاز به تایید ادمین دارد
            'status' => (bool) $settings->ad_auto_approve ? 'active' : 'pending',
            'is_active' => (bool) $settings->ad_auto_approve,
        ]);
        
        // بعد از ثبت موفق آگهی، session را پاک می‌کنیم تا دفعه بعد دوباره باید قوانین را بپذیرد
        session()->forget('rules_accepted_for_ad_creation');

        if (!empty($this->images)) {
            foreach ($this->images as $image) {
                $path = $this->uploadToPublic($image, 'ads');
                $ad->images()->create(['image' => $path]);
            }
        }

        // Store ad ID in component property (more reliable than session in Livewire)
        $this->pending_ad_id = $ad->id;
        $this->payment_days = $this->payment_days ?? 30;
        
        // Also store in session as backup
        session()->put('pending_ad_id', $ad->id);
        session()->put('pending_payment_days', $this->payment_days);
        
        // Move to payment step
        $this->currentStep = 5;
        
        Log::info('Ad created, moving to payment step', [
            'ad_id' => $ad->id,
            'payment_days' => $this->payment_days,
            'pending_ad_id' => $this->pending_ad_id
        ]);
    }

    public function proceedToPayment()
    {
        Log::info('proceedToPayment called', [
            'component_pending_ad_id' => $this->pending_ad_id,
            'session_pending_ad_id' => session('pending_ad_id'),
            'payment_days' => $this->payment_days,
            'current_step' => $this->currentStep
        ]);
        
        // Try to get ad ID from component property first, then session, then database
        $adId = $this->pending_ad_id ?? session('pending_ad_id');
        
        // If still not found, try to get the last ad created by this user (within last 10 minutes)
        if (!$adId) {
            $lastAd = Ad::where('user_id', auth()->id())
                ->where('is_active', false)
                ->whereNull('paid_at')
                ->where('created_at', '>=', now()->subMinutes(10))
                ->latest()
                ->first();
            
            if ($lastAd) {
                $adId = $lastAd->id;
                $this->pending_ad_id = $adId;
                session()->put('pending_ad_id', $adId);
                Log::info('Found last unpaid ad from database', ['ad_id' => $adId]);
            }
        }
        
        if (!$adId) {
            Log::error('No ad ID found for payment', [
                'user_id' => auth()->id(),
                'component_ad_id' => $this->pending_ad_id,
                'session_ad_id' => session('pending_ad_id'),
                'all_unpaid_ads' => Ad::where('user_id', auth()->id())
                    ->where('is_active', false)
                    ->whereNull('paid_at')
                    ->latest()
                    ->get()
                    ->pluck('id')
                    ->toArray()
            ]);
            $this->dispatch('showToast', ['message' => 'خطا در پردازش پرداخت. لطفا دوباره آگهی را ثبت کنید.', 'type' => 'error']);
            return $this->redirect(route('create-ad'), navigate: true);
        }
        
        Log::info('Proceeding to payment', [
            'ad_id' => $adId,
            'payment_days' => $this->payment_days
        ]);

        $ad = Ad::findOrFail($adId);
        $settings = SiteSetting::getSettings();
        
        // Get days from component property, ensure it's valid
        $days = (int)($this->payment_days ?? 30);
        
        // Ensure days is valid
        if ($days < 1 || $days > 365) {
            $this->addError('payment_days', 'تعداد روزها باید بین 1 تا 365 باشد.');
            $this->dispatch('showToast', ['message' => 'تعداد روزها باید بین 1 تا 365 باشد.', 'type' => 'error']);
            return;
        }
        
        $dailyPrice = $ad->type === 'auction' ? $settings->auction_daily_price : $settings->ad_daily_price;
        $amount = $dailyPrice * $days;

        // Ensure amount is valid
        if ($amount <= 0 || $amount < 1000) {
            $this->dispatch('showToast', ['message' => 'مبلغ پرداخت معتبر نیست. حداقل مبلغ 1000 تومان است.', 'type' => 'error']);
            return;
        }

        // Create payment record
        try {
            $payment = Payment::create([
                'user_id' => auth()->id(),
                'ad_id' => $ad->id,
                'amount' => $amount,
                'days' => $days,
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
            // This is the official test merchant ID provided by Zarinpal for sandbox testing
            $merchantId = '71c705f8-bd37-11e6-aa0c-000c295eb8fc';
            $mode = 'sandbox'; // Use sandbox if merchant ID is not set
        }
        
        config(['payment.drivers.zarinpal.merchantId' => $merchantId]);
        config(['payment.drivers.zarinpal.mode' => $mode]);
        config(['payment.drivers.zarinpal.callbackUrl' => route('payment.verify', $payment)]);
        
        Log::info('Payment Gateway Config', [
            'merchant_id' => $merchantId,
            'mode' => $mode,
            'amount' => $amount,
            'days' => $days,
            'ad_id' => $ad->id,
            'callback_url' => route('payment.verify', $payment)
        ]);

        try {
            // Ensure amount is at least 1000 toman (minimum for Zarinpal)
            if ($amount < 1000) {
                $this->dispatch('showToast', ['message' => 'حداقل مبلغ پرداخت 1000 تومان است.', 'type' => 'error']);
                $payment->update(['status' => 'failed']);
                return;
            }

            // Convert amount to integer (Zarinpal expects integer in Toman)
            $amountInt = (int)round($amount);
            
            // Ensure amount is valid
            if ($amountInt < 1000) {
                $this->dispatch('showToast', ['message' => 'حداقل مبلغ پرداخت 1000 تومان است.', 'type' => 'error']);
                $payment->update(['status' => 'failed']);
                return;
            }
            
            $invoice = (new Invoice)->amount($amountInt);
            
            // Don't clear session here - let it be cleared after successful payment verification
            // session()->forget(['pending_ad_id', 'pending_payment_days']);
            
            // Purchase and redirect to payment gateway
            Log::info('Attempting payment purchase', [
                'amount' => $amountInt,
                'merchant_id' => $merchantId ?: 'empty',
                'mode' => $mode,
                'callback_url' => route('payment.verify', $payment)
            ]);
            
            // Get the redirection form from payment gateway
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
            
            // Extract URL from redirection form and redirect in Livewire
            $paymentUrl = $redirectionForm->getAction();
            
            Log::info('Redirecting to payment gateway', [
                'payment_url' => $paymentUrl,
                'payment_id' => $payment->id
            ]);
            
            // In Livewire, we need to use redirect()->to() instead of returning RedirectionForm
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
                'amount' => $amount ?? null,
                'days' => $days ?? null,
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
                // Show detailed error in development, generic in production
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
        $rules = SiteRule::where('is_active', true)->get();
        
        // Calculate payment amount if on payment step
        if ($this->currentStep === 5) {
            $settings = SiteSetting::getSettings();
            $dailyPrice = $this->type === 'auction' ? $settings->auction_daily_price : $settings->ad_daily_price;
            $this->payment_amount = $dailyPrice * $this->payment_days;
        }
        
        // Create preview ad object
        $previewAd = (object)[
            'id' => 0,
            'title' => $this->title ?: 'عنوان آگهی شما',
            'description' => $this->description,
            'member_count' => $this->member_count ?: 0,
            'type' => $this->type,
            'price' => $this->type === 'normal' ? ($this->price ?: 0) : null,
            'base_price' => $this->type === 'auction' ? ($this->base_price ?: 0) : null,
            'current_bid' => $this->type === 'auction' ? ($this->base_price ?: 0) : null,
            'auction_end_time' => null,
            'images' => collect($this->imagePreviews)->map(function($preview) {
                return (object)['image' => $preview['preview'] ?? null];
            }),
            'user' => (object)['verified' => false],
            'bids' => collect([]),
        ];

        return view('livewire.create-ad-wizard', [
            'rules' => $rules,
            'previewAd' => $previewAd,
        ])->layout('layouts.app');
    }
}
