@extends('layouts.app')

@section('content')    
    {{-- {{json_encode($speakers)}} --}}
    {{-- @foreach($speakers as $data)
        {{$data->email}}
    @endforeach --}}
    {{-- {{$speakers->links()}} --}}
    {{-- <speakers  path={{url('/img')}}></speakers> --}}
    <speakers-new path={{url('/img')}}></speakers-new>
    {{-- <speakers speakers={{json_encode($speakers)}} path={{url('/img')}}></speakers> --}}
@endsection