<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php
        $siteSettings = \App\Models\SiteSetting::getSettings();
    @endphp
    <!-- Favicon -->
    @if($siteSettings->logo)
        <link rel="icon" type="image/png" href="{{ asset($siteSettings->logo) }}">
        <link rel="shortcut icon" type="image/png" href="{{ asset($siteSettings->logo) }}">
    @else
        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
        <link rel="shortcut icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    @endif
    <title>@yield('title', 'پنل کاربری - GroohBaz')</title>
    
    <!-- Vazir Font -->
    <link href="https://cdn.jsdelivr.net/gh/rastikerdar/vazirfont@v30.1.0/dist/font-face.css" rel="stylesheet">
    
    <!-- Bootstrap 5 RTL CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Jalali DatePicker -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@majidh1/jalalidatepicker@latest/dist/jalalidatepicker.min.css">
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/@majidh1/jalalidatepicker@latest/dist/jalalidatepicker.min.js"></script>
    
    <!-- GSAP Animations -->
    <script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js"></script>
    
    <!-- Toastify -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    
    @livewireStyles
    
    <style>
        /* Panel Background - Same as homepage */
        body {
            background: var(--bg-primary);
            color: var(--text-primary);
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
        }
        
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: 
                radial-gradient(circle at 20% 50%, rgba(176, 38, 255, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(0, 240, 255, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 40% 20%, rgba(255, 0, 110, 0.1) 0%, transparent 50%);
            animation: gradientShift 15s ease infinite;
            z-index: -1;
            pointer-events: none;
        }
        
        body::after {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: 
                radial-gradient(2px 2px at 20% 30%, rgba(255, 255, 255, 0.1), transparent),
                radial-gradient(2px 2px at 60% 70%, rgba(0, 240, 255, 0.1), transparent),
                radial-gradient(1px 1px at 50% 50%, rgba(176, 38, 255, 0.1), transparent),
                radial-gradient(1px 1px at 80% 10%, rgba(255, 0, 110, 0.1), transparent),
                radial-gradient(2px 2px at 90% 40%, rgba(57, 255, 20, 0.1), transparent);
            background-size: 200% 200%;
            animation: particleMove 20s linear infinite;
            z-index: -1;
            pointer-events: none;
        }
        
        .sidebar {
            min-height: calc(100vh - 56px);
            background: var(--glass-bg);
            backdrop-filter: blur(20px) saturate(180%);
            -webkit-backdrop-filter: blur(20px) saturate(180%);
            border-left: 1px solid var(--glass-border);
            border-radius: 0;
            box-shadow: 
                0 8px 32px rgba(0, 0, 0, 0.3),
                inset 0 1px 0 rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }
        
        .sidebar.collapsed {
            width: 80px !important;
            min-width: 80px;
        }
        
        .sidebar.collapsed .nav-link {
            padding: 12px !important;
            text-align: center;
        }
        
        .sidebar.collapsed .nav-link span {
            display: none !important;
        }
        
        .sidebar.collapsed .nav-link i {
            margin: 0 !important;
            font-size: 1.2rem;
        }
        
        .sidebar.collapsed #sidebarMenu h6 {
            display: none;
        }
        
        #mainContent {
            transition: all 0.3s ease;
        }
        
        .sidebar.collapsed ~ #mainContent {
            margin-right: 0;
            width: calc(100% - 80px);
        }
        
        @media (min-width: 992px) {
            .sidebar.collapsed ~ #mainContent {
                margin-right: 0;
            }
        }
        
        @media (max-width: 767.98px) {
            .sidebar {
                display: none !important;
            }
            
            #mainContent {
                width: 100% !important;
                margin-right: 0 !important;
            }
        }
        
        .nav-link {
            color: #e5e7eb !important;
            border-radius: 12px;
            transition: all 0.3s;
            padding: 12px 16px !important;
            margin-bottom: 8px;
            font-weight: 500;
        }
        
        .nav-link:hover {
            background: rgba(0, 240, 255, 0.1) !important;
            color: #ffffff !important;
            transform: translateX(-4px);
        }
        
        .nav-link.active {
            background: linear-gradient(135deg, rgba(0, 240, 255, 0.2), rgba(176, 38, 255, 0.2)) !important;
            color: #ffffff !important;
            box-shadow: 0 4px 15px rgba(0, 240, 255, 0.2);
            border: 1px solid rgba(0, 240, 255, 0.3);
        }
        
        .navbar-modern {
            background: var(--glass-bg) !important;
            backdrop-filter: blur(20px) saturate(180%);
            -webkit-backdrop-filter: blur(20px) saturate(180%);
            border-bottom: 1px solid var(--glass-border);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        }
        
        /* Panel Content Area */
        .col-md-9 {
            background: transparent;
        }
        
        /* All cards in panel should be glass */
        .panel-content .card,
        .col-md-9 .card {
            background: var(--glass-bg) !important;
            backdrop-filter: blur(20px) saturate(180%);
            -webkit-backdrop-filter: blur(20px) saturate(180%);
            border: 1px solid var(--glass-border) !important;
            border-radius: 20px !important;
            box-shadow: 
                0 8px 32px rgba(0, 0, 0, 0.3),
                inset 0 1px 0 rgba(255, 255, 255, 0.1);
            color: #ffffff !important;
        }
        
        .card-header {
            background: rgba(255, 255, 255, 0.05) !important;
            border-bottom: 1px solid var(--glass-border) !important;
            color: #ffffff !important;
            font-weight: 600;
        }
        
        .card-body {
            color: #e5e7eb !important;
        }
        
        /* Tables in panel - Force Dark Theme */
        .table,
        table.table {
            color: #ffffff !important;
            background: transparent !important;
            background-color: transparent !important;
            border-collapse: separate;
            border-spacing: 0;
        }
        
        .table thead th,
        table.table thead th,
        .table thead th[style],
        table.table thead th[style] {
            color: #ffffff !important;
            font-weight: 600 !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1) !important;
            background: rgba(255, 255, 255, 0.08) !important;
            background-color: rgba(255, 255, 255, 0.08) !important;
            padding: 16px !important;
            text-align: right;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .table tbody td,
        table.table tbody td,
        .table tbody td[style],
        table.table tbody td[style] {
            color: #ffffff !important;
            background: transparent !important;
            background-color: transparent !important;
            padding: 16px !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05) !important;
            vertical-align: middle;
        }
        
        .table tbody tr,
        table.table tbody tr {
            background: transparent !important;
            background-color: transparent !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05) !important;
            transition: all 0.3s ease;
        }
        
        .table tbody tr:hover,
        table.table tbody tr:hover {
            background: rgba(255, 255, 255, 0.05) !important;
            background-color: rgba(255, 255, 255, 0.05) !important;
            transform: translateX(-2px);
        }
        
        .table-striped > tbody > tr:nth-of-type(odd),
        table.table-striped > tbody > tr:nth-of-type(odd) {
            background: rgba(255, 255, 255, 0.02) !important;
            background-color: rgba(255, 255, 255, 0.02) !important;
        }
        
        .table-striped > tbody > tr:nth-of-type(odd):hover,
        table.table-striped > tbody > tr:nth-of-type(odd):hover {
            background: rgba(255, 255, 255, 0.08) !important;
            background-color: rgba(255, 255, 255, 0.08) !important;
        }
        
        .table-hover > tbody > tr:hover,
        table.table-hover > tbody > tr:hover {
            background: rgba(255, 255, 255, 0.05) !important;
            background-color: rgba(255, 255, 255, 0.05) !important;
        }
        
        .table-responsive {
            border-radius: 12px;
            overflow: hidden;
        }
        
        /* Override Bootstrap default table styles */
        .table td,
        .table th {
            border-color: rgba(255, 255, 255, 0.1) !important;
        }
        
        /* Force remove white backgrounds */
        .table tbody tr td,
        .table tbody tr th,
        table tbody tr td,
        table tbody tr th {
            background: transparent !important;
            background-color: transparent !important;
        }
        
        /* Badges */
        .badge {
            color: #ffffff !important;
            font-weight: 600;
        }
        
        /* Headings */
        h1, h2, h3, h4, h5, h6 {
            color: #ffffff !important;
        }
        
        /* Text muted */
        .text-muted {
            color: #9ca3af !important;
        }
        
        /* Form controls in panel */
        .form-control,
        .form-select,
        .modern-input,
        input[type="text"],
        input[type="number"],
        input[type="url"],
        input[type="time"],
        textarea {
            background: rgba(255, 255, 255, 0.05) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            color: #ffffff !important;
            border-radius: 12px;
        }
        
        /* Select specific styles */
        select,
        .form-select,
        select.form-control,
        select.modern-input {
            appearance: none !important;
            -webkit-appearance: none !important;
            -moz-appearance: none !important;
            background: rgba(255, 255, 255, 0.05) !important;
            background-color: rgba(255, 255, 255, 0.05) !important;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23ffffff' d='M6 9L1 4h10z'/%3E%3C/svg%3E") !important;
            background-repeat: no-repeat !important;
            background-position: right 12px center !important;
            background-size: 12px !important;
            padding-right: 40px !important;
            padding-left: 16px !important;
            cursor: pointer !important;
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
        
        .form-control:focus,
        .form-select:focus,
        .modern-input:focus,
        input[type="text"]:focus,
        input[type="number"]:focus,
        input[type="url"]:focus,
        input[type="time"]:focus,
        textarea:focus,
        select:focus {
            background: rgba(255, 255, 255, 0.08) !important;
            border-color: rgba(0, 240, 255, 0.5) !important;
            color: #ffffff !important;
            box-shadow: 0 0 0 3px rgba(0, 240, 255, 0.1) !important;
            outline: none !important;
        }
        
        select:hover,
        .form-select:hover {
            border-color: rgba(0, 240, 255, 0.3) !important;
            background-color: rgba(255, 255, 255, 0.07) !important;
        }
        
        .form-control:disabled,
        .modern-input:disabled,
        input:disabled,
        textarea:disabled {
            background: rgba(255, 255, 255, 0.03) !important;
            opacity: 0.6;
            cursor: not-allowed;
        }
        
        .form-label {
            color: #e5e7eb !important;
            font-weight: 500;
        }
        
        /* Input placeholder */
        .modern-input::placeholder,
        input::placeholder,
        textarea::placeholder {
            color: rgba(255, 255, 255, 0.3) !important;
            opacity: 1;
        }
        
        /* Input text color fix */
        .modern-input,
        input[type="text"],
        input[type="number"],
        input[type="url"],
        input[type="time"],
        textarea {
            color: #ffffff !important;
        }
        
        /* Input value color */
        .modern-input:not(:placeholder-shown),
        input:not(:placeholder-shown),
        textarea:not(:placeholder-shown) {
            color: #ffffff !important;
        }
        
        /* Checkbox and Switch */
        .form-check-input {
            background-color: rgba(255, 255, 255, 0.1) !important;
            border-color: rgba(255, 255, 255, 0.3) !important;
        }
        
        .form-check-input:checked {
            background-color: var(--neon-cyan) !important;
            border-color: var(--neon-cyan) !important;
        }
        
        .form-check-input:focus {
            border-color: var(--neon-cyan) !important;
            box-shadow: 0 0 0 3px rgba(0, 240, 255, 0.25) !important;
        }
        
        /* Input Group Text */
        .input-group-text {
            background: rgba(255, 255, 255, 0.05) !important;
            border-color: rgba(255, 255, 255, 0.1) !important;
            color: #ffffff !important;
        }
        
        /* Border bottom in lists */
        .border-bottom {
            border-color: rgba(255, 255, 255, 0.1) !important;
        }
        
        /* Links */
        a {
            color: #00f0ff;
            transition: all 0.3s;
        }
        
        a:hover {
            color: #b026ff;
        }
        
        /* Pagination */
        .pagination .page-link {
            background: rgba(255, 255, 255, 0.05) !important;
            border-color: rgba(255, 255, 255, 0.1) !important;
            color: #ffffff !important;
        }
        
        .pagination .page-link:hover {
            background: rgba(0, 240, 255, 0.2) !important;
            border-color: rgba(0, 240, 255, 0.3) !important;
            color: #ffffff !important;
        }
        
        .pagination .page-item.active .page-link {
            background: linear-gradient(135deg, #00f0ff, #b026ff) !important;
            border-color: #00f0ff !important;
            color: #ffffff !important;
        }
        /* Fix jalali datepicker z-index to appear above modals */
        /* Bootstrap modal z-index is 1055, so datepicker must be higher */
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
            z-index: 1060 !important;
        }
        /* Ensure datepicker appears above Bootstrap modal backdrop (z-index: 1050) */
        .modal-backdrop ~ .jdp-container,
        .modal-backdrop ~ div[class*="jdp"],
        .modal.show ~ .jdp-container,
        .modal.show ~ div[class*="jdp"] {
            z-index: 1060 !important;
        }
        /* Make all modals scrollable by default */
        .modal-dialog {
            max-height: calc(100% - 3.5rem);
        }
        .modal-dialog-scrollable .modal-content {
            max-height: 100%;
            overflow: hidden;
        }
        .modal-dialog-scrollable .modal-body {
            overflow-y: auto;
        }
        /* Force all modal-dialog to have scrollable behavior */
        .modal-dialog:not(.modal-dialog-scrollable) {
            max-height: calc(100% - 3.5rem);
        }
        .modal-dialog:not(.modal-dialog-scrollable) .modal-content {
            max-height: 100%;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }
        .modal-dialog:not(.modal-dialog-scrollable) .modal-body {
            overflow-y: auto;
            overflow-x: hidden;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-modern">
        <div class="container-fluid">
            <button class="btn btn-modern btn-sm d-md-none me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarOffcanvas" aria-controls="sidebarOffcanvas" style="background: rgba(255,255,255,0.1); box-shadow: none;">
                <i class="bi bi-list"></i>
            </button>
            <a class="navbar-brand" href="{{ route('panel.dashboard') }}" style="background: linear-gradient(135deg, #00f0ff, #b026ff); -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-weight: 700;">پنل کاربری</a>
            <div class="d-flex gap-2">
                <a href="{{ route('home') }}" class="btn btn-modern btn-sm" style="background: rgba(255,255,255,0.1); box-shadow: none;">
                    <i class="bi bi-house me-1"></i> <span class="d-none d-md-inline">بازگشت به سایت</span>
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-modern btn-sm" style="background: rgba(255,0,0,0.2); box-shadow: none;">
                        <i class="bi bi-box-arrow-left me-1"></i> <span class="d-none d-md-inline">خروج</span>
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Desktop Sidebar -->
            <div class="col-md-3 sidebar p-3 d-none d-md-block" id="desktopSidebar">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0 fw-bold" style="color: #ffffff;">منو</h6>
                    <button class="btn btn-sm d-none d-lg-block" type="button" id="toggleSidebar" style="background: rgba(255,255,255,0.1); color: #ffffff; border: none; padding: 4px 8px; border-radius: 6px;">
                        <i class="bi bi-chevron-right" id="sidebarIcon"></i>
                    </button>
                </div>
                <ul class="nav flex-column" id="sidebarMenu">
                    <li class="nav-item mb-2">
                        <a class="nav-link {{ request()->routeIs('panel.dashboard') ? 'active bg-primary text-white' : '' }}" href="{{ route('panel.dashboard') }}">
                            <i class="bi bi-speedometer2 me-2"></i> <span>داشبورد</span>
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a class="nav-link {{ request()->routeIs('panel.ads.*') ? 'active bg-primary text-white' : '' }}" href="{{ route('panel.ads.index') }}">
                            <i class="bi bi-card-list me-2"></i> <span>آگهی‌های من</span>
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a class="nav-link {{ request()->routeIs('panel.bids.*') ? 'active bg-primary text-white' : '' }}" href="{{ route('panel.bids.index') }}">
                            <i class="bi bi-gavel me-2"></i> <span>پیشنهادهای من</span>
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a class="nav-link {{ request()->routeIs('panel.payments.*') ? 'active bg-primary text-white' : '' }}" href="{{ route('panel.payments.index') }}">
                            <i class="bi bi-credit-card me-2"></i> <span>پرداخت‌ها</span>
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a class="nav-link {{ request()->routeIs('panel.profile') ? 'active bg-primary text-white' : '' }}" href="{{ route('panel.profile') }}">
                            <i class="bi bi-person me-2"></i> <span>پروفایل</span>
                        </a>
                    </li>
                </ul>
            </div>
            
            <!-- Mobile Offcanvas Sidebar -->
            <div class="offcanvas offcanvas-start" tabindex="-1" id="sidebarOffcanvas" aria-labelledby="sidebarOffcanvasLabel" style="background: var(--glass-bg); backdrop-filter: blur(20px) saturate(180%); -webkit-backdrop-filter: blur(20px) saturate(180%); border-right: 1px solid var(--glass-border);">
                <div class="offcanvas-header border-bottom" style="border-color: rgba(255,255,255,0.1);">
                    <h5 class="offcanvas-title fw-bold" id="sidebarOffcanvasLabel" style="color: #ffffff;">منو</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body p-3">
                    <ul class="nav flex-column">
                        <li class="nav-item mb-2">
                            <a class="nav-link {{ request()->routeIs('panel.dashboard') ? 'active bg-primary text-white' : '' }}" href="{{ route('panel.dashboard') }}" data-bs-dismiss="offcanvas">
                                <i class="bi bi-speedometer2"></i> داشبورد
                            </a>
                        </li>
                        <li class="nav-item mb-2">
                            <a class="nav-link {{ request()->routeIs('panel.ads.*') ? 'active bg-primary text-white' : '' }}" href="{{ route('panel.ads.index') }}" data-bs-dismiss="offcanvas">
                                <i class="bi bi-card-list"></i> آگهی‌های من
                            </a>
                        </li>
                        <li class="nav-item mb-2">
                            <a class="nav-link {{ request()->routeIs('panel.bids.*') ? 'active bg-primary text-white' : '' }}" href="{{ route('panel.bids.index') }}" data-bs-dismiss="offcanvas">
                                <i class="bi bi-gavel"></i> پیشنهادهای من
                            </a>
                        </li>
                        <li class="nav-item mb-2">
                            <a class="nav-link {{ request()->routeIs('panel.payments.*') ? 'active bg-primary text-white' : '' }}" href="{{ route('panel.payments.index') }}" data-bs-dismiss="offcanvas">
                                <i class="bi bi-credit-card"></i> پرداخت‌ها
                            </a>
                        </li>
                        <li class="nav-item mb-2">
                            <a class="nav-link {{ request()->routeIs('panel.profile') ? 'active bg-primary text-white' : '' }}" href="{{ route('panel.profile') }}" data-bs-dismiss="offcanvas">
                                <i class="bi bi-person"></i> پروفایل
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="col-md-9 col-lg-9 p-4 panel-content" id="mainContent">
                {{ $slot }}
            </div>
        </div>
    </div>

    @guest
        @livewire('auth.login')
    @endguest

    <!-- jQuery (Required for Persian DatePicker) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- Alpine.js (Required for Livewire 3) -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @livewireScripts
    
    <!-- Global Alerts System -->
    <script>
        // Bootstrap Toast Container
        if (!document.getElementById('toast-container')) {
            const toastContainer = document.createElement('div');
            toastContainer.id = 'toast-container';
            toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
            toastContainer.style.zIndex = '9999';
            document.body.appendChild(toastContainer);
        }

        // Show Bootstrap Toast
        window.showToast = function(message, type = 'info') {
            const toastContainer = document.getElementById('toast-container');
            const toastId = 'toast-' + Date.now();
            
            const bgClass = {
                'success': 'bg-success',
                'error': 'bg-danger',
                'warning': 'bg-warning',
                'info': 'bg-info'
            }[type] || 'bg-info';
            
            // Translate type to Persian
            const typeTranslations = {
                'success': 'موفقیت',
                'error': 'خطا',
                'warning': 'هشدار',
                'info': 'اطلاعات'
            };
            
            const typeLabel = typeTranslations[type] || typeTranslations['info'];
            
            const toastHtml = `
                <div id="${toastId}" class="toast ${bgClass} text-white" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="toast-header ${bgClass} text-white">
                        <strong class="me-auto">${typeLabel}</strong>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="بستن"></button>
                    </div>
                    <div class="toast-body">
                        ${message}
                    </div>
                </div>
            `;
            
            toastContainer.insertAdjacentHTML('beforeend', toastHtml);
            const toastElement = document.getElementById(toastId);
            const toast = new bootstrap.Toast(toastElement, { autohide: true, delay: 5000 });
            toast.show();
            
            toastElement.addEventListener('hidden.bs.toast', () => {
                toastElement.remove();
            });
        };

        // Show SweetAlert with Dark Theme
        window.showSwal = function(options) {
            if (typeof Swal === 'undefined') {
                console.error('SweetAlert2 is not loaded');
                return;
            }
            
            const defaultOptions = {
                confirmButtonText: 'تأیید',
                cancelButtonText: 'انصراف',
                confirmButtonColor: '#00f0ff',
                cancelButtonColor: '#ff006e',
                background: '#111118',
                color: '#ffffff',
                backdrop: 'rgba(0, 0, 0, 0.8)',
                customClass: {
                    popup: 'swal-dark-popup',
                    title: 'swal-dark-title',
                    content: 'swal-dark-content',
                    confirmButton: 'swal-dark-confirm',
                    cancelButton: 'swal-dark-cancel'
                }
            };
            
            return Swal.fire({ ...defaultOptions, ...options });
        };

        // Livewire Event Listeners
        document.addEventListener('livewire:init', () => {
            Livewire.on('showToast', (data) => {
                window.showToast(data[0].message, data[0].type || 'info');
            });
            
            Livewire.on('showSwal', (data) => {
                window.showSwal(data[0]);
            });
        });

        // Browser Event Listeners
        window.addEventListener('show-toast', (event) => {
            window.showToast(event.detail.message, event.detail.type || 'info');
        });

        window.addEventListener('show-swal', (event) => {
            window.showSwal(event.detail);
        });

        // Function to add scrollable class to modals
        function makeModalsScrollable() {
            document.querySelectorAll('.modal-dialog').forEach(dialog => {
                if (!dialog.classList.contains('modal-dialog-scrollable')) {
                    dialog.classList.add('modal-dialog-scrollable');
                }
            });
        }

        // Add modal-dialog-scrollable class to all modals on page load
        function initModalScrollable() {
            makeModalsScrollable();
            
            // Use MutationObserver to watch for new modals
            const observer = new MutationObserver(function(mutations) {
                makeModalsScrollable();
            });
            
            observer.observe(document.body, {
                childList: true,
                subtree: true
            });
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initModalScrollable);
        } else {
            initModalScrollable();
        }

        // Also add to dynamically created modals with Livewire
        document.addEventListener('livewire:init', () => {
            makeModalsScrollable();

            Livewire.hook('morph.updated', () => {
                setTimeout(makeModalsScrollable, 50);
            });
        });

        // Watch for modal show events and ensure class is applied
        document.addEventListener('show.bs.modal', function(e) {
            setTimeout(() => {
                const dialog = e.target.querySelector('.modal-dialog');
                if (dialog && !dialog.classList.contains('modal-dialog-scrollable')) {
                    dialog.classList.add('modal-dialog-scrollable');
                }
            }, 10);
        });
        
        // Sidebar Toggle Functionality
        document.addEventListener('DOMContentLoaded', function() {
            const toggleBtn = document.getElementById('toggleSidebar');
            const sidebar = document.getElementById('desktopSidebar');
            const mainContent = document.getElementById('mainContent');
            const sidebarIcon = document.getElementById('sidebarIcon');
            
            if (toggleBtn && sidebar) {
                // Load saved state from localStorage - Default is expanded (open)
                const savedState = localStorage.getItem('sidebarCollapsed');
                
                // Only collapse if explicitly set to 'true', otherwise keep expanded (default)
                if (savedState === 'true') {
                    sidebar.classList.add('collapsed');
                    if (sidebarIcon) {
                        sidebarIcon.classList.remove('bi-chevron-right');
                        sidebarIcon.classList.add('bi-chevron-left');
                    }
                } else {
                    // Default: sidebar is expanded (open)
                    sidebar.classList.remove('collapsed');
                    if (sidebarIcon) {
                        sidebarIcon.classList.remove('bi-chevron-left');
                        sidebarIcon.classList.add('bi-chevron-right');
                    }
                    // Set default state if not set
                    if (savedState === null) {
                        localStorage.setItem('sidebarCollapsed', 'false');
                    }
                }
                
                toggleBtn.addEventListener('click', function() {
                    sidebar.classList.toggle('collapsed');
                    
                    if (sidebar.classList.contains('collapsed')) {
                        if (sidebarIcon) {
                            sidebarIcon.classList.remove('bi-chevron-right');
                            sidebarIcon.classList.add('bi-chevron-left');
                        }
                        localStorage.setItem('sidebarCollapsed', 'true');
                    } else {
                        if (sidebarIcon) {
                            sidebarIcon.classList.remove('bi-chevron-left');
                            sidebarIcon.classList.add('bi-chevron-right');
                        }
                        localStorage.setItem('sidebarCollapsed', 'false');
                    }
                });
            }
        });
    </script>
</body>
</html>

