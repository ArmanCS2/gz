<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php
        $siteSettings = \App\Models\SiteSetting::getSettings();
        $currentUrl = url()->current();
        $canonicalUrl = $currentUrl;
        $pageTitle = $siteSettings->site_name ?? 'خرید و فروش گروه و کانال های تلگرام';
        $pageDescription = $siteSettings->site_description ?? 'بزرگترین بازار خرید و فروش گروه‌های تلگرام با سیستم مزایده و فروش مستقیم';
        $pageImage = $siteSettings->logo ? asset($siteSettings->logo) : asset('favicon.ico');
    @endphp
    <title>@yield('title', $pageTitle)</title>
    <meta name="description" content="@yield('description', $pageDescription)">
    <link rel="canonical" href="@yield('canonical', $canonicalUrl)">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:url" content="@yield('og_url', $canonicalUrl)">
    <meta property="og:title" content="@yield('og_title', $pageTitle)">
    <meta property="og:description" content="@yield('og_description', $pageDescription)">
    <meta property="og:image" content="@yield('og_image', $pageImage)">
    <meta property="og:locale" content="fa_IR">
    
    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="@yield('twitter_url', $canonicalUrl)">
    <meta name="twitter:title" content="@yield('twitter_title', $pageTitle)">
    <meta name="twitter:description" content="@yield('twitter_description', $pageDescription)">
    <meta name="twitter:image" content="@yield('twitter_image', $pageImage)">
    
    @stack('meta')

    <!-- Preload Critical Resources -->
    <link rel="preload" href="https://cdn.jsdelivr.net/gh/rastikerdar/vazirfont@v30.1.0/dist/Vazir-Regular.woff2" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="https://cdn.jsdelivr.net/gh/rastikerdar/vazirfont@v30.1.0/dist/Vazir-Bold.woff2" as="font" type="font/woff2" crossorigin>
    @if($siteSettings->logo)
        <link rel="preload" href="{{ asset($siteSettings->logo) }}" as="image">
    @endif
    
    <!-- Vazir Font with font-display swap -->
    <link href="https://cdn.jsdelivr.net/gh/rastikerdar/vazirfont@v30.1.0/dist/font-face.css" rel="stylesheet" media="print" onload="this.media='all'">
    <noscript><link href="https://cdn.jsdelivr.net/gh/rastikerdar/vazirfont@v30.1.0/dist/font-face.css" rel="stylesheet"></noscript>
    
    <!-- Critical CSS Inline -->
    <style>
        @font-face{font-family:'Vazir';src:url('https://cdn.jsdelivr.net/gh/rastikerdar/vazirfont@v30.1.0/dist/Vazir-Regular.woff2') format('woff2');font-display:swap}
        @font-face{font-family:'Vazir';font-weight:bold;src:url('https://cdn.jsdelivr.net/gh/rastikerdar/vazirfont@v30.1.0/dist/Vazir-Bold.woff2') format('woff2');font-display:swap}
        :root{--bg-primary:#0a0a0f;--bg-secondary:#111118;--neon-cyan:#00f0ff;--neon-purple:#b026ff;--text-primary:#ffffff}
        body{background:var(--bg-primary);color:var(--text-primary);min-height:100vh;font-family:'Vazir','Tahoma',sans-serif;margin:0;padding:0}
        .navbar-modern{background:rgba(17,17,24,0.8)!important;backdrop-filter:blur(20px);border-bottom:1px solid rgba(255,255,255,0.1)}
        .glass-card{background:rgba(255,255,255,0.05);backdrop-filter:blur(20px);border:1px solid rgba(255,255,255,0.1);border-radius:20px}
        .btn-modern{background:linear-gradient(135deg,var(--neon-cyan),var(--neon-purple));border:none;border-radius:12px;padding:14px 32px;color:white;font-weight:600}
        .modern-input{background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.1);border-radius:12px;color:#ffffff;padding:14px 18px}
        [x-cloak]{display:none!important}
    </style>
    
    <!-- Non-Critical CSS Load Asynchronously -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet" media="print" onload="this.media='all'">
    <noscript><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet"></noscript>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" media="print" onload="this.media='all'">
    <noscript><link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css"></noscript>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@majidh1/jalalidatepicker@latest/dist/jalalidatepicker.min.css" media="print" onload="this.media='all'">
    <noscript><link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@majidh1/jalalidatepicker@latest/dist/jalalidatepicker.min.css"></noscript>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" media="print" onload="this.media='all'">
    <noscript><link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"></noscript>
    
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css" media="print" onload="this.media='all'">
    <noscript><link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css"></noscript>
    
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" media="print" onload="this.media='all'">
    <noscript><link rel="stylesheet" href="{{ asset('assets/css/style.css') }}"></noscript>
    
    @livewireStyles
    
    <style>
        /* Panel Dropdown Menu Styles */
        .dropdown-menu {
            z-index: 1050;
        }
        
        .dropdown-item {
            transition: all 0.3s ease;
        }
        
        .dropdown-item:hover {
            background: rgba(0, 240, 255, 0.15) !important;
            color: #00f0ff !important;
            transform: translateX(-4px);
        }
        
        .dropdown-item:active {
            background: rgba(0, 240, 255, 0.25) !important;
        }
        
        .dropdown-toggle::after {
            margin-right: 8px;
            margin-left: 0;
        }
        
        .dropdown-menu.show {
            animation: fadeInDown 0.3s ease;
        }
        
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Ensure dropdown works on mobile */
        @media (max-width: 991.98px) {
            .dropdown-menu {
                position: absolute !important;
                right: 0 !important;
                left: auto !important;
            }
        }
        
        /* Global Select Styles */
        select,
        .form-select,
        select.form-control,
        select.modern-input {
            appearance: none !important;
            -webkit-appearance: none !important;
            -moz-appearance: none !important;
            background: rgba(255, 255, 255, 0.05) !important;
            background-color: rgba(255, 255, 255, 0.05) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            border-radius: 12px !important;
            color: #ffffff !important;
            padding: 12px 40px 12px 16px !important;
            cursor: pointer !important;
            transition: all 0.3s ease !important;
            backdrop-filter: blur(10px) !important;
            -webkit-backdrop-filter: blur(10px) !important;
            font-size: 1rem !important;
            line-height: 1.5 !important;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23ffffff' d='M6 9L1 4h10z'/%3E%3C/svg%3E") !important;
            background-repeat: no-repeat !important;
            background-position: right 12px center !important;
            background-size: 12px !important;
            padding-right: 40px !important;
            padding-left: 16px !important;
        }
        
        select:focus,
        .form-select:focus,
        select.form-control:focus,
        select.modern-input:focus {
            outline: none !important;
            border-color: #00f0ff !important;
            box-shadow: 0 0 0 3px rgba(0, 240, 255, 0.1) !important;
            background-color: rgba(255, 255, 255, 0.08) !important;
        }
        
        select:hover,
        .form-select:hover,
        select.form-control:hover,
        select.modern-input:hover {
            border-color: rgba(0, 240, 255, 0.3) !important;
            background-color: rgba(255, 255, 255, 0.07) !important;
        }
        
        /* Comprehensive select option styling - Maximum priority */
        select option,
        .form-select option,
        select.form-control option,
        select.modern-input option,
        select[class*="modern"] option,
        select[class*="form"] option,
        select[class*="select"] option,
        option {
            background: #111118 !important;
            background-color: #111118 !important;
            color: #ffffff !important;
            padding: 12px 16px !important;
            border: none !important;
            font-size: 1rem !important;
        }
        
        select option:hover,
        .form-select option:hover,
        select.form-control option:hover,
        select.modern-input option:hover,
        select[class*="modern"] option:hover,
        select[class*="form"] option:hover,
        select[class*="select"] option:hover,
        option:hover {
            background: rgba(0, 240, 255, 0.25) !important;
            background-color: rgba(0, 240, 255, 0.25) !important;
            color: #00f0ff !important;
        }
        
        select option:checked,
        .form-select option:checked,
        select.form-control option:checked,
        select.modern-input option:checked,
        select[class*="modern"] option:checked,
        select[class*="form"] option:checked,
        select[class*="select"] option:checked,
        option:checked,
        select option:focus,
        .form-select option:focus,
        select.form-control option:focus,
        select.modern-input option:focus,
        select[class*="modern"] option:focus,
        select[class*="form"] option:focus,
        select[class*="select"] option:focus,
        option:focus {
            background: linear-gradient(135deg, rgba(0, 240, 255, 0.35), rgba(176, 38, 255, 0.35)) !important;
            background-color: rgba(0, 240, 255, 0.35) !important;
            color: #ffffff !important;
            font-weight: 600 !important;
        }
        
        select option:disabled,
        .form-select option:disabled,
        select.form-control option:disabled,
        select.modern-input option:disabled,
        option:disabled {
            background: rgba(255, 255, 255, 0.03) !important;
            background-color: rgba(255, 255, 255, 0.03) !important;
            color: rgba(255, 255, 255, 0.4) !important;
            opacity: 0.5 !important;
        }
        
        select:disabled,
        .form-select:disabled,
        select.form-control:disabled,
        select.modern-input:disabled {
            background: rgba(255, 255, 255, 0.03) !important;
            background-color: rgba(255, 255, 255, 0.03) !important;
            opacity: 0.6 !important;
            cursor: not-allowed !important;
        }
        
        /* Fix for select dropdown list */
        select::-ms-expand {
            display: none;
        }
        
        select:focus option,
        .form-select:focus option,
        select.form-control:focus option,
        select.modern-input:focus option {
            background: #111118 !important;
            background-color: #111118 !important;
            color: #ffffff !important;
        }
        
        /* Fix jalali datepicker z-index to appear above modals */
        .jdp-container,
        .jdp-wrapper,
        .datepicker-plot-area,
        [class*="jdp"],
        [id*="jdp"],
        div[class*="datepicker"],
        div[class*="jdp-container"],
        div[id*="jdp-container"] {
            z-index: 1060 !important;
        }
        
        /* Reset jalali datepicker select styles to use default datepicker styles */
        .jdp-container select,
        [class*="jdp"] select,
        [id*="jdp"] select,
        .jdp-container .jdp-year select,
        .jdp-container .jdp-month select,
        [class*="jdp"] .jdp-year select,
        [class*="jdp"] .jdp-month select {
            appearance: auto !important;
            -webkit-appearance: auto !important;
            -moz-appearance: auto !important;
            background: initial !important;
            background-color: initial !important;
            background-image: none !important;
            background-repeat: initial !important;
            background-position: initial !important;
            background-size: initial !important;
            padding: initial !important;
            padding-right: initial !important;
            padding-left: initial !important;
            border: initial !important;
            border-radius: initial !important;
            color: initial !important;
            font-size: initial !important;
            font-weight: initial !important;
            cursor: initial !important;
        }
        
        .jdp-container select option,
        [class*="jdp"] select option,
        [id*="jdp"] select option,
        .jdp-container .jdp-year select option,
        .jdp-container .jdp-month select option,
        [class*="jdp"] .jdp-year select option,
        [class*="jdp"] .jdp-month select option {
            background: initial !important;
            background-color: initial !important;
            color: initial !important;
            padding: initial !important;
            border: initial !important;
            font-size: initial !important;
            font-weight: initial !important;
        }
        
        .jdp-container select:hover,
        [class*="jdp"] select:hover,
        [id*="jdp"] select:hover,
        .jdp-container select:focus,
        [class*="jdp"] select:focus,
        [id*="jdp"] select:focus {
            background: initial !important;
            background-color: initial !important;
            border-color: initial !important;
            color: initial !important;
            box-shadow: initial !important;
            outline: initial !important;
        }
        
        .jdp-container select option:hover,
        [class*="jdp"] select option:hover,
        [id*="jdp"] select option:hover,
        .jdp-container select option:checked,
        [class*="jdp"] select option:checked,
        [id*="jdp"] select option:checked,
        .jdp-container select option:focus,
        [class*="jdp"] select option:focus,
        [id*="jdp"] select option:focus {
            background: initial !important;
            background-color: initial !important;
            color: initial !important;
            font-weight: initial !important;
        }
        
        /* Ensure datepicker appears above Bootstrap modal backdrop (z-index: 1050) */
        .modal-backdrop ~ .jdp-container,
        .modal-backdrop ~ div[class*="jdp"],
        .modal.show ~ .jdp-container,
        .modal.show ~ div[class*="jdp"] {
            z-index: 1060 !important;
        }
    </style>
</head>
<body>
    @php
        $siteSettings = \App\Models\SiteSetting::getSettings();
    @endphp
    <nav class="navbar navbar-expand-lg navbar-modern fixed-top">
        <div class="container">
            <a class="navbar-brand navbar-brand-modern d-flex align-items-center gap-2" href="{{ route('home') }}">
                @if($siteSettings->logo)
                    <img src="{{ asset($siteSettings->logo) }}" alt="{{ $siteSettings->site_name }}" width="40" height="40" style="height: 40px; width: auto;">
                @endif
                <span>{{ $siteSettings->site_name }}</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" style="border-color: rgba(255,255,255,0.3);">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link nav-link-modern" href="{{ route('store.index') }}">
                            <i class="bi bi-shop me-1"></i> فروشگاه
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-modern" href="{{ route('blog.index') }}">
                            <i class="bi bi-journal-text me-1"></i> بلاگ
                        </a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    @auth
                        <li class="nav-item dropdown">
                            <a class="nav-link nav-link-modern dropdown-toggle" href="#" id="panelDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-person-circle me-1"></i> پنل کاربری
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="panelDropdown" style="background: rgba(17, 17, 24, 0.95); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 12px; box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3); padding: 8px; min-width: 220px;">
                                <li>
                                    <a class="dropdown-item" href="{{ route('panel.dashboard') }}" style="color: #ffffff; padding: 10px 16px; border-radius: 8px; transition: all 0.3s;">
                                        <i class="bi bi-speedometer2 me-2" style="color: #00f0ff;"></i> داشبورد
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider" style="border-color: rgba(255,255,255,0.1); margin: 8px 0;"></li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('panel.ads.index') }}" style="color: #ffffff; padding: 10px 16px; border-radius: 8px; transition: all 0.3s;">
                                        <i class="bi bi-card-list me-2" style="color: #00f0ff;"></i> آگهی‌های من
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('panel.ads.create') }}" style="color: #ffffff; padding: 10px 16px; border-radius: 8px; transition: all 0.3s;">
                                        <i class="bi bi-plus-circle me-2" style="color: #00f0ff;"></i> ایجاد آگهی جدید
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider" style="border-color: rgba(255,255,255,0.1); margin: 8px 0;"></li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('panel.bids.index') }}" style="color: #ffffff; padding: 10px 16px; border-radius: 8px; transition: all 0.3s;">
                                        <i class="bi bi-gavel me-2" style="color: #ff006e;"></i> پیشنهادهای من
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('panel.payments.index') }}" style="color: #ffffff; padding: 10px 16px; border-radius: 8px; transition: all 0.3s;">
                                        <i class="bi bi-credit-card me-2" style="color: #b026ff;"></i> پرداخت‌ها
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider" style="border-color: rgba(255,255,255,0.1); margin: 8px 0;"></li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('panel.profile') }}" style="color: #ffffff; padding: 10px 16px; border-radius: 8px; transition: all 0.3s;">
                                        <i class="bi bi-person me-2" style="color: #39ff14;"></i> پروفایل
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider" style="border-color: rgba(255,255,255,0.1); margin: 8px 0;"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item" style="color: #ff006e; padding: 10px 16px; border-radius: 8px; transition: all 0.3s; background: none; border: none; width: 100%; text-align: right;">
                                            <i class="bi bi-box-arrow-left me-2"></i> خروج
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <button type="button" class="btn btn-link nav-link nav-link-modern border-0 p-0" data-bs-toggle="modal" data-bs-target="#loginModal">
                                <i class="bi bi-box-arrow-in-left me-1"></i> ورود / ثبت نام
                            </button>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <main class="py-4" style="padding-top: 80px !important;">
        @hasSection('content')
            @yield('content')
        @else
            {{ $slot ?? '' }}
        @endif
    </main>

    @guest
        @livewire('auth.login')
    @endguest

    @if(session('openLoginModal'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const modal = new bootstrap.Modal(document.getElementById('loginModal'));
                modal.show();
            });
        </script>
    @endif

    <footer class="text-white text-center py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-3 mb-md-0">
                    <div class="mb-3 d-flex align-items-center gap-2">
                        @if($siteSettings->logo)
                            <img src="{{ asset($siteSettings->logo) }}" alt="{{ $siteSettings->site_name }}" width="40" height="40" loading="lazy" style="height: 40px; width: auto;">
                        @endif
                        <h5 class="mb-0" style="background: linear-gradient(135deg, #00f0ff, #b026ff); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">{{ $siteSettings->site_name }}</h5>
                    </div>
                    <p class="text-muted small">خرید و فروش گروه‌های تلگرام از طریق مزایده و فروش مستقیم</p>
                </div>
                <div class="col-md-4 mb-3 mb-md-0">
                    <h6 class="mb-3">لینک‌های مفید</h6>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('store.index') }}" class="text-muted text-decoration-none">فروشگاه</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h6 class="mb-3">تماس با ما</h6>
                    <p class="text-muted small mb-2">پشتیبانی ۲۴/۷</p>
                    <a href="https://t.me/groohbaz_ir" target="_blank" rel="noopener noreferrer" class="text-decoration-none d-inline-flex align-items-center gap-2" style="color: #00f0ff !important;">
                        <i class="bi bi-telegram" style="font-size: 1.2rem;"></i>
                        <span>@groohbaz_ir</span>
                    </a>
                </div>
            </div>
            <hr class="my-4" style="border-color: rgba(255,255,255,0.1);">
            <p class="mb-0 text-muted small">&copy; {{ date('Y') }} {{ $siteSettings->site_name }}. تمامی حقوق محفوظ است.</p>
        </div>
    </footer>
    
    @auth
        @unless(request()->routeIs('create-ad') || request()->routeIs('panel.ads.create'))
            <a href="{{ route('create-ad') }}" class="fab" title="ثبت آگهی">
                <i class="bi bi-plus-lg fab-icon"></i>
                <span class="fab-text">ثبت آگهی</span>
            </a>
        @endunless
    @else
        @unless(request()->routeIs('create-ad') || request()->routeIs('panel.ads.create'))
            <button type="button" class="fab" data-bs-toggle="modal" data-bs-target="#loginModal" title="ثبت آگهی">
                <i class="bi bi-plus-lg fab-icon"></i>
                <span class="fab-text">ثبت آگهی</span>
            </button>
        @endunless
    @endauth

    <!-- Alpine.js (Required for Livewire 3) -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Bootstrap 5 JS -->
    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @livewireScripts
    
    <!-- Non-Critical JavaScript Load Asynchronously -->
    <script defer src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/@majidh1/jalalidatepicker@latest/dist/jalalidatepicker.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    
    <!-- Custom JavaScript -->
    <script defer src="{{ asset('assets/js/app.js') }}"></script>
    
    <!-- Fix: Ensure page starts at top when loading from Google/external sources -->
    <script>
        // Disable automatic scroll restoration
        if ('scrollRestoration' in history) {
            history.scrollRestoration = 'manual';
        }
        
        // Scroll to top on initial page load (if no hash in URL)
        (function() {
            // Check if there's a hash in URL - if yes, let browser handle it
            if (window.location.hash) {
                return;
            }
            
            // Scroll to top immediately (before page renders)
            window.scrollTo(0, 0);
            
            // Also scroll after DOM is ready
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', function() {
                    if (window.scrollY > 0 && !window.location.hash) {
                        window.scrollTo(0, 0);
                    }
                });
            } else {
                // DOM already loaded
                if (window.scrollY > 0 && !window.location.hash) {
                    window.scrollTo(0, 0);
                }
            }
            
            // Force scroll after a short delay to handle async content
            setTimeout(function() {
                if (window.scrollY > 0 && !window.location.hash) {
                    window.scrollTo(0, 0);
                }
            }, 10);
            
            // Handle window load event (all resources loaded)
            window.addEventListener('load', function() {
                if (window.scrollY > 0 && !window.location.hash) {
                    window.scrollTo(0, 0);
                }
            }, { once: true });
        })();
    </script>
    
    <!-- JSON-LD Structured Data -->
    <script type="application/ld+json">
    {!! json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'WebSite',
        'name' => $pageTitle,
        'description' => $pageDescription,
        'url' => url('/'),
        'potentialAction' => [
            '@type' => 'SearchAction',
            'target' => url('/store') . '?search={search_term_string}',
            'query-input' => 'required name=search_term_string'
        ]
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
    @stack('json-ld')
    
    <!-- Show login success message if exists -->
    @if(session('login_success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                setTimeout(() => {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            title: 'ورود موفق',
                            text: 'خوش آمدید! ورود شما با موفقیت انجام شد.',
                            icon: 'success',
                            confirmButtonText: 'باشه',
                            confirmButtonColor: '#00f0ff',
                            background: '#111118',
                            color: '#ffffff',
                            backdrop: 'rgba(0, 0, 0, 0.8)',
                            customClass: {
                                popup: 'swal-dark-popup',
                                title: 'swal-dark-title',
                                content: 'swal-dark-content',
                                confirmButton: 'swal-dark-confirm'
                            },
                            allowOutsideClick: false,
                            allowEscapeKey: false
                        });
                    }
                }, 500);
            });
        </script>
    @endif
</body>
</html>

