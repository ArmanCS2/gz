@extends('layouts.app')

@section('title', $pageTitle)
@section('description', $pageDescription)

@push('meta')
    <meta name="keywords" content="{{ $action }} {{ $typeInfo['label'] }}, {{ $action }} {{ $typeInfo['label'] }} قیمت, {{ $action }} {{ $typeInfo['label'] }} واقعی, {{ $action }} {{ $typeInfo['label'] }} بدون واسطه">
@endpush

@push('json-ld')
@php
    // Build WebPage schema
    $webPageSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'WebPage',
        'name' => $pageTitle,
        'description' => $pageDescription,
        'url' => route('seo.landing', ['action' => $action, 'type' => $type]),
        'inLanguage' => 'fa-IR',
        'breadcrumb' => [
            '@type' => 'BreadcrumbList',
            'itemListElement' => [
                [
                    '@type' => 'ListItem',
                    'position' => 1,
                    'name' => 'خانه',
                    'item' => route('home')
                ],
                [
                    '@type' => 'ListItem',
                    'position' => 2,
                    'name' => $action . ' ' . $typeInfo['label'],
                    'item' => route('seo.landing', ['action' => $action, 'type' => $type])
                ]
            ]
        ],
        'mainEntity' => [
            '@type' => 'ItemList',
            'numberOfItems' => $ads->count(),
            'itemListElement' => []
        ]
    ];
    
    // Build item list elements
    foreach ($ads as $index => $ad) {
        $item = [
            '@type' => 'ListItem',
            'position' => $index + 1,
            'item' => [
                '@type' => 'Product',
                'name' => $ad->title,
                'description' => Str::limit($ad->description, 150),
                'url' => route('store.show', $ad->slug)
            ]
        ];
        
        if ($ad->price) {
            $item['item']['offers'] = [
                '@type' => 'Offer',
                'price' => (string)$ad->price,
                'priceCurrency' => 'IRR'
            ];
        }
        
        $webPageSchema['mainEntity']['itemListElement'][] = $item;
    }
    
    // Build FAQ schema
    $faqSchema = null;
    if (count($faqs) > 0) {
        $faqSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => []
        ];
        
        foreach ($faqs as $faq) {
            $faqSchema['mainEntity'][] = [
                '@type' => 'Question',
                'name' => $faq['question'],
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => $faq['answer']
                ]
            ];
        }
    }
@endphp
<script type="application/ld+json">
{!! json_encode($webPageSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
</script>
@if($faqSchema)
<script type="application/ld+json">
{!! json_encode($faqSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
</script>
@endif
@endpush

@section('content')
<div class="container py-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb" style="background: rgba(255,255,255,0.05); padding: 12px 20px; border-radius: 12px;">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" style="color: #00f0ff; text-decoration: none;">خانه</a></li>
            <li class="breadcrumb-item active" style="color: #ffffff;">{{ $action }} {{ $typeInfo['label'] }}</li>
        </ol>
    </nav>

    <!-- H1 - Exact match with search query -->
    <h1 class="fw-bold mb-4" style="color: #ffffff; font-size: 2.5rem;">
        {{ $action }} {{ $typeInfo['label'] }}
    </h1>

    <!-- Intro Paragraph -->
    <div class="glass-card p-4 mb-5">
        <p class="mb-0" style="color: #e5e7eb; font-size: 1.1rem; line-height: 1.8;">
            {!! str_replace('{$label}', $typeInfo['label'], $content['intro']) !!}
        </p>
    </div>

    <!-- Benefits Section -->
    <div class="row mb-5">
        <div class="col-md-6 mb-4">
            <div class="glass-card p-4 h-100">
                <h2 class="fw-bold mb-4" style="color: #ffffff; font-size: 1.5rem;">
                    <i class="bi bi-check-circle me-2" style="color: #39ff14;"></i>
                    مزایای {{ $action }} {{ $typeInfo['label'] }}
                </h2>
                <ul class="list-unstyled">
                    @foreach($content['benefits'] as $benefit)
                    <li class="mb-3" style="color: #e5e7eb;">
                        <i class="bi bi-arrow-left me-2" style="color: #00f0ff;"></i>
                        {{ $benefit }}
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <!-- Guide Section -->
        <div class="col-md-6 mb-4">
            <div class="glass-card p-4 h-100">
                <h2 class="fw-bold mb-4" style="color: #ffffff; font-size: 1.5rem;">
                    <i class="bi bi-book me-2" style="color: #00f0ff;"></i>
                    راهنمای {{ $action }} {{ $typeInfo['label'] }}
                </h2>
                <ol class="ps-3">
                    @foreach($content['guide'] as $step)
                    <li class="mb-3" style="color: #e5e7eb;">
                        {!! str_replace('{$label}', $typeInfo['label'], $step) !!}
                    </li>
                    @endforeach
                </ol>
            </div>
        </div>
    </div>

    <!-- Trust Element -->
    <div class="glass-card p-4 mb-5" style="background: rgba(57, 255, 20, 0.1); border-color: rgba(57, 255, 20, 0.3);">
        <div class="d-flex align-items-start gap-3">
            <i class="bi bi-shield-check" style="color: #39ff14; font-size: 2rem;"></i>
            <div>
                <h3 class="fw-bold mb-2" style="color: #ffffff;">اطمینان و امنیت</h3>
                <p class="mb-0" style="color: #e5e7eb;">
                    {!! str_replace('{$label}', $typeInfo['label'], $content['trust']) !!}
                </p>
            </div>
        </div>
    </div>

    <!-- Ads Listing -->
    @if($ads->count() > 0)
    <div class="mb-5">
        <h2 class="fw-bold mb-4" style="color: #ffffff; font-size: 2rem;">
            لیست {{ $action }} {{ $typeInfo['label'] }}
        </h2>
        <div class="row g-4">
            @foreach($ads as $ad)
            <div class="col-md-6 col-lg-4">
                <div class="glass-card h-100" style="transition: all 0.3s;">
                    <a href="{{ route('store.show', $ad->slug) }}" class="text-decoration-none">
                        @if($ad->cover_image)
                        <img src="{{ $ad->cover_image }}" 
                             alt="{{ $ad->title }}"
                             class="w-100" 
                             style="height: 200px; object-fit: cover; border-radius: 20px 20px 0 0;"
                             loading="lazy">
                        @endif
                        <div class="p-4">
                            <h3 class="fw-bold mb-3" style="color: #ffffff; font-size: 1.25rem;">
                                {{ $ad->title }}
                            </h3>
                            <p class="text-muted mb-3" style="min-height: 60px;">
                                {{ Str::limit($ad->description, 100) }}
                            </p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge" style="background: rgba(0, 240, 255, 0.2); color: #00f0ff;">
                                    @if($ad->ad_type === 'instagram')
                                        <i class="bi bi-heart me-1"></i>
                                        {{ number_format($ad->key_metric ?? $ad->member_count ?? 0) }} فالوور
                                    @elseif($ad->ad_type === 'website')
                                        <i class="bi bi-eye me-1"></i>
                                        {{ number_format($ad->key_metric ?? $ad->member_count ?? 0) }} بازدید/ماه
                                    @elseif($ad->ad_type === 'youtube')
                                        <i class="bi bi-youtube me-1"></i>
                                        {{ number_format($ad->key_metric ?? $ad->member_count ?? 0) }} مشترک
                                    @elseif($ad->ad_type === 'domain')
                                        <i class="bi bi-globe me-1"></i>
                                        {{ $ad->key_metric ?? ($ad->ad_extra['domain_name'] ?? 'دامنه') }}
                                    @else
                                        <i class="bi bi-people me-1"></i>
                                        {{ number_format($ad->member_count ?? $ad->key_metric ?? 0) }} عضو
                                    @endif
                                </span>
                                @if($ad->price)
                                <span class="fw-bold" style="color: #ffffff;">
                                    {{ number_format($ad->price) }} تومان
                                </span>
                                @endif
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Related Pages - Internal Linking -->
    <div class="glass-card p-4 mb-5">
        <h2 class="fw-bold mb-4" style="color: #ffffff; font-size: 1.5rem;">
            <i class="bi bi-link-45deg me-2" style="color: #00f0ff;"></i>
            صفحات مرتبط
        </h2>
        <div class="row g-3">
            @foreach($relatedPages as $related)
            <div class="col-md-6">
                <a href="{{ $related['url'] }}" 
                   class="d-block p-3 glass-card text-decoration-none"
                   style="transition: all 0.3s;">
                    <h3 class="fw-bold mb-2" style="color: #00f0ff; font-size: 1.1rem;">
                        {{ $related['title'] }}
                    </h3>
                    <p class="mb-0 text-muted small">
                        {{ $related['description'] }}
                    </p>
                </a>
            </div>
            @endforeach
        </div>
    </div>

    <!-- FAQ Section -->
    @if(count($faqs) > 0)
    <div class="glass-card p-4">
        <h2 class="fw-bold mb-4" style="color: #ffffff; font-size: 1.5rem;">
            <i class="bi bi-question-circle me-2" style="color: #00f0ff;"></i>
            سوالات متداول
        </h2>
        <div class="accordion" id="faqAccordion">
            @foreach($faqs as $index => $faq)
            <div class="accordion-item mb-3" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 12px;">
                <h3 class="accordion-header">
                    <button class="accordion-button {{ $index > 0 ? 'collapsed' : '' }}" 
                            type="button" 
                            data-bs-toggle="collapse" 
                            data-bs-target="#faq{{ $index }}"
                            style="background: rgba(255,255,255,0.05); color: #ffffff; border: none;">
                        {{ $faq['question'] }}
                    </button>
                </h3>
                <div id="faq{{ $index }}" 
                     class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}"
                     data-bs-parent="#faqAccordion">
                    <div class="accordion-body" style="color: #e5e7eb;">
                        {{ $faq['answer'] }}
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- CTA Section -->
    <div class="text-center mt-5">
        @if($isBuy)
        <a href="{{ route('store.index') }}" class="btn btn-modern btn-lg">
            <i class="bi bi-search me-2"></i>
            مشاهده همه آگهی‌ها
        </a>
        @else
        <a href="{{ route('create-ad') }}" class="btn btn-modern btn-lg">
            <i class="bi bi-plus-circle me-2"></i>
            ثبت آگهی رایگان
        </a>
        @endif
    </div>
</div>
@endsection

