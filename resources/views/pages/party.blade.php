@extends('layouts.app')

@section('content')
    <party name={{urlencode($name)}} path={{url('/img')}}></party>
@endsection