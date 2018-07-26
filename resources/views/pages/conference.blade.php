@extends('layouts.app')

@section('content')
    <conference conf_date="{{$conf_date}}" path={{url('/img')}}></conference>
@endsection