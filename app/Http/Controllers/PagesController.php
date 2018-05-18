<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Route;

class PagesController extends Controller
{
    public function index(Route $route){
        // $current_route = $route->uri();
        // echo "route = $current_route";
        // return view('pages.welcome')->with('current_route',$current_route);
        return view('pages.welcome');
    }

    public function about(Route $route){
        // $current_route = $route->uri();
        // echo "route = $current_route";
        return view('pages.about');
    }

    public function speeches(Route $route){
        // $current_route = $route->uri();
        // echo "route = $current_route";
        return view('pages.speeches');
    }

    public function speakers(Route $route){
        // $current_route = $route->uri();
        // echo "route = $current_route";
        return view('pages.speakers');
    }
}
