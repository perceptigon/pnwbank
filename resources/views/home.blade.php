@extends('layouts.app')

@section('content')


<img src="https://i.ibb.co/T0MZqvH/imageedit-19-6358665258.png" title="Rothschilds & Co." alt="" />
<h1>Welcome to the bank of Rothschild Family.</h1>



    <div class="row">
        <div class="col-lg-6">
            <a href="{{ url("/ia/apply") }}" class="btn btn-xl btn-link homeButton">About</a>
        </div>
        <div class="col-lg-6">
            <a href="{{ url("https://politicsandwar.fandom.com/wiki/Rothschild_Family") }}" class="btn btn-xl btn-link homeButton">Wiki</a>
        </div>
        <div class="col-lg-6">
            <a href="{{ url("/accounts") }}" class="btn btn-xl btn-link homeButton">Accounts</a>
        </div>
        <div class="col-lg-6">
            <a href="{{ url("/defense/dashboard") }}" class="btn btn-xl btn-link homeButton">Defense</a>
        </div>
        <div class="col-lg-6">
            <a href="{{ url("/outsidetransferaa") }}" class="btn btn-xl btn-link homeButton">Withdraw to a nation</a>
        </div>
        <div class="col-lg-6">
            <a href="{{ url("/outsidetransfer") }}" class="btn btn-xl btn-link homeButton">Withdraw to an alliance</a>
        </div>
    </div><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
@endsection