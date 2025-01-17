<?php

namespace App\Http\Controllers;

use App\Models\Medication;
use Illuminate\Http\Request;
use Inertia\Response;

class MedicationsController extends Controller
{
    public function index(): Response
    {
        $medications = Medication::where('user_id', auth()->user()->id)->get();
        return inertia()->render('Medications/index', [
            'medications' => $medications
        ]);
    }

    public function store(Request $request)
    {
        dd('store function', $request->all());
    }

    public function update(Request $request, $id)
    {
        dd('update function', $request->all());
    }
}
