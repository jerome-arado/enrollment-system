<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'age',
        'address',
        'birthdate',
        'course',
        'year',
        'profile_picture',
        'status',
        'remarks',
    ];

    protected $casts = [
        'birthdate' => 'date',
    ];

    // ── Relationships ─────────────────────────────────────────
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function documents()
    {
        return $this->hasMany(EnrollmentDocument::class)->latest();
    }

    // ── Status helpers ────────────────────────────────────────
    public function isPending(): bool    { return $this->status === 'pending'; }
    public function isEnrolled(): bool   { return $this->status === 'enrolled'; }
    public function isDisapproved(): bool{ return $this->status === 'disapproved'; }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending'     => 'Pending Review',
            'enrolled'    => 'Enrolled',
            'disapproved' => 'Disapproved',
            default       => 'Unknown',
        };
    }

    public function getProfilePictureUrlAttribute(): string
    {
        if ($this->profile_picture) {
            return asset('storage/' . $this->profile_picture);
        }
        return '';
    }

    // ── Document helpers ──────────────────────────────────────
    public function hasDocument(string $label): bool
    {
        return $this->documents()->where('label', $label)->exists();
    }

    public function getDocumentCount(): int
    {
        return $this->documents()->count();
    }
}