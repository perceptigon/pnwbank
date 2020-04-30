@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-6">
            <a href="{{ url("/ia/apply") }}" class="btn btn-xl btn-link homeButton">Apply</a>
        </div>
        <div class="col-lg-6">
            <a href="{{ url("/signin") }}" class="btn btn-xl btn-link homeButton">Sign In</a>
        </div>
        <div class="col-lg-6">
            <a href="{{ url("/accounts") }}" class="btn btn-xl btn-link homeButton">Accounts</a>
        </div>
        <div class="col-lg-6">
            <a href="{{ url("/defense/dashboard") }}" class="btn btn-xl btn-link homeButton">Defense</a>
        </div>
    </div><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
@endsection