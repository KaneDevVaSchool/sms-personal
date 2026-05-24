<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KeyResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'objective_id',
        'title',
        'target',
        'current',
        'unit',
    ];

    protected function casts(): array
    {
        return [
            'target' => 'decimal:4',
            'current' => 'decimal:4',
        ];
    }

    public function objective(): BelongsTo
    {
        return $this->belongsTo(Objective::class);
    }

    public function checkins(): HasMany
    {
        return $this->hasMany(OkrCheckin::class);
    }
}
