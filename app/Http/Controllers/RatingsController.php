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
        return Rating::where('user_id', auth()->user()->id)
            ->with('order')
            ->orderBy('created_at', 'desc')
            ->paginate(9);
    }

    public function deleteRating($id){
        Rating::findOrFail($id)->delete();
    }
}
