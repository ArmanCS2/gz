<div>
    <div class="container py-5">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">خانه</a></li>
                <li class="breadcrumb-item"><a href="{{ route('blog.index') }}" class="text-decoration-none">بلاگ</a></li>
                <li class="breadcrumb-item active" aria-current="page">جستجو</li>
            </ol>
        </nav>

        <!-- Search Header -->
        <div class="glass-card p-4 p-md-5 mb-5">
            <h1 class="display-5 mb-4 text-white">جستجو در مقالات</h1>
            
            <!-- Search Input -->
            <div class="position-relative">
                <input 
                    type="text" 
                    wire:model.live.debounce.500ms="query" 
                    class="form-control modern-input" 
                    placeholder="جستجو در مقالات..."
                    style="padding-right: 50px;"
                >
                <i class="bi bi-search position-absolute" style="right: 18px; top: 50%; transform: translateY(-50%); color: rgba(255,255,255,0.5);"></i>
            </div>
            
            @if(strlen($query) >= 2)
            <p class="text-white-50 mt-3 mb-0">
                <i class="bi bi-file-text me-1"></i>
                {{ $posts->total() }} نتیجه برای "{{ $query }}"
            </p>
            @elseif(strlen($query) > 0)
            <p class="text-warning mt-3 mb-0">
                <i class="bi bi-info-circle me-1"></i>
                حداقل ۲ کاراکتر وارد کنید
            </p>
            @endif
        </div>

        <!-- Search Results -->
        @if(strlen($query) >= 2)
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
                                {!! str_ireplace($query, '<mark style="background: rgba(0, 240, 255, 0.3); color: #00f0ff; padding: 2px 4px; border-radius: 4px;">' . $query . '</mark>', $post->title) !!}
                            </a>
                        </h5>
                        <p class="text-white-50 small mb-3">
                            {!! str_ireplace($query, '<mark style="background: rgba(0, 240, 255, 0.3); color: #00f0ff; padding: 2px 4px; border-radius: 4px;">' . $query . '</mark>', Str::limit($post->excerpt ?? strip_tags($post->content), 120)) !!}
                        </p>
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
            @empty
            <div class="col-12">
                <div class="glass-card p-5 text-center">
                    <i class="bi bi-search" style="font-size: 4rem; color: rgba(255,255,255,0.3);"></i>
                    <p class="text-white-50 mt-3">نتیجه‌ای برای "{{ $query }}" یافت نشد.</p>
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
        @elseif(strlen($query) == 0)
        <div class="glass-card p-5 text-center">
            <i class="bi bi-search" style="font-size: 4rem; color: rgba(255,255,255,0.3);"></i>
            <p class="text-white-50 mt-3">برای جستجو، عبارت مورد نظر خود را در کادر بالا وارد کنید.</p>
        </div>
        @endif
    </div>
</div>


