<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OkrCheckin extends Model
{
    use HasFactory;

    protected $fillable = [
        'key_result_id',
        'value',
        'note',
        'checked_at',
        'user_id',
    ];

    protected function casts(): array
    {
        return [
            'value' => 'decimal:4',
            'checked_at' => 'datetime',
        ];
    }

    public function keyResult(): BelongsTo
    {
        return $this->belongsTo(KeyResult::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
