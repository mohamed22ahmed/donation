<?php

namespace App\Http\Controllers;

use App\Models\Offer;
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
        return Offer::with('medications')->paginate(9);
    }

    public function getRatings(Request $request)
    {
        return Rating::with('orders')->paginate(9);
    }

    public function orderNow(Request $request)
    {
        dd($request->all);
    }
}
