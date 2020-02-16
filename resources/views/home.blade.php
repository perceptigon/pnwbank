@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-6">
            <a href="{{ url("/ia/apply") }}" class="btn btn-xl btn-link homeButton">Apply</a>
        </div>
        <div class="col-lg-6">
            <a href="{{ url("/loans") }}" class="btn btn-xl btn-link homeButton">Loans</a>
        </div>
        <div class="col-lg-6">
            <a href="{{ url("/grants/city") }}" class="btn btn-xl btn-link homeButton">City Grants</a>
        </div>
        <div class="col-lg-6">
            <a href="{{ url("/grants/entrance") }}" class="btn btn-xl btn-link homeButton">Entrance Grants</a>
        </div>
        <div class="col-lg-6">
            <a href="{{ url("/grants/activity") }}" class="btn btn-xl btn-link homeButton">Activity Grants</a>
        </div>
        <div class="col-lg-6">
            <a href="{{ url("/grants/id") }}" class="btn btn-xl btn-link homeButton">CIA Grants</a>
        </div>
        <div class="col-lg-6">
            <a href="{{ url("/grants/oil") }}" class="btn btn-xl btn-link homeButton">Oil Grant</a>
        </div>
        <div class="col-lg-6">
            <a href="{{ url("/grants/nuke") }}" class="btn btn-xl btn-link homeButton">Nuke Grant</a>
        </div>
        <div class="col-lg-6">
            <a href="{{ url("/grants/egr") }}" class="btn btn-xl btn-link homeButton">EGR Grant</a>
        </div>
        <div class="col-lg-6">
            <a href="{{ url("/market") }}" class="btn btn-xl btn-link homeButton">Sell Resources</a>
        </div>
        <div class="col-lg-6">
            <a href="{{ url("/signin") }}" class="btn btn-xl btn-link homeButton">Sign In</a>
        </div>
    </div>
@endsection