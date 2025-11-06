<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EquipmentHistory extends Model
{
    use HasFactory;

    protected $table = 'equipment_history';

    protected $fillable = [
        'equipment_id',
        'owner_id',
        'change_date',
        'action',
        'notes',
        'action_type',
        'performed_by_id',
    ];

    protected $casts = [
        'change_date' => 'date',
    ];

    public function equipment(): BelongsTo
    {
        return $this->belongsTo(Equipment::class);
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'owner_id');
    }

    public function performedBy(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'performed_by_id');
    }

    public function getActionTypeIconAttribute(): string
    {
        return match ($this->action_type) {
            'purchased' => 'shopping-cart',
            'assigned' => 'user-plus',
            'returned' => 'undo',
            'repaired' => 'wrench',
            'retired' => 'archive-box-x-mark',
            'note' => 'document-text',
            default => 'circle',
        };
    }

    public function getActionTypeColorAttribute(): string
    {
        return match ($this->action_type) {
            'purchased' => 'green',
            'assigned' => 'blue',
            'returned' => 'yellow',
            'repaired' => 'orange',
            'retired' => 'red',
            'note' => 'purple',
            default => 'gray',
        };
    }
}
