<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rating extends Model
{
    protected $fillable = [
        'user_id',
        'order_id',
        'degree',
    ];

    public function orders(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
