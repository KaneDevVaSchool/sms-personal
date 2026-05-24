<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiAlert extends Model
{
    use HasFactory;

    protected $fillable = [
        'ai_account_id',
        'alert_type',
        'sent_at',
    ];

    protected function casts(): array
    {
        return [
            'sent_at' => 'datetime',
        ];
    }

    public function aiAccount(): BelongsTo
    {
        return $this->belongsTo(AiAccount::class);
    }
}
