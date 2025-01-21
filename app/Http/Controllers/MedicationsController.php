<?php

namespace App\Http\Controllers;

use App\Models\Medication;
use Illuminate\Http\Request;
use Inertia\Response;

class MedicationsController extends Controller
{
    public function index(): Response
    {
        $medications = Medication::where('user_id', auth()->user()->id)->get()->map(function ($medication) {
            return [
                'id' => $medication['id'],
                'name' => $medication['name'],
                'quantity' => $medication['quantity'],
                'price' => $medication['price'],
                'total' => $medication['total'],
                'type' => $medication['type'],
                'image' => asset('images/1.jpg'),
//                'image' => asset('storage/' . $medication['medication_img']),
            ];
        });
        return inertia()->render('Medications/index', [
            'medications' => $medications
        ]);
    }

    public function store(Request $request)
    {
         $created = Medication::create([
            'name' => $request->name,
            'quantity' => $request->quantity,
            'price' => $request->price,
            'type' => $request->type,
            'total' => $request->quantity * $request->price,
            'medication_img' => 'background.jpg',
            'user_id' => auth()->user()->id
         ]);

        if ($created) {
            $medications = Medication::where('user_id', auth()->user()->id)->get()->map(function ($medication) {
                return [
                    'id' => $medication['id'],
                    'name' => $medication['name'],
                    'quantity' => $medication['quantity'],
                    'price' => $medication['price'],
                    'total' => $medication['total'],
                    'type' => $medication['type'],
                    'image' => asset('images/1.jpg'),
//                'image' => asset('storage/' . $medication['medication_img']),
                ];
            });

            return inertia()->render('Medications/index', [
                'medications' => $medications
            ])->with([
                'status' => 'success',
                'message' => 'Medication updated successfully'
            ]);
        }
    }

    public function update(Request $request)
    {
        $updated = Medication::find($request->id)->update([
            'name' => $request->name,
            'quantity' => $request->quantity,
            'price' => $request->price,
            'type' => $request->type,
            'total' => $request->quantity * $request->price,
            'medication_img' => 'background.jpg',
            'user_id' => auth()->user()->id
        ]);

        if ($updated) {
            $medications = Medication::where('user_id', auth()->user()->id)->get()->map(function ($medication) {
                return [
                    'id' => $medication['id'],
                    'name' => $medication['name'],
                    'quantity' => $medication['quantity'],
                    'price' => $medication['price'],
                    'total' => $medication['total'],
                    'type' => $medication['type'],
                    'image' => asset('images/1.jpg'),
//                'image' => asset('storage/' . $medication['medication_img']),
                ];
            });

            return inertia()->render('Medications/index', [
                'medications' => $medications
            ])->with([
                'status' => 'success',
                'message' => 'Medication updated successfully'
            ]);
        }
    }

    public function delete($id)
    {
        $deleted = true;
//        $deleted = Medication::where('id', $id)->delete();
        if ($deleted) {
            $medications = Medication::where('user_id', auth()->user()->id)->get()->map(function ($medication) {
                return [
                    'id' => $medication['id'],
                    'name' => $medication['name'],
                    'quantity' => $medication['quantity'],
                    'price' => $medication['price'],
                    'total' => $medication['total'],
                    'type' => $medication['type'],
                    'image' => asset('images/1.jpg'),
//                'image' => asset('storage/' . $medication['medication_img']),
                ];
            });

            return inertia()->render('Medications/index', [
                'medications' => $medications
            ])->with([
                'status' => 'success',
                'message' => 'Medication deleted successfully'
            ]);
        }
    }
}
