<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;

class PageController extends Controller
{
    public function index()
    {
        $events = Event::all();
    
        return view('welcome', compact('events'));
    }
    public function about()
    {
        return view('pages.about');
    }

    public function contact()
    {
        return view('pages.contact');
    }

    public function privacy(){
        return view('pages.privacy-policy');
    }

    public function terms()
    {
        return view('pages.terms-and-conditions');
    }

}
