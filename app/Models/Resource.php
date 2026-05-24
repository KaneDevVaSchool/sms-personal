<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Resource extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'resource_type_id',
        'type',
        'url',
        'owner_team_id',
        'status',
        'expires_at',
        'cost_monthly',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
            'cost_monthly' => 'decimal:2',
        ];
    }

    public function resourceType(): BelongsTo
    {
        return $this->belongsTo(ResourceType::class);
    }

    public function ownerTeam(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'owner_team_id');
    }

    public function alerts(): HasMany
    {
        return $this->hasMany(ResourceAlert::class);
    }

    public function websites(): HasMany
    {
        return $this->hasMany(Website::class);
    }
}
