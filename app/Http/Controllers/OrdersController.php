<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrderResource;
use App\Models\Medication;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Response;

class OrdersController extends Controller
{
    public function index(): Response
    {
        $orders = Order::where('user_id', auth()->user()->id)->with('offer')->get();
        return inertia()->render('Orders/index', [
            'orders' => $orders,
        ]);
    }

    public function getOffer($id){
        dd(Order::with('offer')->find($id)->toArray());
        return Order::with('offer')->find($id)->toArray();
    }
}
