@extends('layouts.app')

@section('content')
    {{-- {{$name}}
    {{urlencode($name)}} --}}
    <speaker name={{urlencode($name)}} path={{url('/img')}}></speaker>
@endsection