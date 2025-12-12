<?php

namespace App\Providers;

use App\Models\Checklist;
use App\Models\ChecklistInstance;
use App\Models\Client;
use App\Models\Equipment;
use App\Models\EquipmentHistory;
use App\Models\Event;
use App\Models\EventParticipant;
use App\Models\File;
use App\Models\Note;
use App\Models\Person;
use App\Models\PersonHistory;
use App\Models\Vacancy;
use App\Policies\ChecklistInstancePolicy;
use App\Policies\ChecklistPolicy;
use App\Policies\ClientPolicy;
use App\Policies\EquipmentHistoryPolicy;
use App\Policies\EquipmentPolicy;
use App\Policies\EventParticipantPolicy;
use App\Policies\EventPolicy;
use App\Policies\FilePolicy;
use App\Policies\NotePolicy;
use App\Policies\PersonHistoryPolicy;
use App\Policies\PersonPolicy;
use App\Policies\VacancyPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Checklist::class => ChecklistPolicy::class,
        ChecklistInstance::class => ChecklistInstancePolicy::class,
        Client::class => ClientPolicy::class,
        Equipment::class => EquipmentPolicy::class,
        EquipmentHistory::class => EquipmentHistoryPolicy::class,
        Event::class => EventPolicy::class,
        EventParticipant::class => EventParticipantPolicy::class,
        File::class => FilePolicy::class,
        Note::class => NotePolicy::class,
        Person::class => PersonPolicy::class,
        PersonHistory::class => PersonHistoryPolicy::class,
        Vacancy::class => VacancyPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Define role-based gates
        Gate::define('admin', fn ($user) => $user->email === 'admin@example.com');
        Gate::define('hr', fn ($user) => in_array($user->email, [
            'hr@example.com',
            'admin@example.com',
        ]));
    }
}
