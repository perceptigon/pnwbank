@extends('layouts.app')

@section("headScripts")
    <link href="{{ url("lib/admin/css/AdminLTE.min.css") }}" rel="stylesheet">
    <link href="{{ url("lib/font-awesome/css/font-awesome.min.css") }}" rel="stylesheet">
@endsection

@section('content')

        @if (Auth::check())
            <h3 class="text-center">Defense Dashboard: <a href="https://politicsandwar.com/nation/id={{ $nation->nID }}" target="_blank">{{ $nation->leader }}</a></h3>

            <br>
            <div class="row">
                <div class="col-sm-6 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-aqua"><i class="fa fa-crosshairs" style="line-height: 90px;"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">MMR Percentage</span>
                            <span class="info-box-number">{{ round(\App\Defense\Warchest::mmrScoreFromDefNations($nation), 0) }}%</span>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon {{ ($nation->hasSignedIn) ? "bg-green" : "bg-red" }}"><i class="fa fa-crosshairs" style="line-height: 90px;"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Sign In Status</span>
                            <span class="info-box-number">{{ ($nation->hasSignedIn) ? "Yes" : "No" }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" style="font-size: 150%">
            <h4>Resources</h4>
            <div class="table-responsive">
                <table class="table table-hover table-striped sortable">
                    <tr>
                        <th></th>
                        <th>Steel</th>
                        <th>Gas</th>
                        <th>Aluminum</th>
                        <th>Munitions</th>
                        <th>Uranium</th>
                        <th>Money</th>
                        <th>Food</th>
                    </tr>
                    <tr>
                        <th>Current:</th>
                        <td>{{ number_format($nation->steel) }}</td>
                        <td>{{ number_format($nation->gas) }}</td>
                        <td>{{ number_format($nation->aluminum) }}</td>
                        <td>{{ number_format($nation->munitions) }}</td>
                        <td>{{ number_format($nation->uranium) }}</td>
                        <td>${{ number_format($nation->money) }}</td>
                        <td>{{ number_format($nation->food) }}</td>
                    </tr>
                    <tr>
                        <th>Required:</th>
                        <td>{{ number_format($nationReq->steel) }}</td>
                        <td>{{ number_format($nationReq->gas) }}</td>
                        <td>{{ number_format($nationReq->aluminum) }}</td>
                        <td>{{ number_format($nationReq->munitions) }}</td>
                        <td>{{ number_format($nationReq->uranium) }}</td>
                        <td>${{ number_format($nationReq->money) }}</td>
                        <td>{{ number_format($nationReq->food) }}</td>
                    </tr>
                </table>
            </div>
        <hr>
        <strong><h5><font color="red">If Rothschild Family is at a war state, please follow the below-mentioned <u>mandatory</u> military requirements</font></h5></strong>
        <h4>Military</h4>
        <div class="table-responsive">
            <table class="table table-hover table-striped sortable">
                <tr>
                    <th></th>
                    <th>Soldiers</th>
                    <th>Tanks</th>
                    <th>Planes</th>
                    <th>Ships</th>
                    <th>Missiles</th>
                    <th>Nukes</th>
                    <th>Spies</th>
                </tr>
                <tr>
                    <th>Current:</th>
                    <td>{{ number_format($nation->soldiers) }}</td>
                    <td>{{ number_format($nation->tanks) }}</td>
                    <td>{{ number_format($nation->planes) }}</td>
                    <td>{{ number_format($nation->ships) }}</td>
                    <td>{{ number_format($nation->missiles) }}</td>
                    <td>{{ number_format($nation->nukes) }}</td>
                    <td>{{ number_format($nation->spies) }}</td>
                </tr>
                <tr>
                    <th>Required:</th>
                    <td>{{ number_format(App\Classes\MMR::soldiers($nation->cities)) }}</td>
                    <td>{{ number_format(App\Classes\MMR::tanks($nation->cities)) }}</td>
                    <td>{{ number_format(App\Classes\MMR::planes($nation->cities)) }}</td>
                    <td>{{ number_format(App\Classes\MMR::ships($nation->cities)) }}</td>
                    <td>{{ number_format(App\Classes\MMR::missiles($nation->cities)) }}</td>
                    <td>{{ number_format(App\Classes\MMR::nukes($nation->cities)) }}</td>
                    <td>{{ number_format(App\Classes\MMR::spies($nation->cities)) }}</td>
                </tr>
            </table>
        </div>

        <hr>

        <h4 class="text-center">All City Requirements</h4>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                <tr>
                    <th>City Number</th>
                    <th>Money</th>
                    <th>Food</th>
                    <th>Uranium</th>
                    <th>Gasoline</th>
                    <th>Munitions</th>
                    <th>Steel</th>
                    <th>Aluminum</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($requirements as $req)
                    <tr {{ $req->cityNum == $nation->cities ? "class=success" : "" }}>
                        <td>City #{{ number_format($req->cityNum) }}</td>
                        <td>${{ number_format($req->money) }}</td>
                        <td>{{ number_format($req->food) }}</td>
                        <td>{{ number_format($req->uranium) }}</td>
                        <td>{{ number_format($req->gas) }}</td>
                        <td>{{ number_format($req->munitions) }}</td>
                        <td>{{ number_format($req->steel) }}</td>
                        <td>{{ number_format($req->aluminum) }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

        @endif


@endsection