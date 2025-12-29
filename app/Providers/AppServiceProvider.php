<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use App\Helpers\DateHelper;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // استفاده از Bootstrap 5 برای pagination
        Paginator::useBootstrapFive();

        // Register DateHelper for Blade views
        Blade::directive('persianDate', function ($expression) {
            return "<?php echo \App\Helpers\DateHelper::toPersianDate($expression); ?>";
        });

        Blade::directive('persianDateTime', function ($expression) {
            return "<?php echo \App\Helpers\DateHelper::toPersianDateTime($expression); ?>";
        });

        Blade::directive('persianDiff', function ($expression) {
            return "<?php echo \App\Helpers\DateHelper::diffForHumans($expression); ?>";
        });
    }
}
