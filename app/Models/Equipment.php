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
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'purchase_price' => 'decimal:2',
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
        return $this->morphMany(Note::class, 'notable');
    }
}
