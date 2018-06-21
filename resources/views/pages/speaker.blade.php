@extends('layouts.app')

@section('content')
    <p>Speaker</p>
    {{$speaker}}
    <speaker speaker={{json_encode($speaker)}} path={{url('/img')}}></speaker>
@endsection