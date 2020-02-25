@extends('layouts.app')

@section('content')
    <h1 class="text-center">Bauxite Grant</h1>
    @if ($system === 1)
        <div class="row">
            <p class="text-center" style="color: red">Note: The validation for this grant is a bit more complicated than the others, so it can take several seconds to load after you hit request.</p>
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
            <p>The bauxite grant system is currently turned off.</p>
        </div>
    @endif
    <div class="bs-callout bs-callout-primary">
        <h2>Information</h2>
        <ul>
            <li><strong>Grant Amount</strong>: 10000 Bauxite</li>
            <li>
                In order to qualify for the grant you must fulfill the following requirements:
                <ul>
                    <li>Less than 1000 Bauxite.</li>
                </ul>
            </li>
        </ul>
    </div>
@endsection