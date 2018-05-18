@extends('layouts.app')

@section('content')
    <p>Speakers</p>
    {{-- <speakers id={{$id}}></speakers> --}}
    {{-- {{json_encode($speakers)}} --}}
    {{-- @foreach($speakers as $data)
        {{$data->email}}
    @endforeach --}}
    {{-- {{$speakers->links()}} --}}
    <speakers speakers={{json_encode($speakers)}} path={{url('/img')}}></speakers>
@endsection