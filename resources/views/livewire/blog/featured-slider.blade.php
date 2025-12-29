<div class="swiper featured-swiper">
    <div class="swiper-wrapper">
        @foreach($posts as $post)
        <div class="swiper-slide">
            <div class="glass-card h-100 position-relative overflow-hidden">
                @if($post->banner_image)
                <img src="{{ asset($post->banner_image) }}" alt="{{ $post->title }}" class="w-100" style="height: 250px; object-fit: cover;">
                @else
                <div class="w-100 d-flex align-items-center justify-content-center" style="height: 250px; background: linear-gradient(135deg, rgba(0, 240, 255, 0.2), rgba(176, 38, 255, 0.2));">
                    <i class="bi bi-file-text" style="font-size: 4rem; color: rgba(255,255,255,0.3);"></i>
                </div>
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
                    <p class="text-white-50 small mb-3">{{ Str::limit($post->excerpt ?? strip_tags($post->content), 100) }}</p>
                    <div class="d-flex align-items-center justify-content-between text-white-50 small">
                        <span>
                            <i class="bi bi-person me-1"></i>
                            {{ $post->author->name }}
                        </span>
                        <span>
                            <i class="bi bi-calendar me-1"></i>
                            {{ $post->published_at->format('Y/m/d') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <div class="swiper-pagination"></div>
    <div class="swiper-button-next"></div>
    <div class="swiper-button-prev"></div>
</div>


