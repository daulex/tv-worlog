<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;

class Person extends Authenticatable
{
    use HasFactory, Notifiable, TwoFactorAuthenticatable;

    protected $fillable = [
        'status',
        'first_name',
        'last_name',
        'pers_code',
        'phone',
        'phone2',
        'email',
        'email2',
        'date_of_birth',
        'address',
        'starting_date',
        'last_working_date',
        'position',
        'client_id',
        'vacancy_id',
        'cv_id',
        'password',
    ];

    protected $hidden = [
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'remember_token',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'starting_date' => 'date',
        'last_working_date' => 'date',
        'email_verified_at' => 'datetime',
        'two_factor_confirmed_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function vacancy(): BelongsTo
    {
        return $this->belongsTo(Vacancy::class);
    }

    public function cv(): BelongsTo
    {
        return $this->belongsTo(CV::class, 'cv_id');
    }

    public function password(): HasOne
    {
        return $this->hasOne(Password::class, 'user_id');
    }

    public function equipment(): HasMany
    {
        return $this->hasMany(Equipment::class, 'current_holder_id');
    }

    public function equipmentHistory(): HasMany
    {
        return $this->hasMany(EquipmentHistory::class, 'holder_id');
    }

    public function events(): BelongsToMany
    {
        return $this->belongsToMany(Event::class, 'event_participants');
    }

    public function notes(): HasMany
    {
        return $this->hasMany(Note::class, 'entity_id')->where('note_type', 'person');
    }

    public function personHistory(): HasMany
    {
        return $this->hasMany(PersonHistory::class)->orderBy('change_date', 'desc')->orderBy('id', 'desc');
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getNameAttribute(): string
    {
        return $this->full_name;
    }

    public function initials(): string
    {
        return strtoupper(substr($this->first_name, 0, 1).substr($this->last_name, 0, 1));
    }
}
