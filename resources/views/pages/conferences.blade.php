@extends('layouts.app')

@section('content')
    <p>Conferences</p>
    {{-- {{json_decode(json_encode($conferences))}} --}}
    {{-- {{$test = (array) $conferences}} --}}
    <conferences :conferences="{{json_encode($conferences)}}" path={{url('/img')}}></conferences>
@endsection