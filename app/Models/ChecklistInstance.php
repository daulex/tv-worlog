<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChecklistInstance extends Model
{
    use HasFactory;

    protected $fillable = [
        'checklist_id',
        'person_id',
        'started_at',
        'completed_at',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function checklist(): BelongsTo
    {
        return $this->belongsTo(Checklist::class);
    }

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }

    public function itemInstances(): HasMany
    {
        return $this->hasMany(ChecklistItemInstance::class);
    }

    public function getProgressAttribute(): array
    {
        // Cache progress calculation for 5 minutes since it involves database queries
        $cacheKey = "checklist_instance_progress_{$this->id}";

        return \Cache::remember($cacheKey, 300, function () {
            // Eager load relationships if not already loaded
            if (! $this->relationLoaded('checklist')) {
                $this->load('checklist.items');
            }
            if (! $this->relationLoaded('itemInstances')) {
                $this->load('itemInstances');
            }

            $totalItems = $this->checklist->items->count();
            $completedItems = $this->itemInstances->filter(function ($itemInstance) {
                return $itemInstance->is_completed;
            })->count();

            return [
                'completed' => $completedItems,
                'total' => $totalItems,
                'percentage' => $totalItems > 0 ? round(($completedItems / $totalItems) * 100) : 0,
            ];
        });
    }

    public function getIsCompletedAttribute(): bool
    {
        return $this->completed_at !== null;
    }

    public function checkCompletion(): void
    {
        // Ensure we have fresh data
        $this->load(['checklist.items', 'itemInstances']);

        $allItems = $this->checklist->items;
        $allItemsCompleted = $allItems->every(function ($item) {
            $instance = $this->itemInstances->where('checklist_item_id', $item->id)->first();

            return $instance && $instance->is_completed;
        });

        $wasCompleted = $this->is_completed;

        if ($allItemsCompleted && ! $this->completed_at) {
            $this->update(['completed_at' => now()]);
        } elseif (! $allItemsCompleted && $this->completed_at) {
            $this->update(['completed_at' => null]);
        }

        // Clear progress cache if completion status changed
        if ($wasCompleted !== $this->fresh()->is_completed) {
            \Cache::forget("checklist_instance_progress_{$this->id}");
        }
    }
}
