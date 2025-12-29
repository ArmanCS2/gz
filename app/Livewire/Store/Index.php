<?php

namespace App\Livewire\Store;

use App\Models\Ad;
use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $category = '';
    public $type = 'all';
    public $min_members = '';
    public $max_members = '';
    public $min_price = '';
    public $max_price = '';
    public $sort = 'latest';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCategory()
    {
        $this->resetPage();
    }

    public function updatingType()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->category = '';
        $this->type = 'all';
        $this->min_members = '';
        $this->max_members = '';
        $this->min_price = '';
        $this->max_price = '';
        $this->sort = 'latest';
        $this->resetPage();
    }

    public function render()
    {
        // فقط آگهی‌هایی که منقضی نشده‌اند نمایش داده می‌شوند
        $query = Ad::where('status', 'active')
            ->where('is_active', true)
            ->where(function($q) {
                // آگهی‌هایی که expire_at ندارند یا expire_at آنها در آینده است
                $q->whereNull('expire_at')
                  ->orWhere('expire_at', '>', now());
            });

        // Search filter
        if ($this->search) {
            $query->where(function($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        // Category filter
        if ($this->category) {
            $query->where('category_id', $this->category);
        }

        // Type filter
        if ($this->type === 'normal') {
            $query->where('type', 'normal');
        } elseif ($this->type === 'auction') {
            $query->where('type', 'auction');
        }

        // Min members filter
        if ($this->min_members) {
            $query->where('member_count', '>=', $this->min_members);
        }

        // Max members filter
        if ($this->max_members) {
            $query->where('member_count', '<=', $this->max_members);
        }

        // Min price filter
        if ($this->min_price) {
            $query->where(function($q) {
                $q->where(function($normalPrice) {
                    $normalPrice->where('type', 'normal')
                                ->where('price', '>=', $this->min_price);
                })->orWhere(function($auctionPrice) {
                    $auctionPrice->where('type', 'auction')
                                 ->where(function($p) {
                                     $p->where('current_bid', '>=', $this->min_price)
                                       ->orWhere('base_price', '>=', $this->min_price);
                                 });
                });
            });
        }

        // Max price filter
        if ($this->max_price) {
            $query->where(function($q) {
                $q->where(function($normalPrice) {
                    $normalPrice->where('type', 'normal')
                                ->where('price', '<=', $this->max_price);
                })->orWhere(function($auctionPrice) {
                    $auctionPrice->where('type', 'auction')
                                 ->where(function($p) {
                                     $p->where('current_bid', '<=', $this->max_price)
                                       ->orWhere('base_price', '<=', $this->max_price);
                                 });
                });
            });
        }

        // Sort filter
        switch ($this->sort) {
            case 'oldest':
                $query->oldest();
                break;
            case 'price_asc':
                $query->orderByRaw("CASE WHEN type = 'normal' THEN price ELSE COALESCE(current_bid, base_price) END ASC");
                break;
            case 'price_desc':
                $query->orderByRaw("CASE WHEN type = 'normal' THEN price ELSE COALESCE(current_bid, base_price) END DESC");
                break;
            case 'members_asc':
                $query->orderBy('member_count', 'asc');
                break;
            case 'members_desc':
                $query->orderBy('member_count', 'desc');
                break;
            case 'latest':
            default:
                $query->latest();
                break;
        }

        $ads = $query->with(['images' => function($q) { 
                $q->orderBy('id', 'asc'); // Load all images, ordered consistently
            }, 'user:id,is_verified', 'bids' => function($q) {
                $q->limit(1);
            }])
            ->paginate(12);
        
        $ads->setPath('/store');

        $categories = Category::where('is_active', true)->orderBy('name')->get();

        return view('livewire.store.index', [
            'ads' => $ads,
            'categories' => $categories,
        ])->layout('layouts.app');
    }
}

