<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    protected $fillable = [
        'user_id',
        'price',
        'medications'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
