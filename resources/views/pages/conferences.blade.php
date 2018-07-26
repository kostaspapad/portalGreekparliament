@extends('layouts.app')

@section('content')
    {{-- {{json_decode(json_encode($conferences))}} --}}
    {{-- {{$test = (array) $conferences}} --}}
    {{-- <conferences :conferences="{{json_encode($conferences)}}" path={{url('/img')}}></conferences> --}}
    <conferences path={{url('/img')}}></conferences>
@endsection