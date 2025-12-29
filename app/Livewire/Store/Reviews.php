<?php

namespace App\Livewire\Store;

use App\Models\Ad;
use App\Models\Review;
use Livewire\Component;
use Livewire\WithPagination;

class Reviews extends Component
{
    use WithPagination;

    public Ad $ad;
    public $rating = 5;
    public $comment = '';
    public $hasReviewed = false;

    protected $rules = [
        'rating' => 'required|integer|min:1|max:5',
        'comment' => 'nullable|string|max:1000',
    ];

    protected $messages = [
        'rating.required' => 'لطفا امتیاز خود را انتخاب کنید.',
        'rating.min' => 'امتیاز باید بین 1 تا 5 باشد.',
        'rating.max' => 'امتیاز باید بین 1 تا 5 باشد.',
        'comment.max' => 'نظر شما نمی‌تواند بیشتر از 1000 کاراکتر باشد.',
    ];

    public function mount($ad)
    {
        // دریافت ad از route parameter یا object
        $this->ad = $ad instanceof Ad ? $ad : Ad::findOrFail($ad);
        
        // بررسی اینکه آیا کاربر قبلاً برای این آگهی نظر داده است
        if (auth()->check()) {
            $this->hasReviewed = Review::where('user_id', auth()->id())
                ->where('ad_id', $this->ad->id)
                ->exists();
        }
    }

    public function submitReview()
    {
        if (!auth()->check()) {
            $this->dispatch('showToast', ['message' => 'لطفا ابتدا وارد شوید.', 'type' => 'error']);
            return;
        }

        // بررسی اینکه آیا کاربر قبلاً نظر داده است
        if ($this->hasReviewed) {
            $this->dispatch('showToast', ['message' => 'شما قبلاً برای این آگهی نظر داده‌اید.', 'type' => 'error']);
            return;
        }

        // کاربر نمی‌تواند برای آگهی خودش نظر بدهد
        if ($this->ad->user_id === auth()->id()) {
            $this->dispatch('showToast', ['message' => 'شما نمی‌توانید برای آگهی خودتان نظر بدهید.', 'type' => 'error']);
            return;
        }

        $this->validate();

        Review::create([
            'user_id' => auth()->id(),
            'ad_id' => $this->ad->id,
            'rating' => $this->rating,
            'comment' => $this->comment ?: null,
            'status' => 'pending', // نیاز به تایید ادمین
        ]);

        $this->reset(['rating', 'comment']);
        $this->hasReviewed = true;
        $this->resetPage();
        
        $this->dispatch('showToast', ['message' => 'نظر شما با موفقیت ثبت شد و پس از تایید ادمین نمایش داده می‌شود.', 'type' => 'success']);
    }

    public function render()
    {
        // Reload ad to get fresh reviews count
        $this->ad->refresh();
        $this->ad->load('approvedReviews.user');
        
        $reviews = $this->ad->approvedReviews()
            ->with('user:id,name')
            ->latest()
            ->paginate(10);
        
        $reviews->setPath('/store/' . $this->ad->slug);

        return view('livewire.store.reviews', [
            'reviews' => $reviews,
            'averageRating' => $this->ad->average_rating,
            'reviewsCount' => $this->ad->reviews_count,
        ]);
    }
}
