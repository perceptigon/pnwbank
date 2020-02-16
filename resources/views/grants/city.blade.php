@extends('layouts.app')

@section('content')
    <h1 class="text-center">City Grants</h1>
    @if ($settings->value === 1)
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <form method="post">
                    <div class="form-group">
                        <label class="control-label" for="nID">Nation ID</label>
                        <input type="number" id="nID" name="nID" class="form-control" required @if (Auth::check()) value="{{ Auth::user()->nID }}" @endif>
                        <p class="help-block">The nation ID is the numbers at the end of the URL when you view your nation. EX: https://politicsandwar.com/nation/id=XXXXX</p>
                    </div>
                    <div class="form-group">
                        {{ csrf_field() }}
                        <input type="submit" name="reqGrant" class="btn btn-raised btn-primary" value="Request">
                    </div>
                </form>
            </div>
        </div>
    @else
        <div class="alert alert-info alert-dismissible">
            <button type="button" class="close" data-dismiss="alert">�</button>
            <h4 style="font-size: 20px;">Sorry</h4>
            <p>The City Grant system is currently turned off.</p>
        </div>
    @endif
    <div class="bs-callout bs-callout-primary">
        <h2>Information</h2>
        <p>When you request a grant, the system will automatically determine which city grant you are eligible for.</p>
        <p>You should not request a grant unless you have the remaining money needed to purchase the city</p>
        <p>You must be using the manifest destiny policy</p>
        <p>After requesting your grant, it may take up to 24 hours for your request to be approved</p>
        <p>If you leave within three months of receiving a city grant, you must pay the full grant back. By requesting a city grant, you agree to this.</p>
        <p>Grant values and requirements are as follows:</p>
        <ul>
            @foreach ($grants as $grant)
                <li class=" {{ $grant->enabled ? "" : "text-muted" }}">
                    <strong>City {{ $grant->grantNum }} - ${{ number_format($grant->amount) }}</strong>
                    <ul>
                        @if (! $grant->enabled)
                            <li>Currently Disabled</li>
                        @endif
                        @if ($grant->infPerCity === 0)
                            <li>No infra requirement! Just apply!</li>
                        @else
                            <li>Requires {{ number_format($grant->infPerCity) }} Infra Per City</li>
                        @endif

                        @if ($grant->irondome === 1)
                            <li>Requires Intel Agency or Iron Dome</li>
                        @endif

                        @if ($grant->NRF === 1)
                            <li>Requires Nuclear Research Facility</li>
                        @endif
                        {!! $grant->notes !!}
                    </ul>
                </li>
            @endforeach
        </ul>
    </div>
@endsection