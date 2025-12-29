<div>
    <!-- آمار نظرات -->
    <div class="glass-card p-4 mb-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <div class="d-flex align-items-center gap-3">
                    <div class="text-center">
                        <div class="display-4 fw-bold mb-0" style="background: linear-gradient(135deg, #00f0ff, #b026ff); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                            {{ number_format($averageRating, 1) }}
                        </div>
                        <div class="d-flex justify-content-center gap-1 mt-2">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="bi bi-star{{ $i <= round($averageRating) ? '-fill' : '' }}" 
                                   style="color: {{ $i <= round($averageRating) ? '#ffd700' : '#9ca3af' }}; font-size: 1.2rem;"></i>
                            @endfor
                        </div>
                    </div>
                    <div>
                        <div class="fw-bold mb-1" style="color: #ffffff;">میانگین امتیاز</div>
                        <div class="text-muted">بر اساس {{ $reviewsCount }} نظر</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- فرم ثبت نظر -->
    @auth
        @if(!$hasReviewed && $ad->user_id !== auth()->id())
            <div class="glass-card p-4 mb-4">
                <h4 class="fw-bold mb-3" style="color: #ffffff;">
                    <i class="bi bi-chat-left-text me-2" style="color: #00f0ff;"></i>
                    ثبت نظر و امتیاز
                </h4>
                <form wire:submit.prevent="submitReview">
                    <div class="mb-3">
                        <label class="form-label mb-2 d-block fw-semibold" style="color: #ffffff; font-size: 14px;">
                            امتیاز شما <span class="text-danger">*</span>
                        </label>
                        <div class="d-flex gap-2 align-items-center" style="direction: ltr;">
                            @for($i = 5; $i >= 1; $i--)
                                <label style="cursor: pointer; margin: 0; padding: 4px;" 
                                       wire:click="$set('rating', {{ $i }})"
                                       onmouseover="this.querySelector('i').style.color='#ffd700'; this.querySelector('i').style.transform='scale(1.2)'"
                                       onmouseout="this.querySelector('i').style.color='{{ $rating >= $i ? '#ffd700' : '#9ca3af' }}'; this.querySelector('i').style.transform='scale(1)'">
                                    <input type="radio" wire:model="rating" value="{{ $i }}" class="d-none">
                                    <i class="bi bi-star{{ $rating >= $i ? '-fill' : '' }}" 
                                       style="color: {{ $rating >= $i ? '#ffd700' : '#9ca3af' }}; font-size: 2rem; transition: all 0.2s; display: block;"></i>
                                </label>
                            @endfor
                            <span class="ms-3" style="color: #ffffff; font-weight: 600;">{{ $rating }}/5</span>
                        </div>
                        @error('rating') 
                            <span class="text-danger small d-block mt-2">
                                <i class="bi bi-exclamation-circle me-1"></i> {{ $message }}
                            </span> 
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label mb-2 d-block fw-semibold" style="color: #ffffff; font-size: 14px;">
                            نظر شما
                        </label>
                        <textarea class="modern-input w-100 @error('comment') border-danger @enderror" 
                                  rows="4" 
                                  placeholder="نظر خود را درباره این آگهی بنویسید..." 
                                  wire:model="comment"
                                  style="color: #ffffff !important; resize: vertical;"></textarea>
                        @error('comment') 
                            <span class="text-danger small d-block mt-2">
                                <i class="bi bi-exclamation-circle me-1"></i> {{ $message }}
                            </span> 
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-modern">
                        <i class="bi bi-send me-2"></i> ثبت نظر
                    </button>
                </form>
            </div>
        @elseif($hasReviewed)
            <div class="glass-card p-3 mb-4" style="background: rgba(0, 240, 255, 0.1); border-color: rgba(0, 240, 255, 0.3);">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-info-circle" style="color: #00f0ff; font-size: 1.5rem;"></i>
                    <div>
                        <strong style="color: #ffffff;">شما قبلاً برای این آگهی نظر داده‌اید.</strong>
                        <div class="text-muted small">نظر شما پس از تایید ادمین نمایش داده می‌شود.</div>
                    </div>
                </div>
            </div>
        @endif
    @else
        <div class="glass-card p-3 mb-4" style="background: rgba(255, 170, 0, 0.1); border-color: rgba(255, 170, 0, 0.3);">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-exclamation-triangle" style="color: #ffaa00; font-size: 1.5rem;"></i>
                <div>
                    <strong style="color: #ffffff;">برای ثبت نظر باید وارد شوید.</strong>
                    <div class="text-muted small">
                        <a href="{{ route('home') }}" wire:navigate style="color: #00f0ff; text-decoration: none;">ورود به سایت</a>
                    </div>
                </div>
            </div>
        </div>
    @endauth

    <!-- لیست نظرات -->
    <div class="glass-card p-4">
        <h4 class="fw-bold mb-4" style="color: #ffffff;">
            <i class="bi bi-chat-dots me-2" style="color: #00f0ff;"></i>
            نظرات کاربران ({{ $reviews->total() }})
        </h4>

        @if($reviews->count() > 0)
            <div class="reviews-list">
                @foreach($reviews as $review)
                    <div class="review-item mb-4 pb-4" style="border-bottom: 1px solid rgba(255,255,255,0.1);">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div class="d-flex align-items-center gap-3">
                                <div>
                                    <div class="fw-bold" style="color: #ffffff;">{{ $review->user->name }}</div>
                                    <div class="text-muted small">{{ \App\Helpers\DateHelper::diffForHumans($review->created_at) }}</div>
                                </div>
                            </div>
                            <div class="d-flex gap-1" style="direction: ltr;">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="bi bi-star{{ $i <= $review->rating ? '-fill' : '' }}" 
                                       style="color: {{ $i <= $review->rating ? '#ffd700' : '#9ca3af' }};"></i>
                                @endfor
                            </div>
                        </div>
                        @if($review->comment)
                            <div class="mt-2" style="color: #e5e7eb; line-height: 1.8;">
                                {{ $review->comment }}
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            <div class="mt-4">
                {{ $reviews->links('pagination::bootstrap-5') }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-chat-left-text" style="font-size: 4rem; color: rgba(255,255,255,0.3);"></i>
                <p class="text-muted mt-3 mb-0">هنوز نظری ثبت نشده است.</p>
            </div>
        @endif
    </div>
</div>
