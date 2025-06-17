<?php

namespace App\Http\Controllers;

use App\Models\NotificationSearch;
use App\Models\Offer;
use App\Models\Order;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(): Response
    {
        return inertia()->render('Dashboard/index');
    }

    public function getOffers(Request $request)
    {
        $query = Offer::with(['medications', 'user'])
            ->where('offered', false)
            ->where('user_id', '!=', auth()->user()->id);

        if($request->has('search') && $request->search) {
            $searchTerm = $request->search;
            $query->whereHas('medications', function($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%');
            });
        }

        if($request->has('sort')) {
            $sort = $request->sort;
            if($sort == 'newest' || $sort == 'oldest') {
                $direction = ($sort == 'newest' ? 'desc' : 'asc');
                $query->orderBy('created_at', $direction);
            }else{
                $direction = $sort == 'price_high' ? 'desc' : 'asc';
                $query->orderBy('price', $direction);
            }
        }

        $offers = $query->paginate(10);

        if($request->has('search') && $request->search) {
            if(!$offers->total()) {
                NotificationSearch::create([
                    'user_id' => auth()->user()->id,
                    'search' => $request->search
                ]);
            }
        }
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
        return Rating::with(['order', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    }

    public function orderNow($offer_id)
    {
        $offer = Offer::find($offer_id)->with(['medications', 'user'])->first();
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

        if($order){
            $notification = new NotificationsController();
            $request = new Request();
            $request->merge([
                'user_id' => $offer->user_id,
                'message' => 'user: '.auth()->user()->name.', phone: '.auth()->user()->phone.' ordered the offer #'.$offer->id
            ]);
            $notification->sendNotification($request);
        }

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

    public function ask(Request $request): string
    {
        $baseUrl = 'http://localhost:11434';
        $response = Http::timeout(120)->post($baseUrl . '/api/generate', [
            'model' => 'mistral',
            'prompt' => "Answer very briefly and concisely. " . $request->question,
            'stream' => false,
            'max_tokens' => 100,
        ]);

        $response = $response->json();
        return $response['response'] ?? 'No reply from model.';
    }
}
