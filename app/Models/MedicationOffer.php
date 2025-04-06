<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicationOffer extends Model
{
    protected $fillable = [
        'offer_id',
        'medication_id',
        'quantity',
        'price'
    ];
}
