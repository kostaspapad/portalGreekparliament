<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Conference;

class ConferencesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $arr = array();
        $conferences = Conference::orderBy('conference_date','desc')->select('conference_date')->distinct()->get();
        //$conferences = Conference::all(); 
        foreach($conferences as $c){
            // $arr[$c->Date]['ID'] = $c->ID;
            // $arr[$c->Date]['Conference'] = $c->Conference;
            // $arr[$c->Date]['DocumentLocation'] = $c->DocumentLocation;
            // $arr[$c->Date]['DocumentName'] = $c->DocumentName;
            // $arr[$c->Date]['RelatedVideosLink'] = $c->RelatedVideosLink;
            // $arr[$c->Date]['Session'] = $c->Session;
            // $arr[$c->Date]['TimePeriod'] = $c->TimePeriod;
            // $arr[$c->Date]['DateOfCrawl'] = $c->DateOfCrawl;
            // $arr[$c->Date]['PDFdocumentLocation'] = $c->PDFdocumentLocation;
            // $arr[$c->Date]['PDFdocuments'] = $c->PDFdocuments;
            // $arr[$c->Date]['WebPageNum'] = $c->WebPageNum;
            // $arr[$c->Date]['MorningConf'] = $c->MorningConf;
            // $arr[$c->Date]['AfternoonConf'] = $c->AfternoonConf;
            // $arr[$c->Date]['Downloaded'] = $c->Downloaded;
            $arr[$c->conference_date] = $c->conference_date;
        }
        // print_r($arr['1992-05-05']);
        // die;
        //return $conferences;
        $obj = (object) $arr;
        //return json_encode($obj);
        //return view('pages.conferences')->with('conferences',json_encode($obj));
        return view('pages.conferences')->with('conferences',$conferences);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
