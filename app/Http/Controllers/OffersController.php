<?php

namespace App\Http\Controllers;

use App\Http\Resources\OfferMedicationResource;
use App\Models\Medication;
use App\Models\Offer;
use App\Models\OfferMedication;
use Illuminate\Http\Request;
use Inertia\Response;
use function Laravel\Prompts\select;

class OffersController extends Controller
{
    public function index(): Response
    {
        $offers = Offer::where('user_id', auth()->user()->id)->get();
        return inertia()->render('Offers/index', [
            'offers' => $offers,
        ]);
    }

    public function getMedications($offerId)
    {
        $offerMedications = OfferMedication::where('offer_id', $offerId)->select('medication_id')->get();

        return Medication::where('user_id', auth()->user()->id)
            ->get()
            ->map(function ($medication) use ($offerMedications) {
                $selected = false;
                foreach ($offerMedications as $offerMedication) {
                    if ($offerMedication->medication_id == $medication->id) {
                        $selected = true;
                        break;
                    }
                }

                if (!$selected) {
                    return MedicationsController::mapMedication($medication);
                }
            })
            ->filter(function ($medication) {
                return !is_null($medication);
            })
            ->values()
            ->toArray();
    }

    public function getMedicationsForUpdate($offerId, $medicationId)
    {
        $offerMedications = OfferMedication::where('offer_id', $offerId)->select('medication_id')->get();

        return Medication::where('user_id', auth()->user()->id)
            ->get()
            ->map(function ($medication) use ($offerMedications, $medicationId) {
                $selected = false;
                foreach ($offerMedications as $offerMedication) {
                    if ($offerMedication->medication_id == $medication->id) {
                        if($offerMedication->medication_id != $medicationId){
                            $selected = true;
                        }
                        break;
                    }
                }

                if (!$selected) {
                    return MedicationsController::mapMedication($medication);
                }
            })
            ->filter(function ($medication) {
                return !is_null($medication);
            })
            ->values()
            ->toArray();
    }

    public function getNewId()
    {
        return Offer::query()->max('id') + 1;
    }

    public function saveMedications(Request $request)
    {
        OfferMedication::insert([
            'offer_id' => $request->offer_id,
            'medication_id' => $request->id,
            'quantity' => $request->quantity,
            'price' => $request->price
        ]);
        $offer = OfferMedication::where('offer_id', $request->offer_id)->latest()->first();
        return $this->getOfferMedications($offer->offer_id);
    }

    public function updateMedications(Request $request)
    {
        $offer = OfferMedication::find($request->offer_medication_id);
        $offer->update([
            'medication_id' => $request->id,
            'quantity' => $request->quantity,
            'price' => $request->price
        ]);

        return $this->getOfferMedications($offer->offer_id);
    }

    public function getOfferMedications($id)
    {
        $offerMedications = OfferMedication::where('offer_id', $id)->with('medication')->get();
        return OfferMedicationResource::collection($offerMedications);
    }

    public function deleteOfferMedication($id, $offerId){
        OfferMedication::find($id)->delete();
        return $this->getOfferMedications($offerId);
    }
}
