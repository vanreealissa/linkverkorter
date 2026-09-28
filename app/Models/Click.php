<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Click extends Model
{
    public const UPDATED_AT = null;

    protected $fillable = ['referrer_host'];

    public function link(): BelongsTo
    {
        return $this->belongsTo(Link::class);
    }
}
