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
        'status',
        'medication_img',
        'user_id',
        'expiry_date'
    ];

    public function offerMedications()
    {
        return $this->hasMany(OfferMedication::class);
    }
}
