<?php

namespace App\Livewire;

use App\Models\Ad;
use App\Models\Category;
use App\Models\SiteSetting;
use Livewire\Component;
use Livewire\WithPagination;

class Home extends Component
{
    use WithPagination;

    public $searchQuery = '';
    public $category = '';
    public $listingType = 'all';
    public $minMembers = 0;
    public $maxMembers = null;
    public $minPrice = 0;
    public $maxPrice = 0;

    public function resetFilters()
    {
        $this->searchQuery = '';
        $this->category = '';
        $this->listingType = 'all';
        $this->minMembers = 0;
        $this->maxMembers = null;
        $this->minPrice = 0;
        $this->maxPrice = 0;
        $this->resetPage();
        $this->dispatch('filters-reset');
    }

    public function search()
    {
        // Search functionality can be implemented here
        $this->resetPage();
    }

    public function updatedSearchQuery()
    {
        $this->resetPage();
    }

    public function updatedCategory()
    {
        $this->resetPage();
    }

    public function updatedListingType()
    {
        $this->resetPage();
    }

    public function render()
    {
        // Get all active ads (both normal and auction) together
        // فقط آگهی‌هایی که منقضی نشده‌اند نمایش داده می‌شوند
        $query = Ad::where('status', 'active')
            ->where('is_active', true)
            ->where(function($query) {
                // آگهی‌هایی که expire_at ندارند یا expire_at آنها در آینده است
                $query->whereNull('expire_at')
                      ->orWhere('expire_at', '>', now());
            });

        // Apply filters
        if ($this->searchQuery) {
            $query->where(function($q) {
                $q->where('title', 'like', '%' . $this->searchQuery . '%')
                  ->orWhere('description', 'like', '%' . $this->searchQuery . '%');
            });
        }

        if ($this->category) {
            $query->where('category_id', $this->category);
        }

        if ($this->listingType === 'auction') {
            $query->where('type', 'auction');
        } elseif ($this->listingType === 'normal') {
            $query->where('type', 'normal');
        }

        if ($this->minMembers > 0) {
            $query->where('member_count', '>=', $this->minMembers);
        }

        if ($this->maxMembers && $this->maxMembers > 0) {
            $query->where('member_count', '<=', $this->maxMembers);
        }

        // Only apply price filter if user changed it from default
        if ($this->minPrice > 0) {
            $query->where(function($q) {
                $q->where(function($normalPrice) {
                    $normalPrice->where('type', 'normal')
                                ->where('price', '>=', $this->minPrice);
                })->orWhere(function($auctionPrice) {
                    $auctionPrice->where('type', 'auction')
                                 ->where(function($p) {
                                     $p->where('base_price', '>=', $this->minPrice)
                                       ->orWhere('current_bid', '>=', $this->minPrice);
                                 });
                });
            });
        }

        if ($this->maxPrice > 0) {
            $query->where(function($q) {
                $q->where(function($normalPrice) {
                    $normalPrice->where('type', 'normal')
                                ->where('price', '<=', $this->maxPrice);
                })->orWhere(function($auctionPrice) {
                    $auctionPrice->where('type', 'auction')
                                 ->where(function($p) {
                                     $p->where('base_price', '<=', $this->maxPrice)
                                       ->orWhere('current_bid', '<=', $this->maxPrice);
                                 });
                });
            });
        }

        $allAds = $query->with(['images' => function($q) { 
                $q->orderBy('id', 'asc'); // Load all images, ordered consistently
            }, 'user:id,is_verified', 'bids' => function($q) {
                $q->limit(1);
            }])
            ->latest()
            ->take(12)
            ->get();

        $settings = SiteSetting::getSettings();
        $categories = Category::where('is_active', true)->orderBy('order')->orderBy('name')->get();

        return view('livewire.home', [
            'ads' => $allAds,
            'settings' => $settings,
            'categories' => $categories,
        ])->layout('layouts.app');
    }
}



