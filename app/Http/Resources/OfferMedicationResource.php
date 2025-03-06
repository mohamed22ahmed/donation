<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OfferMedicationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
          'id' => $this->id,
          'offer_id' => $this->offer_id,
          'medication_id' => $this->medication_id,
          'quantity' => $this->quantity,
          'price' => $this->price,
          'name' => $this->medication->name
        ];
    }
}
