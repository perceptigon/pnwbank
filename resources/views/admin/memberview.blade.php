@extends('layouts.admin')

@section('content')
    <section class="content-header">
        <h1><a href="https://politicsandwar.com/nation/id={{ $nation->nID }}" target="_blank"> {{ $nation->leader ?? "Error" }}</a></h1>
    </section>
    <section class="content">
        @include("admin.alerts")
        <div class="row">
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-aqua"><i class="fa fa-credit-card" style="line-height: 90px;"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Loaned</span>
                        <span class="info-box-number">${{ number_format($stats["totalLoaned"]) }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-red"><i class="fa fa-building-o"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total City Grants</span>
                        <span class="info-box-number">${{ number_format($stats["totalCityGrants"]) }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-green"><i class="fa fa-adn"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Activity Grants</span>
                        <span class="info-box-number">${{ number_format($stats["totalActivity"]) }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-yellow"><i class="fa fa-btc"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Taxed</span>
                        <span class="info-box-number">${{ number_format($stats["totalTaxed"]) }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="box box-warning">
                    <div class="box-header with-border">
                        <h3 class="box-title">Tax History</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                                <i class="fa fa-minus"></i></button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
                                <i class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <canvas id="taxHistory"/>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="box box-warning">
                    <div class="box-header with-border">
                        <h3 class="box-title">Last 10 Sign ins</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                                <i class="fa fa-minus"></i></button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
                                <i class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <canvas id="signins"/>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4">
                <div class="box box-warning">
                    <div class="box-header with-border">
                        <h3 class="box-title">Last Five Loans</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                                <i class="fa fa-minus"></i></button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
                                <i class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <table class="table table-striped">
                            <tr>
                                <th>Date</th>
                                <th>Code</th>
                                <th>Amount</th>
                                <th>Status</th>
                            </tr>
                            @foreach ($loans as $loan)
                                <tr>
                                    <td>{{ \Carbon\Carbon::createFromFormat("Y-m-d H:s:i", $loan->timestamp)->toDateString() }}</td>
                                    <td><a href="{{ url("/lookup/$loan->code") }}">{{ $loan->code }}</a></td>
                                    <td>${{ number_format($loan->amount) }}</td>
                                    <td>
                                        @if ($loan->isPaid)
                                            <span class="text-success">Paid</span>
                                        @elseif ($loan->isPending)
                                            <span class="text-warning">Pending</span>
                                        @elseif ($loan->isActive)
                                            <span class="text-info">Active</span>
                                        @elseif ($loan->isDenied)
                                            <span class="text-danger">Denied</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="box box-warning">
                    <div class="box-header with-border">
                        <h3 class="box-title">Last Five City Grants</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                                <i class="fa fa-minus"></i></button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
                                <i class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <table class="table table-striped">
                            <tr>
                                <th>ID</th>
                                <th>Date</th>
                                <th>City Num</th>
                                <th>Amount</th>
                                <th>Status</th>
                            </tr>
                            @foreach ($cityGrants as $city)
                                <tr>
                                    <td>{{ $city->id }}</td>
                                    <td>{{ \Carbon\Carbon::createFromFormat("Y-m-d H:s:i", $city->timestamp)->toDateString() }}</td>
                                    <td>City {{ $city->cityNum }}</td>
                                    <td>${{ number_format($city->amount) }}</td>
                                    <td>
                                        @if ($city->isSent)
                                            <span class="text-success">Sent</span>
                                        @elseif ($city->isPending)
                                            <span class="text-warning">Pending</span>
                                        @elseif ($city->isDenied)
                                            <span class="text-danger">Denied</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="box box-warning">
                    <div class="box-header with-border">
                        <h3 class="box-title">Last Five Activity Grants</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                                <i class="fa fa-minus"></i></button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
                                <i class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <table class="table table-striped">
                            <tr>
                                <th>ID</th>
                                <th>Date</th>
                                <th>Threshold</th>
                                <th>Amount</th>
                                <th>Status</th>
                            </tr>
                            @foreach ($activityGrants as $grant)
                                <tr>
                                    <td>{{ $grant->id }}</td>
                                    <td>{{ \Carbon\Carbon::createFromFormat("Y-m-d H:s:i", $grant->timestamp)->toDateString() }}</td>
                                    <td>{{ number_format($grant->threshold) }}</td>
                                    <td>${{ number_format($grant->amount) }}</td>
                                    <td>
                                        @if ($grant->isSent)
                                            <span class="text-success">Sent</span>
                                        @elseif ($city->isPending)
                                            <span class="text-warning">Pending</span>
                                        @else
                                            <span class="text-danger">Denied</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="box box-warning">
                    <div class="box-header with-border">
                        <h3 class="box-title">{{ $nation->leader }}'s Profile</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                                <i class="fa fa-minus"></i></button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
                                <i class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <p class="text-danger"><strong>Warning:</strong> Editing these values can mess things up or allow people to have multiple loans or grants. Only edit if you know what you're doing.</p>
                        <form method="post">
                            <div class="form-group">
                                <label>Nation ID</label>
                                <input type="text" class="form-control" name="nID" disabled value="{{ $profile->nationID }}">
                            </div>
                            <div class="form-group">
                                <label>Last Loan</label>
                                <input type="date" class="form-control" name="lastLoan" value="{{ $profile->lastLoan }}">
                            </div>
                            <div class="form-group">
                                <div class="checkbox">
                                    <label><input type="checkbox" value="1" {{ $profile->loanActive ? "checked" : "" }} name="loanActive"> Loan Active</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="checkbox">
                                    <label><input type="checkbox" value="1" {{ $profile->grantPending ? "checked" : "" }} name="grantPending"> Grant Pending</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Last City Grant</label>
                                <input type="number" class="form-control" name="lastGrant" value="{{ $profile->lastGrant }}">
                            </div>
                            <div class="form-group">
                                <label>Last City Grant Date</label>
                                <input type="date" class="form-control" name="lastGrantDate" value="{{ $profile->lastGrantDate }}">
                            </div>
                            <div class="form-group">
                                <div class="checkbox">
                                    <label><input type="checkbox" value="1" {{ $profile->entAid ? "checked" : "" }} name="entAid"> Entrance Grant</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Last Activity Grant</label>
                                <input type="number" class="form-control" name="lastActivityGrant" value="{{ $profile->lastActivityGrant }}">
                            </div>
                            <div class="form-group">
                                <div class="checkbox">
                                    <label><input type="checkbox" value="1" {{ $profile->pendingActivityGrant ? "checked" : "" }} name="pendingActivityGrant"> Pending Activity Grant</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="checkbox">
                                    <label><input type="checkbox" value="1" {{ $profile->gottenIDGrant ? "checked" : "" }} name="gottenIDGrant"> Gotten CIA Grant</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="checkbox">
                                    <label><input type="checkbox" value="1" {{ $profile->gottencceGrant ? "checked" : "" }} name="gottencceGrant"> Gotten CCE Grant</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="checkbox">
                                    <label><input type="checkbox" value="1" {{ $profile->gottennrfGrant ? "checked" : "" }} name="gottennrfGrant"> Gotten NRF Grant</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="checkbox">
                                    <label><input type="checkbox" value="1" {{ $profile->gottenpbGrant ? "checked" : "" }} name="gottenpbGrant"> Gotten PB Grant</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="checkbox">
                                    <label><input type="checkbox" value="1" {{ $profile->gottenmlpGrant ? "checked" : "" }} name="gottenmlpGrant"> Gotten MLP Grant</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="checkbox">
                                    <label><input type="checkbox" value="1" {{ $profile->gottenirondomeGrant ? "checked" : "" }} name="gottenirondomeGrant"> Gotten ID Grant</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="checkbox">
                                    <label><input type="checkbox" value="1" {{ $profile->gottenEGRGrant ? "checked" : "" }} name="gottenEGRGrant"> Gotten EGR Grant</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <input type="submit" class="btn btn-warning" name="editProfile" value="Update">
                            </div>
                            {{ csrf_field() }}
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        var lineChartData = {
            labels : [
                    @foreach ($signins as $signin)
                        "{{ \Carbon\Carbon::createFromFormat("Y-m-d H:s:i", $signin->timestamp)->toDateString() }}",
                    @endforeach
            ],
            datasets : [
                {
                    label: "Steel",
                    backgroundColor : "rgba(0, 167, 208, 0.5)",
                    borderColor : "rgba(0, 167, 208, 0.5)",
                    fill: false,
                    data : [
                        @foreach ($signins as $signin)
                            {{ $signin->steel }},
                        @endforeach
                    ]
                },
                {
                    label: "Munitions",
                    backgroundColor : "rgba(0, 141, 76, 0.5)",
                    borderColor : "rgba(0, 141, 76, 0.5)",
                    fill: false,
                    data : [
                        @foreach ($signins as $signin)
                        {{ $signin->munitions }},
                        @endforeach
                    ]
                },
                {
                    label: "Aluminum",
                    backgroundColor : "rgba(211, 55, 36, 0.5)",
                    borderColor : "rgba(211, 55, 36, 0.5)",
                    fill: false,
                    data : [
                        @foreach ($signins as $signin)
                        {{ $signin->aluminum }},
                        @endforeach
                    ]
                },
                {
                    label: "Gasoline",
                    backgroundColor : "rgba(255, 119, 1, 0.5)",
                    borderColor : "rgba(255, 119, 1, 0.5)",
                    fill: false,
                    data : [
                        @foreach ($signins as $signin)
                        {{ $signin->gas }},
                        @endforeach
                    ]
                }
            ]
        };

        var taxHistory = {
            type: "line",
            data : {
                labels : [
                    @foreach ($taxHistory as $date)
                            "{{ \Carbon\Carbon::createFromFormat("Y-m-d H:s:i", $date->date)->toDateString() }}",
                    @endforeach
                ],
                datasets : [{
                    label : "Tax History",
                    backgroundColor : "rgba(224, 142, 11, 0.7)",
                    data : [
                        @foreach ($taxHistory as $his)
                        {{ $his->amount }},
                        @endforeach
                    ]}
                ]
            }
        };


        window.onload = function(){
            var ctx = document.getElementById("signins").getContext("2d");
            window.myLine = new Chart(ctx, {
                type : 'line',
                data: lineChartData
            });
            var ctx2 = document.getElementById("taxHistory");
            window.myLine = new Chart(ctx2, taxHistory);
        };
    </script>
@endsection