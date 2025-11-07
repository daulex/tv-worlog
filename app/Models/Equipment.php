<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Equipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'brand',
        'model',
        'serial',
        'purchase_date',
        'purchase_price',
        'current_owner_id',
        'retired_at',
        'retirement_notes',
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'purchase_price' => 'decimal:2',
        'retired_at' => 'datetime',
    ];

    public function currentOwner(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'current_owner_id');
    }

    public function equipmentHistory(): HasMany
    {
        return $this->hasMany(EquipmentHistory::class);
    }

    public function notes(): MorphMany
    {
        return $this->morphMany(Note::class, 'noteable', 'note_type', 'entity_id');
    }

    public function isRetired(): bool
    {
        return ! is_null($this->retired_at);
    }

    public function retire(?string $notes = null): void
    {
        $this->update([
            'retired_at' => now(),
            'retirement_notes' => $notes,
            'current_owner_id' => null,
        ]);

        // Create retirement history record
        $this->equipmentHistory()->create([
            'owner_id' => null,
            'change_date' => now(),
            'action' => 'Equipment retired',
            'action_type' => 'retired',
            'notes' => $notes ?? 'Equipment was retired from service',
            'performed_by_id' => auth()->id(),
        ]);
    }

    public function unretire(): void
    {
        $this->update([
            'retired_at' => null,
            'retirement_notes' => null,
        ]);

        // Create unretirement history record
        $this->equipmentHistory()->create([
            'owner_id' => $this->current_owner_id,
            'change_date' => now(),
            'action' => 'Equipment returned to service',
            'action_type' => 'purchased',
            'notes' => 'Equipment was returned to active service',
            'performed_by_id' => auth()->id(),
        ]);
    }
}
