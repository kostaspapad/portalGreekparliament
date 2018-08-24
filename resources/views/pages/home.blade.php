@extends('layouts.app')

@section('content')
<div class="container-imagebox">
    <div class="row">
        <div class="image-container">
            <img src="/img/justice2.jpg" alt="">
            <h1 class="centered image-text">Find a speaker</h1>
            <speechsearch class="search"></speechsearch>
        </div>
        <div class="imagebox-text col-sm">
            <h2 class="imagebox-text-title">Democracy: it’s for everyone</h2>
            <br>
            <div class="imagebox-text">
                <p>You shouldn’t have to be an expert to understand what goes on in Parliament. Your politicians represent you… but what exactly do they do in your name?</p>
                <br>
                <p>TheyWorkForYou takes open data from the UK Parliament, and presents it in a way that’s easy to follow – for everyone. So now you can check, with just a few clicks: are They Working For You?</p>
                Find out more about TheyWorkForYou →
            </div>
        </div>
    </div>
</div> 
<style>
   
</style>
@endsection