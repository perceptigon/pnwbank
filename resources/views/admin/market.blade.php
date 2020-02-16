@extends('layouts.admin')

@section('content')
    <section class="content-header">
        <h1>Market</h1>
    </section>
    <section class="content">
        @include("admin.alerts")
        <div class="row">
            <div class="col-md-3">
                <div class="info-box">
                    <span class="info-box-icon bg-aqua"><i class="fa fa-credit-card-alt"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Spent</span>
                        <span class="info-box-number">${{ number_format($stats->marketTotal) }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="info-box">
                    <span class="info-box-icon bg-green"><i class="fa fa-check"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Transactions</span>
                        <span class="info-box-number">{{ number_format($stats->marketTransations) }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="info-box">
                    <span class="info-box-icon bg-red"><i class="fa fa-ban"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Units Bought</span>
                        <span class="info-box-number">{{ number_format($stats->unitsBought) }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="info-box">
                    <span class="info-box-icon bg-yellow"><i class="fa fa-line-chart"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Most Popular Resource</span>
                        <span class="info-box-number text-capitalize">{{ $stats->popularResource }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-warning">
                    <div class="box-header with-border">
                        <h3 class="box-title">Total Resources Last 30 Days</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                <canvas id="canvas" height="200" width="600"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="box box-warning">
            <div class="box-header with-border">
                <h3 class="box-title">Transactions</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <tr>
                            <th>Leader</th>
                            <th>Resource</th>
                            <th>Amount</th>
                            <th>Code</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                        @foreach($last15 as $deal)
                            <tr>
                                <td><a href="https://politicsandwar.com/nation/id={{ $deal->nationID }}" target="_blank">{{ $deal->leader }}</a></td>
                                <td class="text-capitalize">{{ $deal->resource }}</td>
                                <td>{{ number_format($deal->amount) }}</td>
                                <td>{{ $deal->code }}</td>
                                <td>
                                    @if ($deal->isPending == true)
                                        Pending
                                    @elseif ($deal->isExpired == true)
                                        Expired
                                    @elseif ($deal->isPaid == true)
                                        Paid
                                    @else
                                        Error
                                    @endif
                                </td>
                                <td>
                                    @if ($deal->isPending == true)
                                        <form method="post" onsubmit="return confirm('Are you sure you want to delete this deal? After deleting the deal, the amount for this deal will be added back to the available pool of resources for others to buy.')">
                                            <input type="submit" name="deleteDeal" class="btn btn-danger" value="Delete">
                                            {{ csrf_field() }}
                                            <input type="hidden" value="{{ $deal->code }}" name="dID">
                                        </form>
                                    @else
                                        No Actions
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
        <div class="box box-warning">
            <div class="box-header with-border">
                <h3 class="box-title">Settings</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
            </div>
            <form method="post">
                <div class="box-body">
                    <div class="row">
                            @foreach ($offers as $offer)
                                <div class="col-md-4">
                                    <h3 class="text-capitalize">{{ $offer->resource }}</h3>
                                    <div class="form-group">
                                        <label>Amount</label>
                                        <input type="number" class="form-control" name="{{ $offer->resource }}Amount" min="0" value="{{ $offer->amount }}" required">
                                    </div>
                                    <div class="form-group">
                                        <label>PPU</label>
                                        <input type="number" class="form-control" name="{{ $offer->resource }}PPU" min="0" value="{{ $offer->ppu }}" required>
                                    </div>
                                </div>
                            @endforeach
                            {{ csrf_field() }}
                    </div>
                </div>
                <div class="box-footer">
                    <input type="submit" class="btn btn-warning btn-lg" name="editOffers" value="Edit">
                </div>
            </form>
        </div>
     </section>

    <script>
        var lineChartData = {
            labels : [<?php foreach ($stats->steel as $d) {echo '"'.$d->dates .'",';}?>],
            datasets : [
                {
                    label: "Steel",
                    backgroundColor : "rgba(0, 167, 208, 0.5)",
                    borderColor : "rgba(0, 167, 208, 0.5)",
                    fill: false,
                    data : [
                        @foreach ($stats->steel as $r)
                            {{ $r->value }},
                        @endforeach
                    ]
                },
                {
                    label: "Munitions",
                    backgroundColor : "rgba(0, 141, 76, 0.5)",
                    borderColor : "rgba(0, 141, 76, 0.5)",
                    fill: false,
                    data : [
                        @foreach ($stats->munitions as $r)
                            {{ $r->value }},
                        @endforeach
                    ]
                },
                {
                    label: "Aluminum",
                    backgroundColor : "rgba(211, 55, 36, 0.5)",
                    borderColor : "rgba(211, 55, 36, 0.5)",
                    fill: false,
                    data : [
                        @foreach ($stats->aluminum as $r)
                            {{ $r->value }},
                        @endforeach
                    ]
                },
                {
                    label: "Gasoline",
                    backgroundColor : "rgba(255, 119, 1, 0.5)",
                    borderColor : "rgba(255, 119, 1, 0.5)",
                    fill: false,
                    data : [
                        @foreach ($stats->gas as $r)
                            {{ $r->value }},
                        @endforeach
                    ]
                }
            ]

        };

        window.onload = function(){
            var ctx = document.getElementById("canvas").getContext("2d");
            window.myLine = new Chart(ctx, {
                type : 'line',
                data: lineChartData
            });
        };
    </script>

@endsection