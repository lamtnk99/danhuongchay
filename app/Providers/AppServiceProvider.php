<?php

namespace App\Providers;

use App\Models\Promotion;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

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
        Schema::defaultStringLength(191);

        View::composer('layouts.app', function ($view): void {
            $view->with('globalPopupPromotion', Promotion::current()
                ->where('placement', 'popup')
                ->orderBy('sort_order')
                ->latest()
                ->first());
        });
    }
}
