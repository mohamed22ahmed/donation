<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Offer extends Model
{
    protected $fillable = [
        'user_id',
        'price',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function medications(): BelongsToMany
    {
        return $this->belongsToMany(Medication::class)
            ->withPivot('quantity', 'price');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
