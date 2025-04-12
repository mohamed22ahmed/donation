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
        return inertia()->render('Orders/index', [
            'orders' => $this->getOrders(),
        ]);
    }

    public function getOrders(){
        return Order::where('user_id', auth()->user()->id)->with('offer')->get();
    }
}
