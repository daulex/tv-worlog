<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EquipmentHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'equipment_id',
        'owner_id',
        'change_date',
        'action',
        'notes',
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
}
