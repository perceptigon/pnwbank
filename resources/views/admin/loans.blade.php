@extends('layouts.admin')

@section('content')
    <section class="content-header">
        <h1>Loans</h1>
    </section>
    <section class="content">
        @include("admin.alerts")
        @if (App\Models\Settings::getSettings()["warMode"] == true)
            {{ App\Classes\Output::genAlert(["War mode is on. People will not be notified if their loan is late"], "info", "War Mode") }}
        @endif
        <div class="row">
            <div class="col-md-3">
                <div class="info-box">
                    <span class="info-box-icon bg-aqua"><i class="fa fa-credit-card"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Current Loaned</span>
                        <span class="info-box-number">${{ number_format($stats->currentLoaned) }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="info-box">
                    <span class="info-box-icon bg-red"><i class="fa fa-line-chart"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Ever Loanded</span>
                        <span class="info-box-number">${{ number_format($stats->loanTotal) }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="info-box">
                    <span class="info-box-icon bg-green"><i class="fa fa-hashtag"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Number of Loans</span>
                        <span class="info-box-number">{{ number_format($stats->numLoans) }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="info-box">
                    <span class="info-box-icon bg-yellow"><i class="fa fa-ban"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Loans Denied</span>
                        <span class="info-box-number">{{ number_format($stats->numdeniedLoans) }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-warning">
                    <div class="box-header with-border">
                        <h3 class="box-title">Total Loans Last 30 Days</h3>
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
                <h3 class="box-title">Pending Loans</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                        @if (count($pendLoans) === 0)
                            {{ App\Classes\Output::genAlert(["No pending loans"], "info", "Shiiiiiit") }}
                        @else
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover dataTable" role="grid">
                                    <thead>
                                    <tr>
                                        <th>Leader</th>
                                        <th>Nation</th>
                                        <th>Score</th>
                                        <th>Amount</th>
                                        <th>Reason</th>
                                        <th>Approve</th>
                                        <th>Deny</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($pendLoans as $loan)
                                        <tr>
                                            <td>{{ $loan->leader }}</td>
                                            <td><a href="https://politicsandwar.com/nation/id={{ $loan->nationID }}" target="_blank">{{ $loan->nationName }}</a></td>
                                            <td>{{ number_format($loan->score) }}</td>
                                            <td>${{ number_format($loan->amount) }}</td>
                                            <td>{{ $loan->reason }}</td>
                                            <td>
                                                <form method="post" onsubmit="return confirm('Are you sure you want to approve this loan?')" style="margin: 0;">
                                                    {{ csrf_field() }}
                                                    <input type="hidden" value="{{ $loan->id }}" name="loanID">
                                                    <input type="submit" name="approveLoan" class="btn btn-success" value="Approve">
                                                </form>
                                            </td>
                                            <td>
                                                <form method="post" onsubmit="return confirm('Are you sure you want to deny this loan?')">
                                                    {{ csrf_field() }}
                                                    <input type="hidden" value="{{ $loan->id }}" name="loanID">
                                                    <input type="submit" name="denyLoan" class="btn btn-danger" value="Deny">
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="box box-warning">
            <div class="box-header with-border">
                <h3 class="box-title">Active Loans</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                        @if (count($activeLoans) === 0)
                            {{ App\Classes\Output::genAlert(["No active loans. Get your shit together Blackbird"], "info", "Well, fuck") }}
                        @else
                            <div class="table-responsive">
                                <table class="table table-striped table-hover dataTable" role="grid">
                                    <thead>
                                    <tr>
                                        <th>Leader</th>
                                        <th>Nation Name</th>
                                        <th>Amount</th>
                                        <th>Due</th>
                                        <th>Days Left</th>
                                        <th>Delete</th>
                                        <th>Edit</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($activeLoans as $loan)
                                        <tr {{ $today->diffInDays(new \Carbon\Carbon($loan->due), false) < 0 ? 'class=danger' : '' }}>
                                            <td>{{ $loan->leader }}</td>
                                            <td><a href="https://politicsandwar.com/nation/id={{ $loan->nationID }}" target="_blank">{{ $loan->nationName }}</a></td>
                                            <td>${{ number_format($loan->amount) }}</td>
                                            <td>{{ $loan->due }}</td>
                                            <td>{{ $today->diffInDays(new \Carbon\Carbon($loan->due), false) }} days</td>
                                            <td>
                                                <form method="post" onsubmit="return confirm('Are you sure you want to delete this loan?')">
                                                    {{ csrf_field() }}
                                                    <input type="hidden" value="{{ $loan->id }}" name="loanID">
                                                    <input type="submit" name="deleteLoan" class="btn btn-danger" value="Delete">
                                                </form>
                                            </td>
                                            <td><a href="{{ url("/lookup/$loan->code") }}" class="btn btn-info">Edit</a></td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="box box-warning">
                    <div class="box-header with-border">
                        <h3 class="box-title">Manually Send/Add Loan</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <form method="post">
                            <div class="form-group">
                                <label>Nation ID</label>
                                <input type="number" name="nID" class="form-control" placeholder="Nation ID" required>
                            </div>
                            <div class="form-group">
                                <label>Amount</label>
                                <input type="number" name="amount" class="form-control" placeholder="Amount" required>
                            </div>
                            <div class="form-group">
                                <label>Due</label>
                                <input type="date" name="due" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Send Money or Not</label>
                                <div class="radio">
                                    <label><input type="radio" name="sendMoney" value="yes" class="radio"> Yes</label>
                                </div>
                                <div class="radio">
                                    <label><input type="radio" name="sendMoney" value="no" class="radio" checked>No</label>
                                </div>
                                <p class="help-block">If yes, the money will be sent like a normal loan. If no, no money will be sent but the loan will still be tracked by the program.</p>
                            </div>
                            <div class="form-group">
                                {{ csrf_field() }}
                                <input type="submit" name="manualLoan" value="Send" class="btn btn-warning">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="box box-warning">
            <div class="box-header with-border">
                <h3 class="box-title">Logs</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                        <i class="fa fa-minus"></i></button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
                        <i class="fa fa-times"></i></button>
                </div>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-hover table-striped table-bordered text-capitalize">
                        <tr>
                            <th>Timestamp</th>
                            <th>User</th>
                            <th>Message</th>
                        </tr>
                        @foreach ($logs as $log)
                            <tr>
                                <td>{{ $log->timestamp }}</td>
                                <td>{{ $log->username }}</td>
                                <td>
                                    {{ $log->message }}
                                    @if ($log->reasons != null)
                                        <a href="javascript://" class="text-danger" title="Errors" data-toggle="popover" data-trigger="focus" data-html="true" data-content="
                                        <ul>
                                        @foreach (\json_decode($log->reasons) as $reason)
                                                <li>{!! $reason !!}</li>
                                        @endforeach
                                                </ul>
                                                "><i class="fa fa-exclamation-triangle" aria-hidden="true"></i></a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
            <div class="box-footer">
                <p>
                    <a class="text-center" href="{{ url("/admin/logs/loan") }}">View All</a>
                </p>
            </div>
        </div>
    </section>
    <script>
        var lineChartData = {
            labels : [
                @foreach ($stats->loanHistory as $date)
                "{{ $date->date }}",
                @endforeach
            ],
            datasets : [
                {
                    label : "Total Loans",
                    backgroundColor : "rgba(224, 142, 11, 0.7)",
                    data : [
                        @foreach ($stats->loanHistory as $loan)
                            {{ $loan->value }},
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
                    }
                }
            });
        };
    </script>
@endsection