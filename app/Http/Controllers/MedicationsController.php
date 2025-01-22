<?php

namespace App\Http\Controllers;

use App\Models\Medication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Response;

class MedicationsController extends Controller
{
    public function index(): Response
    {
        $medications = Medication::where('user_id', auth()->user()->id)->get()->map(function ($medication) {
            return $this->mapMedication($medication);
        });
        return inertia()->render('Medications/index', [
            'medications' => $medications
        ]);
    }

    public function store(Request $request)
    {
        $imagePath = 'medications/default.png';
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('medications', 'public');
        }

         $medication = Medication::create([
            'name' => $request->name,
            'quantity' => $request->quantity,
            'price' => $request->price,
            'type' => $request->type,
            'total' => $request->quantity * $request->price,
            'medication_img' => $imagePath,
            'user_id' => auth()->user()->id
         ]);

        return redirect()->back();
    }

    public function update(Request $request)
    {
        $medication = Medication::find($request->id);
        $imagePath = 'medications/default.png';
        if ($request->hasFile('image')) {
            if (Storage::exists($medication->medication_img)) {
                Storage::delete($medication->medication_img);
            }

            $imagePath = $request->file('image')->store('medications', 'public');
        }

        $medication->update([
            'name' => $request->name,
            'quantity' => $request->quantity,
            'price' => $request->price,
            'type' => $request->type,
            'total' => $request->quantity * $request->price,
            'medication_img' => $imagePath,
            'user_id' => auth()->user()->id
        ]);

        return redirect()->back();
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

    private function mapMedication($medication): array
    {
        return [
            'id' => $medication['id'],
            'name' => $medication['name'],
            'quantity' => $medication['quantity'],
            'price' => $medication['price'],
            'total' => $medication['total'],
            'type' => $medication['type'],
            'image' => asset('storage/' . $medication['medication_img']) ?? asset('images/background.jpg'),
        ];
    }
}
