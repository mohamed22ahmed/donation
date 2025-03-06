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

         Medication::create([
            'name' => $request->name,
            'quantity' => $request->quantity,
            'price' => $request->price,
            'type' => $request->type,
            'status' => $request->status,
            'total' => $request->quantity * $request->price,
            'medication_img' => $imagePath,
            'user_id' => auth()->user()->id,
             'expiry_date' => $request->expiry_date
         ]);

        return redirect()->back();
    }

    public function update(Request $request)
    {
        $medication = Medication::find($request->id);
        $imagePath = 'medications/default.png';
        if ($request->hasFile('image')) {
            if (!str_contains($medication->medication_img, 'default.png') && Storage::disk('public')->exists($medication->medication_img)) {
                Storage::disk('public')->delete($medication->medication_img);
            }

            $imagePath = $request->file('image')->store('medications', 'public');
        }

        $medication->update([
            'name' => $request->name,
            'quantity' => $request->quantity,
            'price' => $request->price,
            'type' => $request->type,
            'status' => $request->status,
            'total' => $request->quantity * $request->price,
            'medication_img' => $imagePath,
            'user_id' => auth()->user()->id,
             'expiry_date' => $request->expiry_date
        ]);

        return redirect()->back();
    }

    public function delete(Request $request)
    {
        $medication = Medication::find($request->id);
        if (!str_contains($medication->medication_img, 'default.png') && Storage::disk('public')->exists($medication->medication_img)) {
            Storage::disk('public')->delete($medication->medication_img);
        }

        $medication->delete();
        return redirect()->back();
    }

    public static function mapMedication($medication): array
    {
        return [
            'id' => $medication['id'],
            'name' => $medication['name'],
            'quantity' => $medication['quantity'],
            'price' => $medication['price'],
            'total' => $medication['total'],
            'type' => $medication['type'],
            'status' => $medication['status'],
            'expiry_date' => $medication['expiry_date'],
            'image' => asset('storage/' . $medication['medication_img']) ?? asset('images/background.jpg'),
        ];
    }
}
