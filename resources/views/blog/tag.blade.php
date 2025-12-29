@extends('layouts.app')

@section('title', $tag->name . ' - بلاگ گروه باز')

@push('meta')
    <meta name="description" content="{{ $tag->description ?? 'مقالات با برچسب ' . $tag->name }}">
    <meta property="og:title" content="{{ $tag->name }} - بلاگ گروه باز">
    <link rel="canonical" href="{{ route('blog.tag', $tag->slug) }}">
@endpush

@section('content')
<div class="container py-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">خانه</a></li>
            <li class="breadcrumb-item"><a href="{{ route('blog.index') }}" class="text-decoration-none">بلاگ</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $tag->name }}</li>
        </ol>
    </nav>

    <!-- Tag Header -->
    <div class="glass-card p-4 p-md-5 mb-5 text-center">
        <div class="badge mb-3" style="background: rgba(176, 38, 255, 0.2); color: #b026ff; border: 1px solid rgba(176, 38, 255, 0.3); padding: 1rem 2rem; font-size: 1.2rem;">
            <i class="bi bi-tag-fill me-2"></i>
            {{ $tag->name }}
        </div>
        @if($tag->description)
        <p class="lead text-white-50">{{ $tag->description }}</p>
        @endif
        <p class="text-white-50 mb-0">
            <i class="bi bi-file-text me-1"></i>
            {{ $posts->total() }} مقاله
        </p>
    </div>

    <!-- Posts Grid -->
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
                <i class="bi bi-inbox" style="font-size: 4rem; color: rgba(255,255,255,0.3);"></i>
                <p class="text-white-50 mt-3">هنوز مقاله‌ای با این برچسب منتشر نشده است.</p>
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
@endsection

