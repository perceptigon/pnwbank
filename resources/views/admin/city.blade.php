@extends('layouts.admin')

@section('content')
    <section class="content-header">
        <h1>City Grants</h1>
    </section>
    <section class="content">
        @include("admin.alerts")
        <div class="row">
            <div class="col-md-3">
                <div class="info-box">
                    <span class="info-box-icon bg-aqua"><i class="fa fa-credit-card-alt"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Sent</span>
                        <span class="info-box-number">${{ number_format($stats->totalCityGrantsSent) }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="info-box">
                    <span class="info-box-icon bg-green"><i class="fa fa-check"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Grants Accepted</span>
                        <span class="info-box-number">{{ number_format($stats->cityGrantsApproved) }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="info-box">
                    <span class="info-box-icon bg-red"><i class="fa fa-ban"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Grants Denied</span>
                        <span class="info-box-number">{{ number_format($stats->cityGrantsDenied) }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="info-box">
                    <span class="info-box-icon bg-orange"><i class="fa fa-line-chart"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Sent Last 30 Days</span>
                        <span class="info-box-number">${{ number_format($stats->grantMonthly) }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="box box-warning">
            <div class="box-header with-border">
                <h3 class="box-title">Total City Grants Last 30 Days</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                        <i class="fa fa-minus"></i></button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
                        <i class="fa fa-times"></i></button>
                </div>
            </div>
            <div class="box-body">
                <canvas id="canvas" height="200" width="600"></canvas>
            </div>
        </div>
        <div class="box box-warning">
            <div class="box-header with-border">
                <h3 class="box-title">Pending City Grants</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                        <i class="fa fa-minus"></i></button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
                        <i class="fa fa-times"></i></button>
                </div>
            </div>
            <div class="box-body">
                @if (count($pendingGrants) === 0)
                    {{ App\Classes\Output::genAlert(["No pending city grants"]) }}
                @else
                    <div class="table-responsive">
                        <table class="table table-hover table-striped table-bordered">
                            <tr>
                                <th>Leader</th>
                                <th>Grant Num</th>
                                <th>Amount</th>
                                <th>Approve</th>
                                <th>Deny</th>
                            </tr>
                            @foreach ($pendingGrants as $grant)
                                <tr>
                                    <td><a href="https://politicsandwar.com/nation/id={{ $grant->nationID }}" target="_blank">{{ $grant->leader }}</a></td>
                                    <td>{{ $grant->cityNum }}</td>
                                    <td>${{ number_format($grant->amount) }}</td>
                                    <td>
                                        <form method="post" onsubmit="return confirm('Are you sure you want to approve this grant?')">
                                            <input type="submit" name="approveGrant" class="btn btn-success" value="Approve">
                                            {{ csrf_field() }}
                                            <input type="hidden" value="{{ $grant->id }}" name="grantID">
                                        </form>
                                    </td>
                                    <td>
                                        <form method="post" onsubmit="return confirm('Are you sure you want to deny this grant?')">
                                            <input type="submit" name="denyGrant" class="btn btn-danger" value="Deny">
                                            {{ csrf_field() }}
                                            <input type="hidden" value="{{ $grant->id }}" name="grantID">
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                @endif
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
                <div class="box box-warning" id="lookup" style="min-height: 350px">
                    <div class="box-header with-border">
                        <h3 class="box-title">Lookup Grant</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                                <i class="fa fa-minus"></i></button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
                                <i class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <form action="#lookup" method="post">
                            <div class="form-group">
                                <label>Grant ID</label>
                                <input type="number" name="gID" class="form-control" placeholder="Grant ID" required>
                            </div>
                            <div class="form-group">
                                <input type="submit" class="btn btn-warning" name="getGrant" value="Lookup">
                                {{ csrf_field() }}
                            </div>
                        </form>
            <span>
                @if (isset($cityReq->id))
                    <ul>
                        <li>Grant ID - {{ $cityReq->id }}</li>
                        <li>Nation ID - {{ $cityReq->nationID }}</li>
                        <li>Nation Name - {{ $cityReq->nationName }}</li>
                        <li>Leader - {{ $cityReq->leader }}</li>
                        <li>Grant # - {{ $cityReq->cityNum }}</li>
                        <li>Amount - ${{ number_format($cityReq->amount) }}</li>
                        <li>Is Pending - {{ $cityReq->isPending }}</li>
                        <li>Is Sent - {{ $cityReq->isSent }}</li>
                        <li>Is Denied - {{ $cityReq->isDenied }}</li>
                    </ul>
                @endif
            </span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="box box-warning" style="min-height: 350px">
                    <div class="box-header with-border">
                        <h3 class="box-title">Reset Grant Timer</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                                <i class="fa fa-minus"></i></button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
                                <i class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <form method="post">
                            <div class="form-group">
                                <label>Nation ID</label>
                                <input type="number" name="nID" class="form-control" placeholder="Nation ID" required>
                            </div>
                            <div class="form-group">
                                {{ csrf_field() }}
                                <input type="submit" class="btn btn-danger" name="resetGrantTimer" value="Reset">
                                <p class="help-block">The system prevents members from getting a grant within 10 days of their last grant. If you want, you can reset that for the member here</p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="box box-warning" style="min-height: 350px">
                    <div class="box-header with-border">
                        <h3 class="box-title">Reset Grant Number</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                                <i class="fa fa-minus"></i></button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
                                <i class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <form method="post">
                            <div class="form-group">
                                <label>Nation ID</label>
                                <input type="number" name="nID" class="form-control" placeholder="Nation ID" required>
                            </div>
                            <div class="form-group">
                                {{ csrf_field() }}
                                <input type="submit" class="btn btn-danger" name="resetGrantNum" value="Reset">
                                <p class="help-block">If for whatever reason you want to reset the grant number for a nation (usually if they re-rolled), you can do that here. (Warning: This will allow the nation to get multiple of the same # grant)</p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="box box-warning" style="min-height: 350px">
                    <div class="box-header with-border">
                        <h3 class="box-title">Manually Send Grant</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                                <i class="fa fa-minus"></i></button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
                                <i class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <form method="post">
                            <div class="form-group">
                                <label>Nation ID</label>
                                <input type="number" name="nID" class="form-control" placeholder="Nation ID" required>
                            </div>
                            <div class="form-group">
                                <label>Grant Number</label>
                                <input type="number" name="gNum" class="form-control" placeholder="1" max="20" required>
                            </div>
                            <div class="form-group">
                                <p class="help-block">This will manually send the grant as if they requested it. <strong>This will skip all checks and will send the grant.</strong></p>
                                {{ csrf_field() }}
                                <input type="submit" name="manualGrant" class="btn btn-warning" value="Send">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="box box-warning" id="grantTable">
            <div class="box-header with-border">
                <h3 class="box-title">Grants</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                        <i class="fa fa-minus"></i></button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
                        <i class="fa fa-times"></i></button>
                </div>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <tr>
                            <th>Grant</th>
                            <th>Amount</th>
                            <th>Inf/City</th>
                            <th>MMR</th>
                            <th>ID</th>
                            <th>NRF</th>
                            <th>Enabled</th>
                            <th>Edit</th>
                        </tr>
                        @foreach($grants as $grant)
                            <tr>
                                <td>{{ $grant->grantNum }}</td>
                                <td>{{ number_format($grant->amount) }}</td>
                                <td>{{ number_format($grant->infPerCity) }}</td>
                                <td>{{ $grant->mmrScore }}</td>
                                <td class="{{ $grant->irondome ? "success" : "warning" }}">{{ $grant->irondome ? "Yes" : "No" }}</td>
                                <td class="{{ $grant->NRF ? "success" : "warning" }}">{{ $grant->NRF ? "Yes" : "No" }}</td>
                                <td class="{{ $grant->enabled ? "success" : "warning" }}">{{ $grant->enabled ? "Yes" : "No" }}</td>
                                <td><a href='#' class='editGrant' data-id='{{ $grant->id }}' data-toggle='modal' data-target='#editGrant'>Edit</a></td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
            <div class="box-footer">
                <div class="text-center">
                    <button type="button" data-toggle="modal" data-target="#createGrant" class="btn btn-warning">Create A Grant</button>
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
                    <table class="table table-hover table-striped">
                        <tr>
                            <th>Timestamp</th>
                            <th>User</th>
                            <th>Message</th>
                        </tr>
                        @foreach ($logs as $log)
                            <tr>
                                <td>{{ $log->timestamp }}</td>
                                <td class="text-capitalize">{{ $log->username }}</td>
                                <td class="text-capitalize">
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
                <p class="text-center"><a href="{{ url("/admin/logs/cityGrant") }}">View All</a></p>
            </div>
        </div>
    </section>

    @include("admin.editGrant")
    @include("admin.createGrant")

    <script>
        var lineChartData = {
            labels : [
                @foreach ($stats->grantHistory as $d)
                    "{{ $d->date }}",
                @endforeach
            ],
            datasets : [
                {
                    label: "City Grants",
                    backgroundColor : "rgba(224, 142, 11, 0.7)",
                    data : [
                        @foreach ($stats->grantHistory as $g)
                            {{ $g->value }},
                        @endforeach
                    ]
                }
            ]

        };

        window.onload = function(){
            var ctx = document.getElementById("canvas").getContext("2d");
            window.myLine = new Chart(ctx, {
                type: 'line',
                data: lineChartData
            });
        };
    </script>
@endsection

@section("scripts")
    <script>
        $('a.editGrant').click(function(ev) {
            ev.preventDefault();
            var gID = $(this).data('id');
            var json;
            $.get("{{ url("api/gInfo") }}/" + gID, function(html) {
                replaceShit(html);
            });

            function replaceShit(json) {
                console.log(json.amount);
                document.getElementById("gNumber").value = json.grantNum;
                document.getElementById("gAmount").value = json.amount;
                document.getElementById("gInf").value = json.infPerCity;
                var ironParent = document.getElementById("gIron").parentElement; // Visually update the checkbox
                if (json.irondome == 1) {
                    document.getElementById("gIron").checked = true;
                    var ironParent = document.getElementById("gIron").parentElement; // Visually update the checkbox
                    $(ironParent).addClass("checked");
                }
                else {
                    document.getElementById("gIron").checked = false; // Check if it's false so if they go to another grant it is unchecked
                    $(ironParent).removeClass("checked");
                }
                var nrfParent = document.getElementById("gNRF").parentElement; // Visually update the checkbox
                if (json.NRF == 1) {
                    document.getElementById("gNRF").checked = true;
                    $(nrfParent).addClass("checked");
                }
                else {
                    document.getElementById("gNRF").checked = false;
                    $(nrfParent).removeClass("checked");
                }
                var enabledParent = document.getElementById("gEnabled").parentElement; // Visually update the checkbox
                if (json.enabled == 1) {
                    document.getElementById("gEnabled").checked = true;
                    $(enabledParent).addClass("checked");
                }
                else {
                    document.getElementById("gEnabled").checked = false;
                    $(enabledParent).removeClass("checked");
                }

                document.getElementById("gMMR").value = json.mmrScore;
                json.notes = json.notes.replace(/&lt;/g, "<"); // The < comes in weird so just replace it
                document.getElementById("gNotes").value = json.notes;
                document.getElementById("editGID").value = json.id;
                document.getElementById("delGID").value = json.id;
            }
        });
    </script>
@endsection