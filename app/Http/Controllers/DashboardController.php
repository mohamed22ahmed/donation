<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use App\Models\Order;
use App\Models\Rating;
use Illuminate\Http\Request;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(): Response
    {
        return inertia()->render('Dashboard/index');
    }

    public function getOffers(Request $request)
    {
        $offers = Offer::with(['medications', 'user'])->where('offered', false)->orderBy('created_at', 'desc')->paginate(9);

        $offers->getCollection()->transform(function ($offer) {
            $offer->medications->each(function ($medication) {
                $medication->medication_img = asset('storage/' . $medication->medication_img) ?? asset('storage/medications/default.png');
            });

            return $offer;
        });

        return $offers;
    }

    public function getRatings(Request $request)
    {
        return Rating::with('orders')->paginate(9);
    }

    public function orderNow($offer_id)
    {
        $offer = Offer::find($offer_id)->with('medications')->first();
        $quantity = 0;
        foreach ($offer->medications as $medication) {
            $quantity += $medication->pivot->quantity;
        }

        $order = Order::create([
            'user_id' => auth()->user()->id,
            'offer_id' => $offer_id,
            'price' => $offer->price,
            'quantity' => $quantity,
            'active' => true,
            'status' => 'pending'
        ]);

        Offer::find($offer_id)->update([
            'offered' => true
        ]);

        return $order->id;
    }

    public function rateOrder(Request $request)
    {
        Rating::create([
            'user_id' => auth()->user()->id,
            'order_id' => $request->order_id,
            'degree' => $request->degree
        ]);
    }
}
