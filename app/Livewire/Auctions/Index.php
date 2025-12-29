<?php

namespace App\Livewire\Auctions;

use App\Models\Ad;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $min_members = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        // فقط آگهی‌هایی که منقضی نشده‌اند نمایش داده می‌شوند
        $query = Ad::select('id', 'title', 'base_price', 'current_bid', 'member_count', 'type', 'auction_end_time', 'created_at', 'user_id', 'price', 'telegram_link', 'description')
            ->where('status', 'active')
            ->where('is_active', true)
            ->where(function($q) {
                // آگهی‌هایی که expire_at ندارند یا expire_at آنها در آینده است
                $q->whereNull('expire_at')
                  ->orWhere('expire_at', '>', now());
            })
            ->where('type', 'auction'); // فقط مزایده‌ها

        if ($this->search) {
            $query->where(function($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->min_members) {
            $query->where('member_count', '>=', $this->min_members);
        }

        $ads = $query->with(['images' => function($q) { 
                $q->orderBy('id', 'asc'); // Load all images, ordered consistently
            }, 'user:id,is_verified', 'bids' => function($q) {
                $q->select('id', 'ad_id')->limit(1);
            }])
            ->latest()
            ->paginate(12);
        
        $ads->setPath('/auctions');
        
        // SEO Rules: Auctions page with filters = NOINDEX, FOLLOW
        // Clean /auctions page (no filters) = INDEX, FOLLOW
        $hasFilters = !empty($this->search) || !empty($this->min_members);
        $robotsMeta = $hasFilters ? 'noindex, follow' : 'index, follow';
        $canonicalUrl = $hasFilters ? route('auctions.index') : url()->current();
        
        $siteSettings = \App\Models\SiteSetting::getSettings();

        return view('livewire.auctions.index', [
            'ads' => $ads,
            'hasFilters' => $hasFilters,
        ])->layout('layouts.app', [
            'title' => $hasFilters ? 'جستجوی مزایده‌ها | ' . ($siteSettings->site_name ?? 'گروه باز') : 'مزایده‌ها | ' . ($siteSettings->site_name ?? 'گروه باز'),
            'description' => $hasFilters ? 'نتایج جستجو در مزایده‌ها' : 'مزایده گروه‌های تلگرام، کانال‌های تلگرام و پیج‌های اینستاگرام',
            'canonical' => $canonicalUrl,
            'robots' => $robotsMeta,
        ]);
    }
}

