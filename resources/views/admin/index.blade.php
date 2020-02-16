@extends('layouts.admin')

@php

use App\Models\BKNation;

$nations = BKNation::where('inBK', true)->get();

@endphp

@section('content')
    <section class="content-header">
        <h1>Dashboard</h1>
    </section>
    <section class="content">
        @include("admin.alerts")
        <div class="row">
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-aqua"><i class="fa fa-credit-card" style="line-height: 90px;"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Current Loaned</span>
                        <span class="info-box-number">${{ number_format($stats->currentLoaned) }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-red"><i class="fa fa-building-o"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total City Grants</span>
                        <span class="info-box-number">${{ number_format($stats->totalCityGrantsSent) }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-green"><i class="fa fa-shopping-cart"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Spent On Market</span>
                        <span class="info-box-number">${{ number_format($stats->marketTotal) }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-yellow"><i class="fa fa-btc"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Weekly Tax</span>
                        <span class="info-box-number">${{ number_format($stats->weeklyTax) }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-warning">
                    <div class="box-header with-border">
                        <h3 class="box-title">Bank Balance 30 Days</h3>
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
                <h3 class="box-title">Combined Warchests</h3>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <tr>
                            <th>Money</th>
                            <th>Steel</th>
                            <th>Munitions</th>
                            <th>Gasoline</th>
                            <th>Aluminum</th>
                            <th>Food</th>
                            <th>Uranium</th>
                        </tr>
                        <tr>
                            <td>${{ number_format($nations->sum('money'), 0) }}</td>
                            <td>{{ number_format($nations->sum('steel'), 0) }}</td>
                            <td>{{ number_format($nations->sum('munitions'), 0) }}</td>
                            <td>{{ number_format($nations->sum('gas'), 0) }}</td>
                            <td>{{ number_format($nations->sum('aluminum'), 0) }}</td>
                            <td>{{ number_format($nations->sum('food'), 0) }}</td>
                            <td>{{ number_format($nations->sum('uranium'), 0) }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-7">
                <div class="box box-warning">
                    <div class="box-header with-border">
                        <h3 class="box-title">Money Spent Last 30 Days</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                <canvas id="pieChart"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-5">
                <div class="info-box bg-yellow">
                    <span class="info-box-icon"><i class="fa fa-check"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Loans Approved</span>
                        <span class="info-box-number">{{ number_format($stats->numLoans) }}</span>

                        <div class="progress">
                            <div class="progress-bar" style="width: 100%"></div>
                        </div>
                        <span class="progress-description"></span>
                    </div>
                </div>
                <div class="info-box bg-green">
                    <span class="info-box-icon"><i class="fa fa-money"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Spent</span>
                        <span class="info-box-number">${{ number_format($stats->totalSpent) }}</span>

                        <div class="progress">
                            <div class="progress-bar" style="width: 100%"></div>
                        </div>
                        <span class="progress-description"></span>
                    </div>
                </div>
                <div class="info-box bg-red">
                    <span class="info-box-icon"><i class="fa fa-users"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Tax</span>
                        <span class="info-box-number">${{ number_format($stats->totalTax) }}</span>

                        <div class="progress">
                            <div class="progress-bar" style="width: 100%"></div>
                        </div>
                        <span class="progress-description"></span>
                    </div>
                </div>
                <div class="info-box bg-aqua">
                    <span class="info-box-icon"><i class="fa fa-shopping-basket"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Resources Bought</span>
                        <span class="info-box-number">{{ number_format($stats->unitsBought) }}</span>

                        <div class="progress">
                            <div class="progress-bar" style="width: 100%"></div>
                        </div>
                        <span class="progress-description"></span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        var lineChartData = {
            labels : [
                @foreach ($stats->money as $date)
                "{{ $date->date }}",
                @endforeach
            ],
            datasets : [
                {
                    label : "BK Bank Balance",
                    backgroundColor : "rgba(224, 142, 11, 0.7)",
                    data : [
                        @foreach ($stats->money as $money)
                            {{ $money->value }},
                        @endforeach
                    ]
                }
            ]
        };

        var doughnutData = {
            type: 'pie',
            data : {
                datasets: [{
                    data: [
                        {{ $stats->loanMonthly }},
                        {{ $stats->grantMonthly }},
                        {{ $stats->entranceMonthly }},
                        {{ $stats->marketMonthly }},
                        {{ $stats->idMonthly }},
                        {{ $stats->activityMonthly }}
                    ],
                    backgroundColor: [
                        "#F7464A",
                        "#46BFBD",
                        "#FDB45C",
                        "#9C27B0",
                        "#009688",
                        "#607D8B"
                    ]
                }],
                labels: [
                    "Loans",
                    "Grants",
                    "Entrance Aid",
                    "Market",
                    "ID Grants",
                    "Activity Grants"
                ]
            }
        };

        window.onload = function() {
            var ctx = document.getElementById("canvas");
            window.myLine = new Chart(ctx, {
                type: 'line',
                data: lineChartData,
                options: {
                    title: {
                        display: false,
                        text: "Bank Balance Last 30 Days"
                    },
                    tooltips: {
                        enabled: true
                    },
                }
            });
            var ctx2 = document.getElementById("pieChart").getContext("2d");
            window.myPie = new Chart(ctx2, doughnutData);
        };


    </script>

@endsection