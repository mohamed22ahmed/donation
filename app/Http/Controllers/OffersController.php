<?php

namespace App\Http\Controllers;

use App\Http\Resources\OfferMedicationResource;
use App\Models\Medication;
use App\Models\Offer;
use App\Models\MedicationOffer;
use Illuminate\Http\Request;
use Inertia\Response;
use function Laravel\Prompts\select;

class OffersController extends Controller
{
    public function index(): Response
    {
        $offers = $this->getOffers();
        return inertia()->render('Offers/index', [
            'offers' => $offers,
        ]);
    }

    public function getOffers()
    {
        return Offer::where('user_id', auth()->user()->id)->get();
    }

    public function store(Request $request) {
        Offer::create([
            'id' => $request->offer_id,
            'user_id' => auth()->user()->id,
            'price' => $request->price
        ]);

        $this->subtractMedicationsQuantity($request->offer_id);
    }

    private function subtractMedicationsQuantity($offerId): void
    {
        $offerMedications = MedicationOffer::where('offer_id', $offerId)->get();

        foreach ($offerMedications as $offerMedication) {
            $medication = Medication::find($offerMedication->medication_id);
            $medication->quantity -= $offerMedication->quantity;
            $medication->total = $medication->quantity * $medication->price;
            $medication->save();
        }
    }

    private function addMedicationsQuantity($offerId): void
    {
        $offerMedications = MedicationOffer::where('offer_id', $offerId)->get();

        foreach ($offerMedications as $offerMedication) {
            $medication = Medication::find($offerMedication->medication_id);
            $medication->quantity += $offerMedication->quantity;
            $medication->total = $medication->quantity * $medication->price;
            $medication->save();
        }
    }

    public function getMedications($offerId)
    {
        $offerMedications = MedicationOffer::where('offer_id', $offerId)->select('medication_id')->get();

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

                if($medication->quantity == 0){
                    $selected = true;
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
        $offerMedications = MedicationOffer::where('offer_id', $offerId)->select('medication_id')->get();

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
        MedicationOffer::insert([
            'offer_id' => $request->offer_id,
            'medication_id' => $request->id,
            'quantity' => $request->quantity,
            'price' => $request->price
        ]);
    }

    public function updateMedications(Request $request)
    {
        MedicationOffer::find($request->offer_medication_id)
            ->update([
                'medication_id' => $request->id,
                'quantity' => $request->quantity,
                'price' => $request->price
            ]);
    }

    public function getOfferMedications($id)
    {
        $offer = Offer::where('id', $id)->with('medications')->get();
        return OfferMedicationResource::collection($offer);
    }

    public function deleteOfferMedication($id, $offerId){
        MedicationOffer::find($id)->delete();
    }

    public function delete($id){
        $this->addMedicationsQuantity($id);
        MedicationOffer::where('offer_id', $id)->delete();
        Offer::find($id)->delete();
    }
}
