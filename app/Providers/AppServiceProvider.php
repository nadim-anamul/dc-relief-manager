<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Carbon\Carbon;

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
        // Carbon locale based on app locale
        Carbon::setLocale(app()->getLocale());
        // Blade directives for Bangla numerals and money
        Blade::directive('bn', function ($expression) {
            return "<?= strtr($expression, ['0'=>'০','1'=>'১','2'=>'২','3'=>'৩','4'=>'৪','5'=>'৫','6'=>'৬','7'=>'৭','8'=>'৮','9'=>'৯']); ?>";
        });

        Blade::directive('moneybn', function ($expression) {
            return "<?php
                \$amount = (float)({$expression});
                if (\$amount >= 10000000) {
                    \$formatted = number_format(\$amount / 10000000, 2) . ' ' . 'কোটি';
                } elseif (\$amount >= 100000) {
                    \$formatted = number_format(\$amount / 100000, 2) . ' ' . 'লক্ষ';
                } else {
                    \$formatted = number_format(\$amount, 2);
                }
                echo '৳ ' . strtr(\$formatted, ['0'=>'০','1'=>'১','2'=>'২','3'=>'৩','4'=>'৪','5'=>'৫','6'=>'৬','7'=>'৭','8'=>'৮','9'=>'৯']);
            ?>";
        });
    }
}
