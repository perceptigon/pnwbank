@extends('layouts.app')

@section('content')
    <h1 class="text-center">CCE Grants</h1>
    @if ($system === 1)
        <div class="row">
            <p class="text-center" style="color: red">Limited to one Per Person</p>
            <div class="col-md-6 col-md-offset-3">
                <form method="post">
                    <div class="form-group">
                        <label for="nID">Nation ID</label>
                        <input type="number" class="form-control" id="nID" name="nID" required @if (Auth::check()) value="{{ Auth::user()->nID }}" @endif>
                    </div>
                    <div class="form-group">
                        {{ csrf_field() }}
                        <input type="submit" class="btn btn-primary" value="Request">
                    </div>
                </form>
            </div>
        </div>
    @else
        <div class="alert alert-info alert-dismissible">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <h4 style="font-size: 20px;">Sorry</h4>
            <p>The grant system is currently turned off.</p>
        </div>
    @endif
    <div class="bs-callout bs-callout-primary">
        <h2>Information</h2>
        <ul>
            <li>Grant Amount: Full Cost of CCE</li>
            <li>
                In order to qualify for the grant you must fulfill the following requirements:
                <ul>
                    <li>Be good</li>
                    <li>Have the CIA, Iron Dome, MLP & PB</li>
                    <li>Agree that If you leave within three months of receiving this grant, you must pay the full grant back. By requesting this grant, you agree to this term</li>
                </ul>
            </li>
        </ul>
    </div>
@endsection