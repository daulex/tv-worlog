<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Note extends Model
{
    use HasFactory;

    protected $fillable = [
        'note_type',
        'entity_id',
        'note_text',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function noteable(): MorphTo
    {
        return $this->morphTo('noteable', 'note_type', 'entity_id');
    }

    public function person()
    {
        return $this->belongsTo(Person::class, 'entity_id')->where('note_type', 'person');
    }

    public function event()
    {
        return $this->belongsTo(Event::class, 'entity_id')->where('note_type', 'event');
    }

    public function equipment()
    {
        return $this->belongsTo(Equipment::class, 'entity_id')->where('note_type', 'equipment');
    }

    public function vacancy()
    {
        return $this->belongsTo(Vacancy::class, 'entity_id')->where('note_type', 'vacancy');
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'entity_id')->where('note_type', 'client');
    }
}
