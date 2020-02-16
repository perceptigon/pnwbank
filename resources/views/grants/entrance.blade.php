@extends('layouts.app')

@section('content')
    <h1 class="text-center">Entrance Aid</h1>
    @if ($system === 1)
    <div class="row">
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
            <p>The entrance aid system is currently turned off.</p>
        </div>
    @endif
    <div class="bs-callout bs-callout-primary">
        <h2>Information</h2>
        <p>All new recruits are eligible for a ${{ number_format($amount) }} bonus upon completion of the academy.</p>
        <li>Agree that If you leave within three months of receiving this grant, you must pay the full grant back. By requesting this grant, you agree to this term</li>
    </div>
@endsection