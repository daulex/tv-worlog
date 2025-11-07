<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PersonHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'person_id',
        'change_date',
        'action',
        'action_type',
        'notes',
        'performed_by_id',
    ];

    protected $casts = [
        'change_date' => 'datetime',
    ];

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }

    public function performedBy(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'performed_by_id');
    }

    public function getActionTypeIconAttribute(): string
    {
        return match ($this->action_type) {
            'profile_updated' => 'user',
            'equipment_assigned' => 'wrench-screwdriver',
            'equipment_returned' => 'arrow-path',
            'event_joined' => 'calendar',
            'event_left' => 'calendar-x-mark',
            'note_added' => 'document-text',
            'cv_updated' => 'document',
            'vacancy_assigned' => 'briefcase',
            'vacancy_removed' => 'briefcase-x-mark',
            default => 'information-circle',
        };
    }

    public function getActionTypeColorAttribute(): string
    {
        return match ($this->action_type) {
            'profile_updated' => 'blue',
            'equipment_assigned' => 'orange',
            'equipment_returned' => 'yellow',
            'event_joined' => 'green',
            'event_left' => 'red',
            'note_added' => 'blue',
            'cv_updated' => 'purple',
            'vacancy_assigned' => 'indigo',
            'vacancy_removed' => 'red',
            default => 'gray',
        };
    }
}
