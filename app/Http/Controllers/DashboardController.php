<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use App\Models\Order;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
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
            ->where('offered', false);

        if($request->has('search')) {
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

    public function ask(Request $request): string
    {
        $client = new \GuzzleHttp\Client();

        $response = $client->post('https://api-inference.huggingface.co/models/HuggingFaceH4/zephyr-7b-beta', [
            'headers' => [
                'Authorization' => 'Bearer '.env('HF_API_KEY'),
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'inputs' => "[Answer in 10 words or less] " . $request->question,
                'parameters' => [
                    'max_new_tokens' => 15,
                    'min_new_tokens' => 1,
                    'temperature' => 0.3,
                    'repetition_penalty' => 1.2,
                    'do_sample' => false,
                ],
                'options' => ['wait_for_model' => true]
            ],
            'timeout' => 30
        ]);

        $responseData = json_decode($response->getBody(), true);
        return $this->cleanResponse($responseData[0]['generated_text']);
    }

    private function cleanResponse(string $response): string
    {
        if (isset($responseData['error'])) {
            return "Error: ".$responseData['error'];
        }

        $response = Str::after($response, '<|assistant|>');
        return trim(preg_replace('/\s+/', ' ', $response));
    }
}
