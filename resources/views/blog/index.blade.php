@extends('layouts.app')

@section('title', 'بلاگ - گروه باز')

@push('meta')
    <meta name="description" content="مقالات و مطالب آموزشی درباره خرید و فروش گروه‌های تلگرام">
    <meta name="keywords" content="بلاگ, مقاله, آموزش, گروه تلگرام, خرید و فروش">
    <meta property="og:title" content="بلاگ - گروه باز">
    <meta property="og:description" content="مقالات و مطالب آموزشی درباره خرید و فروش گروه‌های تلگرام">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ route('blog.index') }}">
@endpush

@section('content')
<div class="container py-5">
    <!-- Hero Section -->
    <div class="hero-section mb-5">
        <div class="glass-card p-5 text-center">
            <h1 class="display-4 mb-4" style="background: linear-gradient(135deg, #00f0ff, #b026ff); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                بلاگ گروه باز
            </h1>
            <p class="lead text-white-50 mb-4">مقالات و مطالب آموزشی درباره خرید و فروش گروه‌های تلگرام</p>
            
            <!-- Search Bar -->
            <form action="{{ route('blog.search') }}" method="GET" class="d-flex gap-2 justify-content-center">
                <div class="position-relative" style="max-width: 500px; width: 100%;">
                    <input 
                        type="text" 
                        name="query" 
                        class="form-control modern-input" 
                        placeholder="جستجو در مقالات..."
                        value="{{ request('query') }}"
                        style="padding-right: 50px;"
                    >
                    <button type="submit" class="btn btn-modern position-absolute" style="left: 10px; top: 50%; transform: translateY(-50%); padding: 8px 16px;">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Featured Posts Slider -->
    @if($featuredPosts->count() > 0)
    <section class="mb-5">
        <h2 class="mb-4 text-white">
            <i class="bi bi-star-fill text-warning me-2"></i>
            مقالات ویژه
        </h2>
        <div class="swiper featured-swiper">
            <div class="swiper-wrapper">
                @foreach($featuredPosts as $post)
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
                                    {{ \Morilog\Jalali\Jalalian::fromCarbon($post->published_at)->format('Y/m/d') }}
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
    </section>
    @endif

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Latest Posts -->
            <section class="mb-5">
                <h2 class="mb-4 text-white">
                    <i class="bi bi-clock-history me-2"></i>
                    جدیدترین مقالات
                </h2>
                <div class="row g-4">
                    @forelse($latestPosts as $post)
                    <div class="col-md-6">
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
                            <p class="text-white-50 mt-3">هنوز مقاله‌ای منتشر نشده است.</p>
                        </div>
                    </div>
                    @endforelse
                </div>
                
                <!-- Pagination -->
                @if($latestPosts->hasPages())
                <div class="mt-4">
                    {{ $latestPosts->links('pagination::bootstrap-5') }}
                </div>
                @endif
            </section>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Categories -->
            <section class="mb-4">
                <div class="glass-card p-4">
                    <h5 class="mb-4 text-white">
                        <i class="bi bi-folder me-2"></i>
                        دسته‌بندی‌ها
                    </h5>
                    <div class="list-group list-group-flush">
                        @foreach($categories as $category)
                        <a href="{{ route('blog.category', $category->slug) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center" style="background: transparent; border-color: rgba(255,255,255,0.1); color: #e5e7eb;">
                            <span>
                                <i class="bi {{ $category->icon ?? 'bi-grid-3x3-gap' }} me-2"></i>
                                {{ $category->name }}
                            </span>
                            <span class="badge" style="background: rgba(0, 240, 255, 0.2); color: #00f0ff;">
                                {{ $category->published_posts_count }}
                            </span>
                        </a>
                        @endforeach
                    </div>
                </div>
            </section>

            <!-- Trending Posts -->
            @if($trendingPosts->count() > 0)
            <section class="mb-4">
                <div class="glass-card p-4">
                    <h5 class="mb-4 text-white">
                        <i class="bi bi-fire me-2 text-danger"></i>
                        پربازدیدترین‌ها
                    </h5>
                    <div class="list-group list-group-flush">
                        @foreach($trendingPosts as $post)
                        <a href="{{ route('blog.post', $post->slug) }}" class="list-group-item list-group-item-action" style="background: transparent; border-color: rgba(255,255,255,0.1);">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1 text-white" style="font-size: 0.9rem;">{{ Str::limit($post->title, 50) }}</h6>
                            </div>
                            <small class="text-white-50">
                                <i class="bi bi-eye me-1"></i>
                                {{ number_format($post->views_count) }} بازدید
                            </small>
                        </a>
                        @endforeach
                    </div>
                </div>
            </section>
            @endif
        </div>
    </div>
</div>

@push('styles')
<style>
    .featured-swiper {
        padding-bottom: 50px;
    }
    
    .featured-swiper .swiper-slide {
        height: auto;
    }
    
    .swiper-button-next,
    .swiper-button-prev {
        color: #00f0ff;
    }
    
    .swiper-pagination-bullet-active {
        background: #00f0ff;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof Swiper !== 'undefined') {
            new Swiper('.featured-swiper', {
                slidesPerView: 1,
                spaceBetween: 20,
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                breakpoints: {
                    640: {
                        slidesPerView: 2,
                    },
                    1024: {
                        slidesPerView: 3,
                    },
                },
            });
        }
    });
</script>
@endpush
@endsection

