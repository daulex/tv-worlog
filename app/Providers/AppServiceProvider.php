<?php

namespace App\Providers;

use App\Models\Equipment;
use App\Models\EventParticipant;
use App\Models\Person;
use App\Observers\EquipmentObserver;
use App\Observers\EventParticipantObserver;
use App\Observers\PersonObserver;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\ViewErrorBag;
use Illuminate\View\View as ViewView;

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
        Person::observe(PersonObserver::class);
        EventParticipant::observe(EventParticipantObserver::class);

        // Set up morph map for polymorphic relationships
        Relation::morphMap([
            'equipment' => 'App\\Models\\Equipment',
            'person' => 'App\\Models\\Person',
            'event' => 'App\\Models\\Event',
            'vacancy' => 'App\\Models\\Vacancy',
            'client' => 'App\\Models\\Client',
        ]);

        // Register view namespace for layouts
        View::addNamespace('layouts', resource_path('views/components/layouts'));

        // Ensure $errors variable is always available for Flux UI components
        View::composer('*', function (ViewView $view) {
            if (! $view->offsetExists('errors')) {
                $view->with('errors', new ViewErrorBag);
            }
        });
    }
}
