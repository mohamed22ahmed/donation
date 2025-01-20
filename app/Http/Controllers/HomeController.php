<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Home/index', [
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'background' => asset('images/background.jpg'),
        ]);
    }

    public function aboutUs(): Response
    {
        return Inertia::render('Home/about-us', [
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'background' => asset('images/2.jpg'),
        ]);
    }

    public function contactUs(): Response
    {
        return Inertia::render('Home/contact-us', [
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'background' => asset('images/1.jpg'),
        ]);
    }

    public function contactUsSubmit(Request $request): Response
    {
        dd($request->all());
    }

    public function dashboard(): Response
    {
        return Inertia::render('Dashboard');
    }
}
