<?php

namespace App\Livewire\Store;

use App\Models\Ad;
use App\Models\Bid;
use Livewire\Component;

class Show extends Component
{
    public Ad $ad;
    public $bidAmount = '';
    public $showBidForm = false;

    public function mount($ad)
    {
        if ($ad instanceof Ad) {
            $this->ad = $ad;
        } else {
            // Try to find by slug first, then fallback to ID for backward compatibility
            $this->ad = Ad::where('slug', $ad)->orWhere('id', $ad)->firstOrFail();
        }
        
        // فقط آگهی‌های تایید شده و فعال قابل مشاهده هستند
        if ($this->ad->status !== 'active' || !$this->ad->is_active) {
            abort(404, 'آگهی مورد نظر یافت نشد.');
        }
        
        // بررسی انقضای آگهی
        $this->ad->checkExpiration();
        
        // بررسی اینکه آگهی منقضی نشده باشد
        if ($this->ad->expire_at && $this->ad->expire_at <= now()) {
            abort(404, 'آگهی مورد نظر منقضی شده است.');
        }
        
        // Load relationships - ensure ALL images are loaded
        $this->ad->load(['images' => function($q) {
            $q->orderBy('id', 'asc'); // Consistent ordering
        }, 'bids.user', 'approvedReviews.user']);
    }

    public function submitBid()
    {
        if (!auth()->check()) {
            $this->dispatch('showToast', ['message' => 'لطفا ابتدا وارد شوید.', 'type' => 'error']);
            return;
        }

        if ($this->ad->type !== 'auction') {
            $this->dispatch('showToast', ['message' => 'این آگهی مزایده نیست.', 'type' => 'error']);
            return;
        }

        if ($this->ad->user_id === auth()->id()) {
            $this->dispatch('showToast', ['message' => 'شما نمی‌توانید برای آگهی خود پیشنهاد دهید.', 'type' => 'error']);
            return;
        }

        if (!$this->ad->isAuctionActive()) {
            $this->dispatch('showToast', ['message' => 'مزایده به پایان رسیده است.', 'type' => 'error']);
            return;
        }

        $this->validate([
            'bidAmount' => 'required|numeric|min:0',
        ], [
            'bidAmount.required' => 'مبلغ پیشنهادی الزامی است.',
            'bidAmount.numeric' => 'مبلغ باید عدد باشد.',
            'bidAmount.min' => 'مبلغ پیشنهادی باید بیشتر از صفر باشد.',
        ]);

        $bid = Bid::create([
            'user_id' => auth()->id(),
            'ad_id' => $this->ad->id,
            'amount' => $this->bidAmount,
        ]);

        // Update current bid
        $this->ad->update(['current_bid' => $this->bidAmount]);
        $this->ad->refresh();
        $this->ad->load('bids.user');

        $this->bidAmount = '';
        $this->showBidForm = false;

        $this->dispatch('showSwal', [
            'title' => 'موفقیت',
            'text' => 'پیشنهاد شما با موفقیت ثبت شد.',
            'icon' => 'success',
            'confirmButtonText' => 'باشه'
        ]);
    }

    /**
     * Determine if ad should be indexed
     * Rules:
     * - INDEX: Active ads with sufficient content (min 50 chars description)
     * - NOINDEX: Expired, deleted, short descriptions, inactive
     */
    private function shouldIndex()
    {
        // Check if ad is deleted (soft deleted)
        if ($this->ad->trashed()) {
            return false;
        }
        
        // Check if ad is active and approved
        if ($this->ad->status !== 'active' || !$this->ad->is_active) {
            return false;
        }
        
        // Check if ad is expired
        if ($this->ad->expire_at && $this->ad->expire_at <= now()) {
            return false;
        }
        
        // Check if description has sufficient content (minimum 50 characters)
        $descriptionLength = mb_strlen(trim($this->ad->description ?? ''));
        if ($descriptionLength < 50) {
            return false;
        }
        
        return true;
    }

    public function render()
    {
        $siteSettings = \App\Models\SiteSetting::getSettings();
        
        // Determine indexing status
        $shouldIndex = $this->shouldIndex();
        $robotsMeta = $shouldIndex ? 'index, follow' : 'noindex, follow';
        
        // Build SEO-friendly title with ad_type and key metric
        $adType = $this->ad->ad_type ?? 'telegram';
        $adTypeLabels = [
            'telegram' => 'گروه تلگرام',
            'instagram' => 'پیج اینستاگرام',
            'website' => 'سایت آماده',
            'domain' => 'دامنه',
            'youtube' => 'کانال یوتیوب',
        ];
        
        $keyMetric = $this->ad->key_metric;
        $metricText = '';
        
        if ($adType === 'instagram' && $keyMetric) {
            $metricText = number_format($keyMetric) . 'k فالوور';
        } elseif ($adType === 'website' && $keyMetric) {
            $metricText = number_format($keyMetric) . ' بازدید ماهانه';
        } elseif ($adType === 'youtube' && $keyMetric) {
            $metricText = number_format($keyMetric) . ' مشترک';
        } elseif ($adType === 'domain' && $keyMetric) {
            $metricText = $keyMetric;
        } elseif ($adType === 'telegram' && $keyMetric) {
            $metricText = number_format($keyMetric) . ' عضو';
        }
        
        // Build SEO-optimized title for CTR: "خرید گروه تلگرام 12 هزار عضو | قیمت واقعی | گروه باز"
        $titleParts = ['خرید'];
        $titleParts[] = $adTypeLabels[$adType] ?? 'آگهی';
        if ($metricText) {
            $titleParts[] = $metricText;
        }
        $titleParts[] = $this->ad->title;
        if ($this->ad->price) {
            $titleParts[] = '| قیمت ' . number_format($this->ad->price) . ' تومان';
        }
        $titleParts[] = '| ' . ($siteSettings->site_name ?? 'گروه باز');
        
        $pageTitle = implode(' ', $titleParts);
        
        // Build SEO-friendly description optimized for CTR
        $descriptionParts = [];
        $descriptionParts[] = 'خرید ' . ($adTypeLabels[$adType] ?? 'آگهی');
        if ($metricText) {
            $descriptionParts[] = 'با ' . $metricText;
        }
        if ($this->ad->price) {
            $descriptionParts[] = 'قیمت ' . number_format($this->ad->price) . ' تومان';
        }
        $descriptionParts[] = '| آگهی واقعی و مستقیم از مالک';
        $descriptionParts[] = '| بدون واسطه';
        $descriptionParts[] = \Illuminate\Support\Str::limit($this->ad->description, 80);
        
        $pageDescription = implode(' - ', $descriptionParts);
        
        // Use SINGLE SOURCE OF TRUTH
        $pageImage = $this->ad->cover_image ?? ($siteSettings->logo ? asset($siteSettings->logo) : asset('favicon.ico'));
        $canonicalUrl = route('store.show', $this->ad->slug);
        
        return view('livewire.store.show', [
            'pageTitle' => $pageTitle,
            'pageDescription' => $pageDescription,
            'pageImage' => $pageImage,
            'canonicalUrl' => $canonicalUrl,
            'shouldIndex' => $shouldIndex,
            'robotsMeta' => $robotsMeta,
        ])->layout('layouts.app', [
            'title' => $pageTitle,
            'description' => $pageDescription,
            'canonical' => $canonicalUrl,
            'robots' => $robotsMeta,
            'og_type' => 'product',
            'og_url' => $canonicalUrl,
            'og_title' => $pageTitle,
            'og_description' => $pageDescription,
            'og_image' => $pageImage,
            'twitter_url' => $canonicalUrl,
            'twitter_title' => $pageTitle,
            'twitter_description' => $pageDescription,
            'twitter_image' => $pageImage,
        ]);
    }
}

