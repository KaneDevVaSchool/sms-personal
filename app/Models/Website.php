<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Website extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'url',
        'status',
        'tech_stack',
        'cms',
        'ssl_expires_at',
        'resource_id',
    ];

    protected function casts(): array
    {
        return [
            'ssl_expires_at' => 'datetime',
        ];
    }

    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class);
    }

    public function environments(): HasMany
    {
        return $this->hasMany(WebsiteEnvironment::class);
    }
}
