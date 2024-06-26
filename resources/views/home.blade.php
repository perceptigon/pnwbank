@extends('layouts.app')

@section('content')

<p style="text-align:center;padding-top:10px;">
<img src="https://i.ibb.co/CKfBGjW/imageedit-1-8652517477.png" width="294" height="196" title="Rothschilds & Co." alt="" /></p>
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
            <a href="{{ url("/user/dashboard") }}" class="btn btn-xl btn-link homeButton">Dashboard</a>
        </div>
        <div class="col-lg-6">
            <a href="{{ url("/loans") }}" class="btn btn-xl btn-link homeButton">Loans</a>
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
    </div>
@endsection