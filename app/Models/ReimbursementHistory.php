<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReimbursementHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'reimbursement_id',
        'change_date',
        'action',
        'action_type',
        'notes',
        'performed_by_id',
    ];

    protected $casts = [
        'change_date' => 'datetime',
    ];

    public function reimbursement(): BelongsTo
    {
        return $this->belongsTo(Reimbursement::class);
    }

    public function performedBy(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'performed_by_id');
    }

    public function getActionTypeIconAttribute(): string
    {
        return match ($this->action_type) {
            'created' => 'plus',
            'updated' => 'pencil',
            'deleted' => 'trash',
            'note_added' => 'document-text',
            'comment_added' => 'chat-bubble-left',
            default => 'information-circle',
        };
    }

    public function getActionTypeColorAttribute(): string
    {
        return match ($this->action_type) {
            'created' => 'green',
            'updated' => 'blue',
            'deleted' => 'red',
            'note_added' => 'purple',
            'comment_added' => 'indigo',
            default => 'gray',
        };
    }
}
