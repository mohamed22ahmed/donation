<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use Illuminate\Http\Request;
use Inertia\Response;

class OffersController extends Controller
{
    public function index(): Response
    {
        $offers = Offer::where('user_id', auth()->user()->id)->get();
        return inertia()->render('Offers/index', [
            'offers' => $offers,
        ]);
    }
}
