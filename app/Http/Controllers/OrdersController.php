<?php

namespace App\Http\Controllers;

use App\Http\Resources\OfferMedicationResource;
use App\Models\Medication;
use App\Models\Offer;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Response;

class OrdersController extends Controller
{
    public function index(): Response
    {
        $orders = Order::where('user_id', auth()->user()->id)->get();
        return inertia()->render('Orders/index', [
            'orders' => $orders,
        ]);
    }

    public function store(Request $request)
    {
        $imagePath = 'medications/default.png';
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('medications', 'public');
        }

        Medication::create([
            'name' => $request->name,
            'quantity' => $request->quantity,
            'price' => $request->price,
            'type' => $request->type,
            'status' => $request->status,
            'total' => $request->quantity * $request->price,
            'medication_img' => $imagePath,
            'user_id' => auth()->user()->id,
            'expiry_date' => $request->expiry_date
        ]);

        return redirect()->back();
    }

    public function update(Request $request)
    {
        $medication = Medication::find($request->id);
        $imagePath = 'medications/default.png';
        if ($request->hasFile('image')) {
            if (!str_contains($medication->medication_img, 'default.png') && Storage::disk('public')->exists($medication->medication_img)) {
                Storage::disk('public')->delete($medication->medication_img);
            }

            $imagePath = $request->file('image')->store('medications', 'public');
        }

        $medication->update([
            'name' => $request->name,
            'quantity' => $request->quantity,
            'price' => $request->price,
            'type' => $request->type,
            'status' => $request->status,
            'total' => $request->quantity * $request->price,
            'medication_img' => $imagePath,
            'user_id' => auth()->user()->id,
            'expiry_date' => $request->expiry_date
        ]);

        return redirect()->back();
    }

    public function delete(Request $request)
    {
        $medication = Medication::find($request->id);
        if (!str_contains($medication->medication_img, 'default.png') && Storage::disk('public')->exists($medication->medication_img)) {
            Storage::disk('public')->delete($medication->medication_img);
        }

        $medication->delete();
        return redirect()->back();
    }

    public function getOffer($id)
    {
        $order = Order::with(['offer', 'offer.medications'])->find($id);
        dd($order);
        return [
            'offerMedications' => OfferMedicationResource::collection($offerMedications),
            'price' => $offerMedications->offer->price
        ];
    }
}
