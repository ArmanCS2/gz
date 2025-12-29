<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Livewire\Home;
use App\Livewire\Store\Index as StoreIndex;
use App\Livewire\Store\Show as StoreShow;
use App\Livewire\Auctions\Index as AuctionsIndex;
use App\Livewire\Auth\Login;
use App\Livewire\Panel\Dashboard as PanelDashboard;
use App\Livewire\Panel\Ads\Index as PanelAdsIndex;
use App\Livewire\Panel\Ads\Create as PanelAdsCreate;
use App\Livewire\CreateAdWizard;
use App\Livewire\Panel\Ads\Show as PanelAdsShow;
use App\Livewire\Panel\Ads\Edit as PanelAdsEdit;
use App\Livewire\Panel\Ads\Extend as PanelAdsExtend;
use App\Livewire\Panel\Ads\Bids as PanelAdsBids;
use App\Livewire\Panel\Auctions\Index as PanelAuctionsIndex;
use App\Livewire\Panel\Bids\Index as PanelBidsIndex;
use App\Livewire\Panel\Payments\Index as PanelPaymentsIndex;
use App\Livewire\Panel\Profile;
use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Livewire\Admin\Settings;
use App\Livewire\Admin\Rules\Index as AdminRulesIndex;
use App\Livewire\Admin\Rules\Edit as AdminRulesEdit;
use App\Livewire\Admin\Users\Index as AdminUsersIndex;
use App\Livewire\Admin\Users\Edit as AdminUsersEdit;
use App\Livewire\Admin\Ads\Index as AdminAdsIndex;
use App\Livewire\Admin\Ads\Edit as AdminAdsEdit;
use App\Livewire\Admin\Ads\Payments as AdminAdsPayments;
use App\Livewire\Admin\Auctions\Index as AdminAuctionsIndex;
use App\Livewire\Admin\Auctions\Bids as AdminAuctionsBids;
use App\Livewire\Admin\OTP\Index as AdminOTPIndex;
use App\Livewire\Admin\Reviews\Index as AdminReviewsIndex;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\BlogController;

// Public Routes
Route::get('/', Home::class)->name('home');
Route::get('/store', StoreIndex::class)->name('store.index');
Route::get('/store/{ad:slug}', StoreShow::class)->name('store.show');
Route::get('/auctions', AuctionsIndex::class)->name('auctions.index');

// SEO Landing Pages - Must be before blog routes to avoid conflicts
Route::get('/{action}/{type}', [App\Http\Controllers\SeoLandingController::class, 'landing'])
    ->where(['action' => 'خرید|فروش', 'type' => 'گروه-تلگرام|کانال-تلگرام|پیج-اینستاگرام|سایت-آماده|دامنه|کانال-یوتیوب'])
    ->name('seo.landing');

// Blog Routes
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/search', \App\Livewire\Blog\Search::class)->name('blog.search');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.post');
Route::get('/blog/category/{slug}', [BlogController::class, 'category'])->name('blog.category');
Route::get('/blog/tag/{slug}', [BlogController::class, 'tag'])->name('blog.tag');
Route::get('/blog/author/{id}', [BlogController::class, 'author'])->name('blog.author');

// Create Ad Wizard (requires auth)
Route::middleware('auth')->group(function () {
    Route::get('/create-ad', CreateAdWizard::class)->name('create-ad');
});

// Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', function () {
        return redirect()->route('home')->with('openLoginModal', true);
    })->name('login');
});

Route::post('/logout', function () {
    Auth::logout();
    return redirect()->route('home');
})->name('logout');

// User Panel Routes (requires authentication)
Route::prefix('panel')->name('panel.')->middleware('auth')->group(function () {
    Route::get('/', PanelDashboard::class)->name('dashboard');
    Route::get('/ads', PanelAdsIndex::class)->name('ads.index');
    Route::get('/ads/create', PanelAdsCreate::class)->name('ads.create');
    Route::get('/ads/{ad}', PanelAdsShow::class)->name('ads.show');
    Route::get('/ads/{ad}/edit', PanelAdsEdit::class)->name('ads.edit');
    Route::get('/ads/{ad}/extend', PanelAdsExtend::class)->name('ads.extend');
    Route::get('/ads/{ad}/bids', PanelAdsBids::class)->name('ads.bids');
    // Route::get('/auctions', PanelAuctionsIndex::class)->name('auctions.index'); // Removed - auctions are shown in ads.index
    Route::get('/bids', PanelBidsIndex::class)->name('bids.index');
    Route::get('/payments', PanelPaymentsIndex::class)->name('payments.index');
    Route::get('/profile', Profile::class)->name('profile');
});

// Admin Panel Routes (requires authentication and admin role)
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', AdminDashboard::class)->name('dashboard');
    Route::get('/settings', Settings::class)->name('settings');
    Route::get('/rules', AdminRulesIndex::class)->name('rules.index');
    Route::get('/rules/{rule}/edit', AdminRulesEdit::class)->name('rules.edit');
    Route::get('/users', AdminUsersIndex::class)->name('users.index');
    Route::get('/users/{user}/edit', AdminUsersEdit::class)->name('users.edit');
    Route::get('/ads', AdminAdsIndex::class)->name('ads.index');
    Route::get('/ads/create', \App\Livewire\Admin\Ads\Create::class)->name('ads.create');
    Route::get('/ads/{ad}/edit', \App\Livewire\Admin\Ads\Edit::class)->name('ads.edit');
    Route::get('/ads/{ad}/payments', AdminAdsPayments::class)->name('ads.payments');
    Route::get('/auctions', AdminAuctionsIndex::class)->name('auctions.index');
    Route::get('/auctions/{ad}/bids', AdminAuctionsBids::class)->name('auctions.bids');
    Route::get('/otp-logs', AdminOTPIndex::class)->name('otp.index');
    Route::get('/reviews', AdminReviewsIndex::class)->name('reviews.index');
    Route::get('/categories', \App\Livewire\Admin\Categories\Index::class)->name('categories.index');
    
    // Blog Management Routes
    Route::prefix('blog')->name('blog.')->group(function () {
        Route::get('/posts', \App\Livewire\Admin\Blog\Posts\Index::class)->name('posts.index');
        Route::get('/posts/create', \App\Livewire\Admin\Blog\Posts\Create::class)->name('posts.create');
        Route::get('/posts/{id}/edit', \App\Livewire\Admin\Blog\Posts\Edit::class)->name('posts.edit');
    });
});

// Payment Routes
Route::get('/payment/verify/{payment}', [PaymentController::class, 'verify'])->name('payment.verify');

// Sitemap Routes
Route::get('/sitemap.xml', [App\Http\Controllers\SitemapController::class, 'index'])->name('sitemap.index');
Route::get('/sitemap-pages.xml', [App\Http\Controllers\SitemapController::class, 'pages'])->name('sitemap.pages');
Route::get('/sitemap-ads.xml', [App\Http\Controllers\SitemapController::class, 'ads'])->name('sitemap.ads');
Route::get('/sitemap-blog.xml', [App\Http\Controllers\SitemapController::class, 'blog'])->name('sitemap.blog');
