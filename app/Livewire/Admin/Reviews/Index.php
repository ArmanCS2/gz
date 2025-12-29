<?php

namespace App\Livewire\Admin\Reviews;

use App\Models\Review;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $statusFilter = 'pending'; // pending, approved, rejected, all
    public $searchQuery = '';

    public function approve($id)
    {
        $review = Review::findOrFail($id);
        $review->update(['status' => 'approved']);
        $this->dispatch('showToast', ['message' => 'نظر تایید شد.', 'type' => 'success']);
    }

    public function reject($id)
    {
        $review = Review::findOrFail($id);
        $review->update(['status' => 'rejected']);
        $this->dispatch('showToast', ['message' => 'نظر رد شد.', 'type' => 'success']);
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
    }

    public function updatedSearchQuery()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Review::with(['user:id,name', 'ad:id,title']);

        // فیلتر بر اساس وضعیت
        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        // جستجو
        if ($this->searchQuery) {
            $query->where(function($q) {
                $q->whereHas('user', function($userQuery) {
                    $userQuery->where('name', 'like', '%' . $this->searchQuery . '%');
                })
                ->orWhereHas('ad', function($adQuery) {
                    $adQuery->where('title', 'like', '%' . $this->searchQuery . '%');
                })
                ->orWhere('comment', 'like', '%' . $this->searchQuery . '%');
            });
        }

        $reviews = $query->latest()->paginate(20);
        $reviews->setPath('/admin/reviews');

        return view('livewire.admin.reviews.index', [
            'reviews' => $reviews,
        ])->layout('layouts.admin');
    }
}
