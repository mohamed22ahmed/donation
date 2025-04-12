<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use Illuminate\Http\Request;
use Inertia\Response;

class RatingsController extends Controller
{
    public function index(): Response{
        return inertia()->render('Ratings/index', [
            'ratings' => $this->getRatings(),
        ]);
    }

    public function getRatings(){
        return Rating::where('user_id', auth()->user()->id)->with('orders')->get();
    }

    public function deleteRating($id){
        Rating::findOrFail($id)->delete();
    }
}
