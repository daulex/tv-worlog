<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Reimbursement extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'name',
        'amount',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function history(): HasMany
    {
        return $this->hasMany(ReimbursementHistory::class)->orderBy('change_date', 'desc')->orderBy('id', 'desc');
    }

    public function notes(): HasMany
    {
        return $this->hasMany(Note::class, 'entity_id')->where('note_type', 'reimbursement');
    }
}
