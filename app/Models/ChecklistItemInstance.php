<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChecklistItemInstance extends Model
{
    use HasFactory;

    protected $fillable = [
        'checklist_instance_id',
        'checklist_item_id',
        'value',
        'completed_at',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
    ];

    public function checklistInstance(): BelongsTo
    {
        return $this->belongsTo(ChecklistInstance::class);
    }

    public function checklistItem(): BelongsTo
    {
        return $this->belongsTo(ChecklistItem::class);
    }

    public function getIsCompletedAttribute(): bool
    {
        $item = $this->checklistItem;

        if (! $item) {
            return false;
        }

        switch ($item->type) {
            case 'bool':
                return $this->value === '1' || $this->value === 'true' || $this->value === true;
            case 'text':
            case 'number':
            case 'textarea':
                return ! empty(trim($this->value ?? ''));
            default:
                return false;
        }
    }

    protected static function booted(): void
    {
        static::saved(function ($instance) {
            // Update completion status without triggering saved event again
            $updates = [];
            if ($instance->is_completed && ! $instance->completed_at) {
                $updates['completed_at'] = now();
            } elseif (! $instance->is_completed && $instance->completed_at) {
                $updates['completed_at'] = null;
            }

            if (! empty($updates)) {
                $instance->updateQuietly($updates);
            }

            // Clear progress cache since item completion changed
            \Cache::forget("checklist_instance_progress_{$instance->checklist_instance_id}");

            // Check if the checklist instance should be completed
            // Use a short delay to avoid race conditions when multiple items are updated quickly
            \Cache::remember(
                "checklist_completion_check_{$instance->checklist_instance_id}",
                1, // 1 second
                function () use ($instance) {
                    $instance->checklistInstance->checkCompletion();

                    return true;
                }
            );
        });
    }
}
