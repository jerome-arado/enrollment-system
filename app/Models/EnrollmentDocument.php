<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnrollmentDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'enrollment_id',
        'label',
        'original_name',
        'path',
        'mime_type',
        'size',
        'status',
        'remarks',
    ];

    // ── Relationships ─────────────────────────────────────────
    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class);
    }

    // ── Helpers ───────────────────────────────────────────────
    public function isPdf(): bool
    {
        return $this->mime_type === 'application/pdf';
    }

    public function isWord(): bool
    {
        return in_array($this->mime_type, [
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ]);
    }

    public function getIconAttribute(): string
    {
        if ($this->isPdf())  return '📄';
        if ($this->isWord()) return '📝';
        return '📎';
    }

    public function getFormattedSizeAttribute(): string
    {
        $kb = $this->size / 1024;
        if ($kb >= 1024) {
            return number_format($kb / 1024, 2) . ' MB';
        }
        return number_format($kb, 0) . ' KB';
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'approved' => 'Approved',
            'rejected' => 'Rejected',
            default    => 'Pending',
        };
    }

    public function getDownloadUrlAttribute(): string
    {
        return route('documents.download', $this->id);
    }
}