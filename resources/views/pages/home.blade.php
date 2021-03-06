@extends('layouts.app')

@section('content')
<div class="container-imagebox">
    <div class="row" style="margin-right: 0;">
        <div class="image-container col-12 col-sm-6 col-md-4" style="    padding-left: 20px;">
            <img src="/img/justice2.jpg" class="img-fluid">
            <div>
                <h3 class="image-text">Find a speaker</h3>
                <search-plugin></search-plugin>
            </div>
        </div>
        <div class="imagebox-text col-12 col-sm-6 col-md-8">
            <h2 class="imagebox-text-title">Democracy: it’s for everyone</h2>
            <div class="imagebox-text">
                <p>You shouldn’t have to be an expert to understand what goes on in Parliament. Your politicians represent you… but what exactly do they do in your name?</p>
            </div>
        </div>
    </div>
</div> 
@endsection