@extends('layouts.app')

@section('title', ($post->seo_title ?? $post->title) . ' - بلاگ گروه باز')

@push('meta')
    <meta name="description" content="{{ $post->seo_description ?? Str::limit(strip_tags($post->excerpt ?? $post->content), 160) }}">
    <meta name="keywords" content="{{ $post->seo_keywords ?? $post->tags->pluck('name')->join(', ') }}">
    <meta property="og:title" content="{{ $post->title }}">
    <meta property="og:description" content="{{ $post->seo_description ?? Str::limit(strip_tags($post->excerpt ?? $post->content), 200) }}">
    <meta property="og:type" content="article">
    <meta property="og:url" content="{{ route('blog.post', $post->slug) }}">
    @if($post->banner_image)
    <meta property="og:image" content="{{ asset($post->banner_image) }}">
    @endif
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $post->title }}">
    <meta name="twitter:description" content="{{ $post->seo_description ?? Str::limit(strip_tags($post->excerpt ?? $post->content), 200) }}">
    @if($post->banner_image)
    <meta name="twitter:image" content="{{ asset($post->banner_image) }}">
    @endif
    <link rel="canonical" href="{{ route('blog.post', $post->slug) }}">
    
    <!-- JSON-LD Schema -->
    <script type="application/ld+json">
    @php
        $jsonLd = [
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => $post->title,
            'description' => $post->seo_description ?? Str::limit(strip_tags($post->excerpt ?? $post->content), 200),
            'datePublished' => $post->published_at->toIso8601String(),
            'dateModified' => $post->updated_at->toIso8601String(),
            'author' => [
                '@type' => 'Person',
                'name' => $post->author->name
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name' => 'گروه باز',
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => asset('favicon.ico')
                ]
            ]
        ];
        if ($post->banner_image) {
            $jsonLd['image'] = asset($post->banner_image);
        }
    @endphp
    {!! json_encode($jsonLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
@endpush

@section('content')
<div class="container py-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">خانه</a></li>
            <li class="breadcrumb-item"><a href="{{ route('blog.index') }}" class="text-decoration-none">بلاگ</a></li>
            @if($post->category)
            <li class="breadcrumb-item"><a href="{{ route('blog.category', $post->category->slug) }}" class="text-decoration-none">{{ $post->category->name }}</a></li>
            @endif
            <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($post->title, 30) }}</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <article class="glass-card p-4 p-md-5 mb-4">
                <!-- Post Header -->
                <header class="mb-4">
                    @if($post->category)
                    <span class="badge mb-3" style="background: rgba(0, 240, 255, 0.2); color: #00f0ff; border: 1px solid rgba(0, 240, 255, 0.3);">
                        {{ $post->category->name }}
                    </span>
                    @endif
                    <h1 class="display-5 mb-3 text-white">{{ $post->title }}</h1>
                    
                    <div class="d-flex flex-wrap align-items-center gap-3 text-white-50 mb-4">
                        <span>
                            <i class="bi bi-person me-1"></i>
                            {{ $post->author->name }}
                        </span>
                        <span>
                            <i class="bi bi-calendar me-1"></i>
                            {{ $post->published_at ? $post->published_at->format('Y/m/d') : 'منتشر نشده' }}
                        </span>
                        <span>
                            <i class="bi bi-clock me-1"></i>
                            {{ $post->reading_time }} دقیقه مطالعه
                        </span>
                        <span>
                            <i class="bi bi-eye me-1"></i>
                            {{ number_format($post->views_count) }} بازدید
                        </span>
                    </div>

                    <!-- Tags -->
                    @if($post->tags->count() > 0)
                    <div class="mb-4">
                        @foreach($post->tags as $tag)
                        <a href="{{ route('blog.tag', $tag->slug) }}" class="badge me-2 mb-2" style="background: rgba(176, 38, 255, 0.2); color: #b026ff; border: 1px solid rgba(176, 38, 255, 0.3); text-decoration: none;">
                            <i class="bi bi-tag me-1"></i>
                            {{ $tag->name }}
                        </a>
                        @endforeach
                    </div>
                    @endif
                </header>

                <!-- Banner Image -->
                @if($post->banner_image)
                <div class="mb-4">
                    <img src="{{ asset($post->banner_image) }}" alt="{{ $post->title }}" class="w-100 rounded" style="max-height: 500px; object-fit: cover;">
                </div>
                @endif

                <!-- Post Content -->
                <div class="post-content text-white" style="line-height: 1.8; font-size: 1.1rem;">
                    {!! $post->content !!}
                </div>

                <!-- Share Buttons -->
                <div class="mt-5 pt-4 border-top" style="border-color: rgba(255,255,255,0.1) !important;">
                    <h6 class="mb-3 text-white">اشتراک‌گذاری:</h6>
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="https://telegram.me/share/url?url={{ urlencode(route('blog.post', $post->slug)) }}&text={{ urlencode($post->title) }}" 
                           target="_blank" 
                           class="btn btn-modern btn-sm"
                           style="background: rgba(0, 136, 204, 0.2); border: 1px solid rgba(0, 136, 204, 0.3);">
                            <i class="bi bi-telegram me-1"></i> تلگرام
                        </a>
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(route('blog.post', $post->slug)) }}&text={{ urlencode($post->title) }}" 
                           target="_blank" 
                           class="btn btn-modern btn-sm"
                           style="background: rgba(29, 161, 242, 0.2); border: 1px solid rgba(29, 161, 242, 0.3);">
                            <i class="bi bi-twitter me-1"></i> توییتر
                        </a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('blog.post', $post->slug)) }}" 
                           target="_blank" 
                           class="btn btn-modern btn-sm"
                           style="background: rgba(24, 119, 242, 0.2); border: 1px solid rgba(24, 119, 242, 0.3);">
                            <i class="bi bi-facebook me-1"></i> فیسبوک
                        </a>
                        <button onclick="copyToClipboard('{{ route('blog.post', $post->slug) }}')" 
                                class="btn btn-modern btn-sm"
                                style="background: rgba(255, 255, 255, 0.1); border: 1px solid rgba(255, 255, 255, 0.2);">
                            <i class="bi bi-link-45deg me-1"></i> کپی لینک
                        </button>
                    </div>
                </div>
            </article>

            <!-- Author Box -->
            <div class="glass-card p-4 mb-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="category-icon" style="width: 80px; height: 80px; font-size: 2rem;">
                        <i class="bi bi-person-circle"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h5 class="text-white mb-1">{{ $post->author->name }}</h5>
                        <p class="text-white-50 small mb-2">نویسنده مقاله</p>
                        <a href="{{ route('blog.author', $post->author->id) }}" class="btn btn-modern btn-sm">
                            مشاهده مقالات دیگر
                        </a>
                    </div>
                </div>
            </div>

            <!-- Related Posts -->
            @if($relatedPosts->count() > 0)
            <section class="mb-4">
                <h3 class="mb-4 text-white">مقالات مرتبط</h3>
                <div class="row g-3">
                    @foreach($relatedPosts as $relatedPost)
                    <div class="col-md-6">
                        <div class="glass-card p-3 h-100">
                            @if($relatedPost->banner_image)
                            <a href="{{ route('blog.post', $relatedPost->slug) }}">
                                <img src="{{ asset('storage/' . $relatedPost->banner_image) }}" alt="{{ $relatedPost->title }}" class="w-100 mb-3 rounded" style="height: 150px; object-fit: cover;">
                            </a>
                            @endif
                            <h6 class="mb-2">
                                <a href="{{ route('blog.post', $relatedPost->slug) }}" class="text-white text-decoration-none">
                                    {{ Str::limit($relatedPost->title, 60) }}
                                </a>
                            </h6>
                            <small class="text-white-50">
                                <i class="bi bi-calendar me-1"></i>
                                {{ $relatedPost->published_at->format('Y/m/d') }}
                            </small>
                        </div>
                    </div>
                    @endforeach
                </div>
            </section>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Table of Contents (if needed) -->
            <div class="glass-card p-4 mb-4 sticky-top" style="top: 100px;">
                <h6 class="mb-3 text-white">اطلاعات مقاله</h6>
                <ul class="list-unstyled text-white-50 small">
                    <li class="mb-2">
                        <i class="bi bi-calendar me-2"></i>
                        تاریخ انتشار: {{ $post->published_at ? $post->published_at->format('Y/m/d') : 'منتشر نشده' }}
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-clock me-2"></i>
                        زمان مطالعه: {{ $post->reading_time }} دقیقه
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-eye me-2"></i>
                        بازدید: {{ number_format($post->views_count) }}
                    </li>
                    @if($post->category)
                    <li class="mb-2">
                        <i class="bi bi-folder me-2"></i>
                        دسته‌بندی: 
                        <a href="{{ route('blog.category', $post->category->slug) }}" class="text-decoration-none" style="color: #00f0ff;">
                            {{ $post->category->name }}
                        </a>
                    </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .post-content {
        color: #e5e7eb !important;
    }
    
    .post-content h1,
    .post-content h2,
    .post-content h3,
    .post-content h4 {
        color: #ffffff !important;
        margin-top: 2rem;
        margin-bottom: 1rem;
    }
    
    .post-content p {
        margin-bottom: 1.5rem;
    }
    
    .post-content code {
        background: rgba(0, 0, 0, 0.3);
        padding: 2px 6px;
        border-radius: 4px;
        color: #00f0ff;
        font-family: 'Courier New', monospace;
    }
    
    .post-content pre {
        background: rgba(0, 0, 0, 0.5);
        padding: 1rem;
        border-radius: 8px;
        overflow-x: auto;
        border: 1px solid rgba(255, 255, 255, 0.1);
        margin: 1.5rem 0;
    }
    
    .post-content pre code {
        background: transparent;
        padding: 0;
        color: #e5e7eb;
    }
    
    .post-content a {
        color: #00f0ff;
        text-decoration: underline;
    }
    
    .post-content a:hover {
        color: #b026ff;
    }
    
    .post-content img {
        max-width: 100%;
        height: auto;
        border-radius: 8px;
        margin: 1.5rem 0;
    }
    
    .post-content blockquote {
        border-right: 4px solid #00f0ff;
        padding-right: 1rem;
        margin: 1.5rem 0;
        color: #b0b0b0;
        font-style: italic;
    }
    
    .post-content ul,
    .post-content ol {
        margin: 1.5rem 0;
        padding-right: 2rem;
    }
    
    .post-content li {
        margin-bottom: 0.5rem;
    }
</style>
@endpush
@endsection

