@extends('layouts.admin')

@section('content')

    <section class="content">
        @include("admin.alerts")
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
                    label : "Rothschilds & Co. Bank Balance",
                    backgroundColor : "rgba(224, 142, 11, 0.7)",
                    data : [
                        @foreach ($stats->money as $money)
                        {{ $money->value }},
                        @endforeach
                    ]
                }
            ]
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