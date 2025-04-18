<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use Illuminate\Http\Request;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(): Response
    {
        $offers = Offer::with('medications')->paginate(12);
        $ratings = [];
        return inertia()->render('Dashboard/index', [
            'offers' => $offers,
            'ratings' => $ratings
        ]);
    }

    public function orderNow(Request $request)
    {
        dd($request->all);
    }
}
