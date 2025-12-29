<?php

namespace App\Livewire\Admin\Ads;

use App\Models\Ad;
use App\Models\Category;
use App\Models\User;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Traits\HandlesFileUploads;
use Hekmatinasser\Verta\Verta;

class Create extends Component
{
    use WithFileUploads, HandlesFileUploads;

    public $user_id = null;
    public $category_id = '';
    public $title = '';
    public $description = '';
    public $ad_type = 'telegram';
    public $telegram_link = '';
    public $telegram_id = '';
    public $member_count = 0;
    public $construction_year = '';
    public $construction_year_calendar = 'solar'; // 'solar' or 'gregorian'
    public $type = 'normal';
    public $price = '';
    public $base_price = '';
    public $current_bid = '';
    public $auction_end_time = '';
    public $show_contact = true;
    public $is_active = false;
    public $status = 'pending';
    public $expire_at = '';
    public $images = [];
    public $imagePreviews = [];

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

    public function getRules()
    {
        $rules = [
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'ad_type' => 'required|in:telegram,instagram,website,domain,youtube',
            'construction_year' => 'nullable|integer|min:1300|max:2100',
            'construction_year_calendar' => 'nullable|in:solar,gregorian',
            'type' => 'required|in:normal,auction',
            'price' => 'required_if:type,normal|nullable|numeric|min:0|max:99999999999999.99',
            'base_price' => 'required_if:type,auction|nullable|numeric|min:0|max:99999999999999.99',
            'current_bid' => 'nullable|numeric|min:0|max:99999999999999.99',
            'auction_end_time' => 'required_if:type,auction|nullable|date|after:now',
            'status' => 'required|in:pending,active,rejected,expired,sold',
            'images.*' => 'nullable|image|max:2048',
        ];
        
        // Add conditional rules based on ad_type
        if ($this->ad_type === 'telegram') {
            $rules['telegram_link'] = 'nullable|url';
            $rules['telegram_id'] = 'nullable|string|max:255';
            $rules['member_count'] = 'required|integer|min:0';
        } elseif ($this->ad_type === 'instagram') {
            $rules['instagram_followers'] = 'required|integer|min:0';
            $rules['instagram_category'] = 'required|string|max:255';
            $rules['instagram_engagement_rate'] = 'nullable|numeric|min:0|max:100';
        } elseif ($this->ad_type === 'website') {
            $rules['website_url'] = 'required|url';
            $rules['website_type'] = 'required|in:store,blog,service';
            $rules['website_monthly_visits'] = 'required|integer|min:0';
            $rules['website_monthly_income'] = 'nullable|numeric|min:0';
        } elseif ($this->ad_type === 'domain') {
            $rules['domain_name'] = 'required|string|max:255';
            $rules['domain_extension'] = 'required|string|max:10';
            $rules['domain_expire_date'] = 'required|date';
        } elseif ($this->ad_type === 'youtube') {
            $rules['youtube_subscribers'] = 'required|integer|min:0';
            $rules['youtube_watch_hours'] = 'required|integer|min:0';
        }
        
        return $rules;
    }

    public function updatedImages()
    {
        $this->imagePreviews = [];
        foreach ($this->images as $key => $image) {
            $this->imagePreviews[$key] = [
                'file' => $image,
                'preview' => $image->temporaryUrl(),
                'name' => $image->getClientOriginalName(),
            ];
        }
    }

    public function removeNewImage($index)
    {
        unset($this->images[$index]);
        unset($this->imagePreviews[$index]);
        $this->images = array_values($this->images);
        $this->imagePreviews = array_values($this->imagePreviews);
    }

    public function convertJalaliToGregorian($jalaliDate)
    {
        if (empty($jalaliDate)) {
            return null;
        }
        
        try {
            // Check if date is in Jalali format (YYYY/MM/DD)
            if (preg_match('/^\d{4}\/\d{1,2}\/\d{1,2}$/', $jalaliDate)) {
                $parts = explode('/', $jalaliDate);
                $year = (int)$parts[0];
                $month = (int)$parts[1];
                $day = (int)$parts[2];
                $verta = Verta::createJalali($year, $month, $day, 0, 0, 0);
                return $verta->datetime()->format('Y-m-d');
            } else {
                // Already in Gregorian format or invalid
                return $jalaliDate;
            }
        } catch (\Exception $e) {
            return $jalaliDate;
        }
    }

    public function save()
    {
        $this->validate($this->getRules());

        // Convert construction year if needed
        $constructionYear = null;
        if (!empty($this->construction_year)) {
            $year = (int)$this->construction_year;
            if ($this->construction_year_calendar === 'solar') {
                // Convert Solar to Gregorian for storage
                try {
                    $verta = \Hekmatinasser\Verta\Verta::createJalali($year, 1, 1, 0, 0, 0);
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
            $domainExpireDate = $this->convertJalaliToGregorian($this->domain_expire_date);
            
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

        $data = [
            'user_id' => $this->user_id,
            'category_id' => $this->category_id ?: null,
            'title' => $this->title,
            'description' => $this->description,
            'ad_type' => $this->ad_type,
            'ad_extra' => !empty($adExtra) ? $adExtra : null,
            'telegram_link' => $this->ad_type === 'telegram' ? $this->telegram_link : null,
            'telegram_id' => $this->ad_type === 'telegram' ? ($this->telegram_id ?: null) : null,
            'member_count' => $this->ad_type === 'telegram' ? $this->member_count : 0,
            'construction_year' => $constructionYear,
            'type' => $this->type,
            'price' => $this->type === 'normal' ? ($this->price ?: null) : null,
            'base_price' => $this->type === 'auction' ? ($this->base_price ?: null) : null,
            'current_bid' => $this->type === 'auction' ? ($this->current_bid ?: ($this->base_price ?: null)) : null,
            'auction_end_time' => $this->type === 'auction' ? ($this->auction_end_time ?: null) : null,
            'show_contact' => $this->show_contact,
            'status' => $this->status,
            'is_active' => $this->is_active,
            'expire_at' => $this->expire_at ? $this->expire_at : null,
        ];

        $ad = Ad::create($data);

        // آپلود تصاویر جدید
        if (!empty($this->images)) {
            foreach ($this->images as $image) {
                $path = $this->uploadToPublic($image, 'ads');
                $ad->images()->create(['image' => $path]);
            }
        }

        session()->flash('message', 'آگهی با موفقیت ایجاد شد.');
        return $this->redirect(route('admin.ads.index'), navigate: true);
    }

    public function render()
    {
        $users = User::orderBy('name')->get();
        $categories = Category::where('is_active', true)->orderBy('order')->orderBy('name')->get();
        
        return view('livewire.admin.ads.create', [
            'users' => $users,
            'categories' => $categories,
        ])->layout('layouts.admin');
    }
}

