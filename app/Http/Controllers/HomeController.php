<?php

namespace App\Http\Controllers;

use App\Mail\SendContactUsMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Home/index', $this->returnedResponse('background.jpg'));
    }

    public function aboutUs(): Response
    {
        return Inertia::render('Home/about-us', $this->returnedResponse('2.jpg'));
    }

    public function contactUs(): Response
    {
        return Inertia::render('Home/contact-us', $this->returnedResponse('1.jpg'));
    }

    public function contactUsSubmit(Request $request): Response
    {
        Mail::to('mohamedahmed01119828317@gmail.com')->send(new SendContactUsMessage($request));

        return Inertia::render('Home/contact-us', $this->returnedResponse('1.jpg'));
    }

    public function dashboard(): Response
    {
        return Inertia::render('Dashboard');
    }

    private function returnedResponse($image): array
    {
        return [
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'background' => asset("images/$image"),
        ];
    }
}
