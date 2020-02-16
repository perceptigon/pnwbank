@extends('layouts.app')

@section("headScripts")
    <link href="{{ url("lib/admin/css/AdminLTE.min.css") }}" rel="stylesheet">
    <link href="{{ url("lib/font-awesome/css/font-awesome.min.css") }}" rel="stylesheet">
    <script src="{{ url("lib/chart/Chart.min.js") }}"></script>
@endsection

@section('content')
<h1 class="text-center">{{ Auth::user()->username }}'s Dashboard</h1>
    <div class="row">
        <div class="col-md-3 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="fa fa-star" style="line-height: 90px;"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">MMR Score</span>
                    <span class="info-box-number">{{ number_format($mmrScore, 2) }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-red"><i class="fa fa-usd" style="line-height: 90px;"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Taxed</span>
                    <span class="info-box-number">${{ number_format($totalTaxed, 2) }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-green"><i class="fa fa-building" style="line-height: 90px;"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total City Grants</span>
                    <span class="info-box-number">${{ number_format($totalCityGrants) }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-yellow"><i class="fa fa-list-ol" style="line-height: 90px;"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Loaned</span>
                    <span class="info-box-number">${{ number_format($totalLoaned) }}</span>
                </div>
            </div>
        </div>
    </div>
    <div>
        <canvas id="scoreChart"></canvas>
    </div>

    <hr>

    <div class="row">
        <div class="col-xs-12">
            <canvas id="moneyChart"></canvas>
        </div>
        <div class="col-lg-6 col-md-12">
            <canvas id="steelChart"></canvas>
        </div>
        <div class="col-lg-6 col-md-12">
            <canvas id="gasChart"></canvas>
        </div>
        <div class="col-lg-6 col-md-12">
            <canvas id="aluminumChart"></canvas>
        </div>
        <div class="col-lg-6 col-md-12">
            <canvas id="munitionsChart"></canvas>
        </div>
        <div class="col-lg-6 col-md-12">
            <canvas id="uraniumChart"></canvas>
        </div>
        <div class="col-lg-6 col-md-12">
            <canvas id="foodChart"></canvas>
        </div>
    </div>

    <hr>

    <div>
        <canvas id="taxChart"></canvas>
    </div>

    <p class="text-center"><a href="{{ url("/user/export") }}">Export</a></p>

    <script>
        // Member Score History Graph
        var scoreConfig = {
            type: 'line',
            data: {
                labels: [@foreach($nationHistory as $his) "{{ \Carbon\Carbon::createFromFormat("Y-m-d H:i:s", $his->date)->toDateString() }}", @endforeach],
                datasets: [{
                    label: "Nation Score History",
                    backgroundColor: "rgba(211, 55, 36, 0.5)",
                    borderColor: "rgba(211, 55, 36, 0.5)",
                    pointRadius : 0,
                    data: [@foreach ($nationHistory as $his) {{ $his->score }}, @endforeach]
                }]
            },
            options: {
                responsive: true,
                title: {
                    display: true,
                    text: "Nation Score History"
                },
                tooltips: {
                    mode: 'index',
                    intersect: false
                },
                scales: {
                    xAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'Day'
                        }
                    }],
                    yAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'Score'
                        }
                    }]
                }
            }
        };

        @foreach($signInResources as $sir)
        var {{ $sir["variable"] }}Config = {
                type: 'line',
                data: {
                    labels: [@foreach($signInHistory as $his) "{{ \Carbon\Carbon::createFromFormat("Y-m-d H:i:s", $his->timestamp)->toDateString() }}", @endforeach],
                    datasets: [{
                        label: "{{ $sir["name"] }}",
                        backgroundColor: "{{ $sir["color"] }}",
                        borderColor: "{{ $sir["color"] }}",
                        pointRadius : 0,
                        data: [@foreach ($signInHistory as $his) {{ $his->{$sir["variable"]} }}, @endforeach]
                    }]
                },
                options: {
                    responsive: true,
                    title: {
                        display: true,
                        text: "One Year {{ $sir["name"] }} History"
                    },
                    tooltips: {
                        mode: 'index',
                        intersect: false
                    },
                    scales: {
                        xAxes: [{
                            display: true,
                            scaleLabel: {
                                display: true,
                                labelString: 'Day'
                            }
                        }],
                        yAxes: [{
                            display: true,
                            scaleLabel: {
                                display: true,
                                labelString: 'Value'
                            }
                        }]
                    }
                }
            };
        @endforeach

        // Tax History Graph
        var taxConfig = {
            type: 'line',
            data: {
                labels: [@foreach($taxHistory as $his) "{{ \Carbon\Carbon::createFromFormat("Y-m-d H:i:s", $his->timestamp)->toDateString() }}", @endforeach],
                datasets: [{
                    label: "Tax History",
                    backgroundColor: "rgba(63, 81, 181, 0.5)",
                    borderColor: "rgba(63, 81, 181, 0.5)",
                    pointRadius : 0,
                    data: [@foreach ($taxHistory as $his) {{ $his->money }}, @endforeach]
                }]
            },
            options: {
                responsive: true,
                title: {
                    display: true,
                    text: "Tax History"
                },
                tooltips: {
                    mode: 'index',
                    intersect: false
                },
                scales: {
                    xAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'Day'
                        }
                    }],
                    yAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'Money'
                        }
                    }]
                }
            }
        };



        window.onload = function() {
            var scoreChart = document.getElementById("scoreChart").getContext('2d');
            var moneyChart = document.getElementById("moneyChart").getContext('2d');
            var taxChart = document.getElementById("taxChart").getContext('2d');
            @foreach($signInResources as $sir)
                var {{ $sir["variable"] }}Chart = document.getElementById("{{ $sir["variable"] }}Chart").getContext('2d');
                window.myLine = new Chart({{ $sir["variable"] }}Chart, {{ $sir["variable"] }}Config);
            @endforeach
            window.myLine = new Chart(scoreChart, scoreConfig);
            window.myLine = new Chart(taxChart, taxConfig);
        }
    </script>
@endsection