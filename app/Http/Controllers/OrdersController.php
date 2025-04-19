<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
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

    public function show($id){
        return Order::where('id', $id)->with(['offer', 'rating', 'offer.user'])->first();
    }

    public function getOrders(){
        return Order::where('user_id', auth()->user()->id)->with(['offer', 'rating', 'offer.user'])->get();
    }

    public function cancelOrder($id){
        Order::findOrFail($id)->update([
            'status' => OrderStatus::CANCELLED,
        ]);
    }

    public function deleteOrder($id){
        Order::findOrFail($id)->delete();
    }
}
