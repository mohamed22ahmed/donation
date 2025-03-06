<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OfferMedication extends Model
{
    protected $table = 'offer_medication';
    protected $fillable = [
        'offer_id',
        'medication_id',
        'quantity',
        'price'
    ];

    public function offer()
    {
        return $this->belongsTo(Offer::class);
    }

    public function medication()
    {
        return $this->belongsTo(Medication::class);
    }
}
