<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiUsageLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'ai_account_id',
        'amount',
        'unit',
        'metadata',
        'logged_at',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:4',
            'metadata' => 'array',
            'logged_at' => 'datetime',
        ];
    }

    public function aiAccount(): BelongsTo
    {
        return $this->belongsTo(AiAccount::class);
    }
}
