<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Contract extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'partner',
        'type',
        'value',
        'signed_at',
        'expires_at',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'value' => 'decimal:2',
            'signed_at' => 'date',
            'expires_at' => 'date',
        ];
    }

    public function files(): HasMany
    {
        return $this->hasMany(ContractFile::class);
    }

    public function alerts(): HasMany
    {
        return $this->hasMany(ContractAlert::class);
    }
}
