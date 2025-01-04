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
            'content' => 'This is donation application that helps people to offer medications to those in need',
        ]);
    }

    public function aboutUs(): Response
    {
        return Inertia::render('Home/about-us', [
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'background' => asset('images/background.jpg'),
            'content' => 'we are a company based on 2009 and we have been working on this project since then',
        ]);
    }

    public function contactUs(): Response
    {
        return Inertia::render('Home/contact-us', [
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'background' => asset('images/background.jpg'),
        ]);
    }

    public function contactUsSubmit(Request $request): Response
    {
        dd($request->all());
    }
}
