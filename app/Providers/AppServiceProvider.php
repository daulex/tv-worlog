<?php

namespace App\Providers;

use App\Models\Equipment;
use App\Observers\EquipmentObserver;
use Illuminate\Database\Eloquent\Relations\Relation;
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

        // Set up morph map for polymorphic relationships
        Relation::morphMap([
            'equipment' => 'App\\Models\\Equipment',
            'person' => 'App\\Models\\Person',
            'event' => 'App\\Models\\Event',
            'vacancy' => 'App\\Models\\Vacancy',
            'client' => 'App\\Models\\Client',
        ]);
    }
}
