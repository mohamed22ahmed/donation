<?php

namespace App\Http\Controllers;

use App\Mail\SendVerificationMessage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;

class VerificationController extends Controller
{
    public function index()
    {
        return Inertia::render('Verification/index', HomeController::returnedResponse('1.jpg'));
    }

    public function resend()
    {
        return Inertia::render('Verification/resend', HomeController::returnedResponse('1.jpg'));
    }

    public function resendPost(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if($user){
            $verification_code = rand(100000, 999999);

            $user->update([
                'verification_code' => $verification_code
            ]);

            Mail::to($user->email)->send(new SendVerificationMessage($user));
        }

        return redirect(route('verification.index'));
    }

    public function store(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if ($user->verification_code == $request->verification) {
            $user->update([
                'email_verified_at' => now()
            ]);
        }

        return redirect(route('login'));
    }
}
