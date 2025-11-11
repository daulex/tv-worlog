<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class File extends Model
{
    use HasFactory;

    protected $fillable = [
        'person_id',
        'filename',
        'file_path',
        'file_type',
        'file_size',
        'file_category',
        'description',
        'uploaded_at',
    ];

    protected $casts = [
        'uploaded_at' => 'datetime',
        'file_size' => 'integer',
    ];

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }

    public function getFileSizeFormattedAttribute(): string
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];

        for ($i = 0; $bytes >= 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2).' '.$units[$i];
    }

    public function getFileIconAttribute(): string
    {
        return match ($this->file_type) {
            'application/pdf' => '📄',
            'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => '📝',
            'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => '📊',
            'image/jpeg', 'image/png', 'image/gif' => '🖼️',
            default => '📎',
        };
    }

    public function isImage(): bool
    {
        return str_starts_with($this->file_type, 'image/');
    }

    public function isPdf(): bool
    {
        return $this->file_type === 'application/pdf';
    }

    public function getDownloadUrlAttribute(): string
    {
        return route('files.download', $this->id);
    }

    public function getPublicUrlAttribute(): ?string
    {
        return Storage::url($this->file_path);
    }

    public function scopeForPerson($query, $personId)
    {
        return $query->where('person_id', $personId);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('file_category', $category);
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('uploaded_at', 'desc');
    }

    public function canBeDeletedBy($user): bool
    {
        return true; // For now, all authenticated users can delete files
    }

    public function canBeDownloadedBy($user): bool
    {
        return true; // For now, all authenticated users can download files
    }
}
