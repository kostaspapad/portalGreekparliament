<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Route;

class PagesController extends Controller
{
    public function __construct(){
        // $this->middleware('auth')->except(['index','about']);
    }

    public function index(Route $route){
        return view('pages.home');
    }

    public function home(Route $route){
        return view('pages.home');
    }

    public function about(Route $route){
        return view('pages.about');
    }
    
    public function news(Route $route){
        return view('pages.news');
    }

    public function contact(Route $route){
        return view('pages.contact');
    }

    public function donate(Route $route){
        return view('pages.donate');
    }

    public function policy(Route $route){
        return view('pages.policy');
    }

    public function help(Route $route){
        return view('pages.help');
    }

    public function speeches(Route $route){
        return view('pages.speeches');
    }

    public function speakers(Route $route){
        return view('pages.speakers');
    }

    public function speaker($name,Route $route){
        return view('pages.speaker')->with('name',$name);
    }

    public function conferences(Route $route){
        return view('pages.conferences');
    }

    public function conference($date,Route $route){
        return view('pages.conference')->with('conf_date',$date);
    }

    public function parties(Route $route){
        return view('pages.parties');
    }

    public function party($name,Route $route){
        return view('pages.party')->with('name',$name);
    }
}
