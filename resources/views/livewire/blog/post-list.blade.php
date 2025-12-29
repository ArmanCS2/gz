<div>
    <div class="row g-4">
        @forelse($posts as $post)
        <div class="col-md-6 col-lg-4">
            <div class="glass-card h-100">
                @if($post->banner_image)
                <a href="{{ route('blog.post', $post->slug) }}">
                    <img src="{{ asset($post->banner_image) }}" alt="{{ $post->title }}" class="w-100" style="height: 200px; object-fit: cover; border-radius: 20px 20px 0 0;">
                </a>
                @endif
                <div class="p-4">
                    @if($post->category)
                    <span class="badge mb-2" style="background: rgba(0, 240, 255, 0.2); color: #00f0ff; border: 1px solid rgba(0, 240, 255, 0.3);">
                        {{ $post->category->name }}
                    </span>
                    @endif
                    <h5 class="mb-3">
                        <a href="{{ route('blog.post', $post->slug) }}" class="text-white text-decoration-none">
                            {{ $post->title }}
                        </a>
                    </h5>
                    <p class="text-white-50 small mb-3">{{ Str::limit($post->excerpt ?? strip_tags($post->content), 120) }}</p>
                    <div class="d-flex align-items-center justify-content-between text-white-50 small">
                        <span>
                            <i class="bi bi-person me-1"></i>
                            {{ $post->author->name }}
                        </span>
                        <span>
                            <i class="bi bi-eye me-1"></i>
                            {{ number_format($post->views_count) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="glass-card p-5 text-center">
                <i class="bi bi-inbox" style="font-size: 4rem; color: rgba(255,255,255,0.3);"></i>
                <p class="text-white-50 mt-3">مقاله‌ای یافت نشد.</p>
            </div>
        </div>
        @endforelse
    </div>
    
    <!-- Pagination -->
    @if($posts->hasPages())
    <div class="mt-5">
        {{ $posts->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>


