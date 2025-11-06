<?php

namespace App\Providers;

use App\Models\Equipment;
use App\Observers\EquipmentObserver;
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
        Equipment::observe(EquipmentObserver::class);
    }
}
