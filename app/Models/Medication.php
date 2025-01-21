<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medication extends Model
{
    protected $fillable = [
        'name',
        'quantity',
        'price',
        'total',
        'type',
        'medication_img',
        'user_id',
    ];
}
