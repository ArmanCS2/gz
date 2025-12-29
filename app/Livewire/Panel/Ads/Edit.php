<?php

namespace App\Livewire\Panel\Ads;

use App\Models\Ad;
use App\Models\Category;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Traits\HandlesFileUploads;
use Hekmatinasser\Verta\Verta;

class Edit extends Component
{
    use WithFileUploads, HandlesFileUploads;

    public Ad $ad;
    public $title = '';
    public $category_id = '';
    public $ad_type = 'telegram';
    public $description = '';
    public $telegram_link = '';
    public $telegram_id = '';
    public $member_count = 0;
    public $construction_year = '';
    public $construction_year_calendar = 'solar'; // 'solar' or 'gregorian'
    public $price = '';
    public $base_price = '';
    public $show_contact = true;
    public $images = [];
    public $imagePreviews = [];
    public $existing_images = [];
    public $auction_end_time_jalali = '';
    public $auction_end_time_hour = '';

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

    public function removeExistingImage($imageId)
    {
        $image = $this->ad->images()->findOrFail($imageId);
        $this->deleteFromPublic($image->image);
        $image->delete();
        $this->ad->refresh();
    }

    public function mount($ad)
    {
        $this->ad = $ad instanceof Ad ? $ad : Ad::findOrFail($ad);
        if ($this->ad->user_id !== auth()->id()) {
            abort(403);
        }
        $this->title = $this->ad->title;
        $this->category_id = $this->ad->category_id;
        $this->ad_type = $this->ad->ad_type ?? 'telegram';
        $this->description = $this->ad->description;
        $this->telegram_link = $this->ad->telegram_link;
        $this->telegram_id = $this->ad->telegram_id;
        $this->member_count = $this->ad->member_count;
        
        // Load extra fields from ad_extra
        $extra = $this->ad->ad_extra ?? [];
        if ($this->ad_type === 'instagram') {
            $this->instagram_followers = $extra['instagram_followers'] ?? '';
            $this->instagram_category = $extra['instagram_category'] ?? '';
            $this->instagram_engagement_rate = $extra['instagram_engagement_rate'] ?? '';
            $this->instagram_monetized = $extra['instagram_monetized'] ?? false;
        } elseif ($this->ad_type === 'website') {
            $this->website_url = $extra['website_url'] ?? '';
            $this->website_type = $extra['website_type'] ?? '';
            $this->website_monthly_visits = $extra['website_monthly_visits'] ?? '';
            $this->website_monthly_income = $extra['website_monthly_income'] ?? '';
            $this->website_tech = $extra['website_tech'] ?? '';
        } elseif ($this->ad_type === 'domain') {
            $this->domain_name = $extra['domain_name'] ?? '';
            $this->domain_extension = $extra['domain_extension'] ?? '';
            // Convert Gregorian date to Jalali for display
            if (!empty($extra['domain_expire_date'])) {
                try {
                    $carbonDate = \Carbon\Carbon::parse($extra['domain_expire_date']);
                    $verta = Verta::instance($carbonDate);
                    $this->domain_expire_date = $verta->format('Y/m/d');
                } catch (\Exception $e) {
                    $this->domain_expire_date = $extra['domain_expire_date'];
                }
            } else {
                $this->domain_expire_date = '';
            }
        } elseif ($this->ad_type === 'youtube') {
            $this->youtube_subscribers = $extra['youtube_subscribers'] ?? '';
            $this->youtube_watch_hours = $extra['youtube_watch_hours'] ?? '';
            $this->youtube_monetized = $extra['youtube_monetized'] ?? false;
        }
        
        // Convert construction_year from Gregorian (stored) to Solar for display
        if ($this->ad->construction_year) {
            try {
                $carbonDate = \Carbon\Carbon::create($this->ad->construction_year, 1, 1);
                $verta = Verta::instance($carbonDate);
                $this->construction_year = $verta->year; // Display as Solar year
                $this->construction_year_calendar = 'solar';
            } catch (\Exception $e) {
                // If conversion fails, show as Gregorian
                $this->construction_year = $this->ad->construction_year;
                $this->construction_year_calendar = 'gregorian';
            }
        }
        $this->price = $this->ad->price;
        $this->base_price = $this->ad->base_price;
        $this->show_contact = $this->ad->show_contact;
        $this->existing_images = $this->ad->images;
    }

    public function save()
    {
        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'ad_type' => 'required|in:telegram,instagram,website,domain,youtube',
            'construction_year' => 'nullable|integer|min:1300|max:2100',
            'construction_year_calendar' => 'nullable|in:solar,gregorian',
            'price' => 'nullable|numeric|min:0',
            'base_price' => 'nullable|numeric|min:0',
            'images.*' => 'image|max:2048',
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
        
        $this->validate($rules);

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
                    // Parse Jalali date (format: YYYY/MM/DD)
                    $parts = explode('/', $this->domain_expire_date);
                    if (count($parts) === 3) {
                        $year = (int)$parts[0];
                        $month = (int)$parts[1];
                        $day = (int)$parts[2];
                        $verta = Verta::createJalali($year, $month, $day, 0, 0, 0);
                        $domainExpireDate = $verta->toCarbon()->format('Y-m-d');
                    } else {
                        // If format is not Jalali, try to use as-is (might be already Gregorian)
                        $domainExpireDate = $this->domain_expire_date;
                    }
                } catch (\Exception $e) {
                    // If conversion fails, use as-is
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

        $this->ad->update([
            'title' => $this->title,
            'category_id' => $this->category_id ?: null,
            'ad_type' => $this->ad_type,
            'ad_extra' => !empty($adExtra) ? $adExtra : null,
            'description' => $this->description,
            'telegram_link' => $this->ad_type === 'telegram' ? $this->telegram_link : null,
            'telegram_id' => $this->ad_type === 'telegram' ? ($this->telegram_id ?: null) : null,
            'member_count' => $this->ad_type === 'telegram' ? $this->member_count : 0,
            'construction_year' => $constructionYear,
            'price' => $this->ad->type === 'normal' ? $this->price : null,
            'base_price' => $this->ad->type === 'auction' ? $this->base_price : null,
            'show_contact' => $this->show_contact,
        ]);

        if (!empty($this->images)) {
            foreach ($this->images as $image) {
                $path = $this->uploadToPublic($image, 'ads');
                $this->ad->images()->create(['image' => $path]);
            }
        }

        // Refresh ad and reload all values
        $this->ad->refresh();
        $this->title = $this->ad->title;
        $this->category_id = $this->ad->category_id;
        $this->ad_type = $this->ad->ad_type ?? 'telegram';
        $this->description = $this->ad->description;
        $this->telegram_link = $this->ad->telegram_link;
        $this->telegram_id = $this->ad->telegram_id;
        $this->member_count = $this->ad->member_count;
        
        // Reload extra fields
        $extra = $this->ad->ad_extra ?? [];
        if ($this->ad_type === 'instagram') {
            $this->instagram_followers = $extra['instagram_followers'] ?? '';
            $this->instagram_category = $extra['instagram_category'] ?? '';
            $this->instagram_engagement_rate = $extra['instagram_engagement_rate'] ?? '';
            $this->instagram_monetized = $extra['instagram_monetized'] ?? false;
        } elseif ($this->ad_type === 'website') {
            $this->website_url = $extra['website_url'] ?? '';
            $this->website_type = $extra['website_type'] ?? '';
            $this->website_monthly_visits = $extra['website_monthly_visits'] ?? '';
            $this->website_monthly_income = $extra['website_monthly_income'] ?? '';
            $this->website_tech = $extra['website_tech'] ?? '';
        } elseif ($this->ad_type === 'domain') {
            $this->domain_name = $extra['domain_name'] ?? '';
            $this->domain_extension = $extra['domain_extension'] ?? '';
            // Convert Gregorian date to Jalali for display
            if (!empty($extra['domain_expire_date'])) {
                try {
                    $carbonDate = \Carbon\Carbon::parse($extra['domain_expire_date']);
                    $verta = Verta::instance($carbonDate);
                    $this->domain_expire_date = $verta->format('Y/m/d');
                } catch (\Exception $e) {
                    $this->domain_expire_date = $extra['domain_expire_date'];
                }
            } else {
                $this->domain_expire_date = '';
            }
        } elseif ($this->ad_type === 'youtube') {
            $this->youtube_subscribers = $extra['youtube_subscribers'] ?? '';
            $this->youtube_watch_hours = $extra['youtube_watch_hours'] ?? '';
            $this->youtube_monetized = $extra['youtube_monetized'] ?? false;
        }
        
        // Convert construction_year from Gregorian (stored) to Solar for display
        if ($this->ad->construction_year) {
            try {
                $carbonDate = \Carbon\Carbon::create($this->ad->construction_year, 1, 1);
                $verta = Verta::instance($carbonDate);
                $this->construction_year = $verta->year;
                $this->construction_year_calendar = 'solar';
            } catch (\Exception $e) {
                $this->construction_year = $this->ad->construction_year;
                $this->construction_year_calendar = 'gregorian';
            }
        } else {
            $this->construction_year = '';
            $this->construction_year_calendar = 'solar';
        }
        $this->price = $this->ad->price;
        $this->base_price = $this->ad->base_price;
        $this->show_contact = $this->ad->show_contact;
        $this->existing_images = $this->ad->images;
        $this->reset(['images', 'imagePreviews']);

        $this->dispatch('showToast', ['message' => 'آگهی به‌روزرسانی شد.', 'type' => 'success']);
    }

    public function render()
    {
        $categories = Category::where('is_active', true)->orderBy('order')->orderBy('name')->get();
        return view('livewire.panel.ads.edit', [
            'categories' => $categories,
        ])->layout('layouts.panel');
    }
}

