<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WebsiteEnvironment extends Model
{
    use HasFactory;

    protected $fillable = [
        'website_id',
        'name',
        'environment',
        'url',
    ];

    public function website(): BelongsTo
    {
        return $this->belongsTo(Website::class);
    }
}
