@extends('layouts.app')

@section('content')
    <h1 class="text-center">CIA Grant</h1>
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
            <p>The CIA grant system is currently turned off.</p>
        </div>
    @endif
    <div class="bs-callout bs-callout-primary">
        <h2>Information</h2>
        <ul>
            <li>Grant Amount: ${{ number_format($amount) }}</li>
            <li>
                In order to qualify for the grant you must fulfill the following requirements:
                <ul>
                    <li>Be able to purchase the project within the next few days, i.e. you must have a project slot open and you can't have just bought another wonder/a city.</li>
                    <li>Have the remaining money and the resources required for purchasing the project.</li>
                    <li>Have been in the alliance for at least 1 month.</li>
                </ul>
            </li>
            <li>Further by accepting the grant you agree that if you decide to leave the alliance within the next 2 months, you will be required to repay the full amount, i.e. 5 million to the alliance bank.</li>
        </ul>
    </div>
@endsection