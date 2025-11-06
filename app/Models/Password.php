<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Password extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'password_hash',
        'password_salt',
        'last_changed',
    ];

    protected $casts = [
        'last_changed' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'user_id');
    }
}
