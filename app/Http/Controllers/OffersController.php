<?php

namespace App\Http\Controllers;

use App\Http\Resources\OfferMedicationResource;
use App\Http\Resources\OfferResource;
use App\Models\Medication;
use App\Models\Notification;
use App\Models\NotificationSearch;
use App\Models\Offer;
use App\Models\MedicationOffer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
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
        $offers = Offer::where('user_id', auth()->user()->id)
            ->where('active', true)
            ->with('medications')
            ->get();

        return OfferResource::collection($offers);
    }

    public function store(Request $request) {
        if(MedicationOffer::where('offer_id', $request->offer_id)->count() != 0) {
            Offer::find($request->offer_id)
                ->update([
                    'price' => $request->price,
                    'active' => true
                ]);

            $offer = Offer::where('id', (int)$request->offer_id)->with('medications')->first();
            $this->searchInOffersAboutNotificationsSearch($offer);
        }
    }

    private function searchInOffersAboutNotificationsSearch($offer){
        $searches = NotificationSearch::where('user_id', '!=', auth()->user()->id)->get();
        $searchesToNotify = [];
        foreach($searches as $search) {
            foreach ($offer->medications as $med) {
                if (str_contains($med->name, $search->search)){
                    $searchesToNotify[] = [
                        'id' => $search->id,
                        'user_id' => $search->user_id,
                        'search' => $search->search
                    ];
                }
            }
        }

        $this->sendNotificationsToUsers($searchesToNotify);
    }

    private function sendNotificationsToUsers($searchesToNotify){
        foreach ($searchesToNotify as $item) {
            $message = "there's an offer for the medicine #".$item['search']." you searched for before";
            $id = Notification::max('id');
            $id = $id ? $id + 1 : 1;
            $response = Http::post('http://localhost:3000/emit', [
                'event' => 'notification',
                'data' => ['id'=> $id, 'message' => $message, 'read'=> 0],
                'userId' => $item['user_id'],
            ]);

            if ($response->successful()) {
                Notification::create([
                    'message' => $message,
                    'user_id' => $item['user_id']
                ]);

                NotificationSearch::find($item['id'])->delete();
            }
        }
    }

    private function subtractMedicationsQuantity($offerId): void
    {
        $offerMedications = MedicationOffer::where('offer_id', $offerId)->get();

        foreach ($offerMedications as $offerMedication) {
            $this->updateMedicationQuantityAndTotal($offerMedication->medication_id, $offerMedication->quantity, 'subtract');
        }
    }

    private function updateMedicationQuantityAndTotal($id, $quantity, $addOrSubtract = 'add')
    {
        $medication = Medication::find($id);
        $medication->quantity = $addOrSubtract == 'subtract' ?
            $medication->quantity - $quantity :
            $medication->quantity + $quantity;
        $medication->total = $medication->quantity * $medication->price;
        $medication->save();
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
        $med = Medication::find($medicationId);
        $offerMedications = MedicationOffer::where('offer_id', $offerId)
            ->select('medication_id', 'quantity')
            ->get()
            ->keyBy('medication_id');

        return Medication::where('user_id', auth()->user()->id)
            ->get()
            ->map(function ($medication) use ($offerMedications, $medicationId, $med) {
                $isInOffer = $offerMedications->has($medication->id) &&
                    $medication->id != $medicationId;

                if (!$isInOffer) {
                    $mappedMedication = MedicationsController::mapMedication($medication);

                    if ($offerMedications->has($medication->id)) {
                        $mappedMedication['quantity'] = $offerMedications[$medication->id]->quantity + $med->quantity;
                    }

                    return $mappedMedication;                }
            })
            ->filter(function ($medication) {
                return !is_null($medication) && $medication['quantity'] != 0;
            })
            ->values()
            ->toArray();
    }

    public function getNewId()
    {
        $lastId = Offer::where('user_id', auth()->user()->id)->orderBy('id', 'desc')->first();
        if($lastId == null){
            Offer::insert([
                'id' => 1,
                'user_id' => auth()->user()->id,
                'price' => 0
            ]);
            return 1;
        }
        if($lastId?->active == false){
            return $lastId?->id;
        }

        if(MedicationOffer::where('offer_id', $lastId?->id)->count() != 0){
            Offer::insert([
                'user_id' => auth()->user()->id,
                'price' => 0
            ]);
            return $lastId->id + 1;
        }
        return $lastId->id;
    }

    public function saveMedications(Request $request)
    {
        $this->updateMedicationQuantityAndTotal($request->id, $request->quantity, 'subtract');

        MedicationOffer::insert([
            'offer_id' => $request->offer_id,
            'medication_id' => $request->id,
            'quantity' => $request->quantity,
            'price' => $request->price
        ]);
    }

    public function updateMedications(Request $request)
    {
        $medicationOffer = MedicationOffer::find($request->relation_id);
        if($medicationOffer != null){
            $this->updateMedicationQuantityAndTotal($medicationOffer->medication_id, $medicationOffer->quantity);
            MedicationOffer::find($request->relation_id)
                ->update([
                    'medication_id' => $request->id,
                    'quantity' => $request->quantity,
                    'price' => $request->price
                ]);
            $this->updateMedicationQuantityAndTotal($request->id, $request->quantity, 'subtract');
        }
    }

    public function getOfferMedications($id)
    {
        if(!$id)
            return 'no data';
        $offer = Offer::where('id', $id)
            ->with(['medications' => function ($query) {
                $query->withPivot('id');
            }])
            ->get();

        return OfferMedicationResource::collection($offer);
    }

    public function deleteOfferMedication($id){
        $offerMedication = MedicationOffer::find($id);
        $this->updateMedicationQuantityAndTotal($offerMedication->medication_id, $offerMedication->quantity);
        $offerMedication->delete();
    }

    public function delete($id){
        $this->addMedicationsQuantity($id);
        MedicationOffer::where('offer_id', $id)->delete();
        Offer::find($id)->delete();
    }

    private function addMedicationsQuantity($offerId): void
    {
        MedicationOffer::where('offer_id', $offerId)
            ->get()
            ->each(function ($offerMedication) {
                $this->updateMedicationQuantityAndTotal($offerMedication->medication_id, $offerMedication->quantity);
            });
    }
}
