<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'action', 'loggable_id', 'loggable_type',
        'details', 'ip_address', 'user_agent'
    ];

    protected $casts = [
        'details' => 'array', // Store extra info as JSON
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Optional: If using polymorphic relation
    public function loggable(): MorphTo
    {
        return $this->morphTo();
    }
}
