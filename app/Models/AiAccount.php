<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AiAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'service',
        'label',
        'api_key_encrypted',
        'api_key_hint',
        'quota_limit',
        'quota_used',
        'billing_date',
        'team_id',
        'project_id',
    ];

    protected function casts(): array
    {
        return [
            'api_key_encrypted' => 'encrypted',
            'quota_limit' => 'decimal:2',
            'quota_used' => 'decimal:2',
            'billing_date' => 'date',
        ];
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function usageLogs(): HasMany
    {
        return $this->hasMany(AiUsageLog::class);
    }

    public function alerts(): HasMany
    {
        return $this->hasMany(AiAlert::class);
    }
}
