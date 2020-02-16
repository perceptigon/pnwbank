@extends('layouts.admin')

@section('content')
    <link href="{{ url("/lib/prism/prism.css") }}" rel="stylesheet">
    <section class="content-header">
        <h1>Members</h1>
    </section>
    <section class="content">
        @include("admin.alerts")
        <div class="row">
            <div class="col-md-6">
                <div class="box box-warning">
                    <div class="box-header with-border">
                        <h3 class="box-title">City Breakdown Graph</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                                <i class="fa fa-minus"></i></button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
                                <i class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <canvas id="cityBreakdown"/>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="box box-warning">
                    <div class="box-header with-border">
                        <h3 class="box-title">City History</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                                <i class="fa fa-minus"></i></button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
                                <i class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <canvas id="cityHistory"/>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
                <div class="box box-warning">
                    <div class="box-header with-border">
                        <h3 class="box-title">City Grant Reminder</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                                <i class="fa fa-minus"></i></button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
                                <i class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <p>Will send nations who are eligible for a city grant a message reminding them that they are eligible for a city grant. Uses the same verification that is used when verifying an actual request so it checks literally everything.</p>
                        <a href="#" class="btn btn-warning btn-lg" data-toggle="modal" data-target="#cityGrantReminder">Setup Message</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="box box-warning">
                    <div class="box-header with-border">
                        <h3 class="box-title">Audit Nations</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                                <i class="fa fa-minus"></i></button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
                                <i class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <p>Will audit all of our nations based on the code below and will send them that audit in a message. Includes a city grant reminder so don't send both.</p>
                        <a href="#" class="btn btn-default btn-lg pull-right" data-toggle="modal" data-target="#auditCode">View Code</a>
                        <form method="post" onsubmit="return confirm('Are you sure you want to run this job?')">
                            <div class="form-group">
                                <input type="submit" class="btn btn-warning btn-lg" value="Send" name="auditNations">
                                {{ csrf_field() }}
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="box box-warning">
                    <div class="box-header with-border">
                        <h3 class="box-title">Send Mass Message</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                                <i class="fa fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
                                <i class="fa fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body">
                        <p>Sends a message to everyone in BK. Useful if you want to send out information about something and you don't want it to be in an announcement</p>
                        <a href="#" class="btn btn-warning btn-lg" data-toggle="modal" data-target="#massMessage">Create Message</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="box box-warning">
                    <div class="box-header with-border">
                        <h3 class="box-title">Send Sign In Reminder</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                                <i class="fa fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
                                <i class="fa fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body">
                        <p>Sends a message to everyone who has not signed in yet in this period. Everyone with a "No" in the "Signed In" column will be messaged</p>
                        <form method="post" onsubmit="return confirm('Are you sure you want to run this job?')">
                            <input type="submit" name="signInReminder" class="btn btn-warning btn-lg" value="Send">
                            {{ csrf_field() }}
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div id="auditCode" class="modal fade" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Audit Code</h4>
                    </div>
                    <div class="modal-body">
                        <pre class="line-numbers"><code class="language-php">{{ $audit }}</code></pre>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <div id="massMessage" class="modal fade" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Send Mass Message</h4>
                    </div>
                    <div class="modal-body">
                        <form method="post" id="messageForm" onsubmit="return confirm('Are you sure you want to run this job?')">
                            <div class="form-group">
                                <input type="text" name="subject" id="subject" placeholder="Message Subject" class="form-control">
                            </div>
                            <div class="form-group">
                                <textarea rows="10" name="message" id="message" placeholder="Message Body" class="form-control"></textarea>
                            </div>
                            {{ csrf_field() }}
                        </form>
                    </div>
                    <div class="modal-footer">
                        <input type="submit" name="massMessage" form="messageForm" class="btn btn-success">
                        <button type="button" class="btn btn-default" data-dismiss="modal" value="Send">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <div id="cityGrantReminder" class="modal fade" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Send City Grant Reminders</h4>
                    </div>
                    <div class="modal-body">
                        <form method="post" id="cityReminderForm" onsubmit="return confirm('Are you sure you want to run this job?')">
                            <div class="form-group">
                                <label>Select which city tiers to send this message to</label>
                                <span class="help-block">This script will send a message to members with the selected city numbers. EX: You select city 7, all members who are eligible for a city grant with 7 cities will receive a message</span>
                                <a href="#" id="selectAll">All</a> / <a href="#" id="selectNone">None</a>
                                <div class="row">
                                    @for ($x = 1; $x <= 25; $x++)
                                        <div class="col-sm-3">
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" class="cityCheckbox" name="cities[]" value="{{ $x }}" {{ ($x < 8 ? "checked" : "") }}> City {{ $x }}
                                                </label>
                                            </div>
                                        </div>
                                    @endfor
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Message</label>
                                <span class="help-block">Hi {nation leader},</span>
                                <textarea required rows="5" name="message" id="message" placeholder="Message Body" class="form-control">This message is being sent to you to remind you that you are eligible for another city grant. Please go [link={{ url("/grants/city") }}]here[/link] to request a new city grant</textarea>
                                <span class="help-block">{{ \App\Classes\PWClient::endMessage() }}</span>
                            </div>
                            {{ csrf_field() }}
                        </form>
                    </div>
                    <div class="modal-footer">
                        <input type="submit" name="cityGrantReminder" form="cityReminderForm" class="btn btn-success">
                        <button type="button" class="btn btn-default" data-dismiss="modal" value="Send">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="box box-warning">
            <div class="box-header with-border">
                <h3 class="box-title">Nations</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                        <i class="fa fa-minus"></i></button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
                        <i class="fa fa-times"></i></button>
                </div>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-hover table-striped sortable">
                        <tr>
                            <th>Leader</th>
                            <th>Score</th>
                            <th>Cities</th>
                            <th>Steel</th>
                            <th>Gas</th>
                            <th>Aluminum</th>
                            <th>Munitions</th>
                            <th>Money</th>
                            <th>MMR Score</th>
                            <th>Signed In</th>
                            <th>Profile</th>
                        </tr>

                        @foreach ($nations as $nation)
                            <tr>
                                <td><a href="https://politicsandwar.com/nation/id={{ $nation->nID }}" target="_blank">{{ $nation->leader }}</a></td>
                                <td>{{ number_format($nation->score) }}</td>
                                <td>{{ $nation->cities }}</td>
                                <td>{{ number_format($nation->steel) }}</td>
                                <td>{{ number_format($nation->gas) }}</td>
                                <td>{{ number_format($nation->aluminum) }}</td>
                                <td>{{ number_format($nation->munitions) }}</td>
                                <td>${{ number_format($nation->money) }}</td>
                                <td>{{ round(\App\Defense\Warchest::mmrScoreFromDefNations($nation), 2) }}</td>
                                <td class="{{ ($nation->hasSignedIn) ? "success" : "danger" }}">{{ ($nation->hasSignedIn) ? "Yes" : "No" }}</td>
                                <td><a href="{{ url("/admin/members/$nation->nID") }}">Profile</a></td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </section>

    <script>
        var doughnutData = {
            type: 'bar',
            data : {
                datasets: [{
                    data: [
                        @foreach ($cityBreakdown as $break)
                            {{ $break->count }},
                        @endforeach
                    ],
                    backgroundColor: "#000000"
                }],
                labels: [
                    @foreach ($cityBreakdown as $break)
                        "City {{ $break->cities }}",
                    @endforeach
                ]
            },
            options: {
                legend: {
                    display: false
                },
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        };

        var cityHistory = {
            type: "line",
            data : {
                labels : [
                    @foreach ($cityHistory as $date)
                            "{{ \Carbon\Carbon::createFromFormat("Y-m-d H:s:i", $date->date)->toDateString() }}",
                    @endforeach
                ],
                datasets : [{
                    label : "City History",
                    backgroundColor : "rgba(224, 142, 11, 0.7)",
                    data : [
                        @foreach ($cityHistory as $his)
                            @if($his->amount > 3000)
                                {{ $his->amount / 2}},
                            @else
                                {{ $his->amount }},
                            @endif
                        @endforeach
                    ]}
                ],
            }
        };

        window.onload = function() {
            var ctx2 = document.getElementById("cityBreakdown").getContext("2d");
            window.myPie = new Chart(ctx2, doughnutData);
            var ctx = document.getElementById("cityHistory");
            window.myLine = new Chart(ctx, cityHistory);
        };

        function getRandomColor() {
            var letters = '0123456789ABCDEF';
            var color = '#';
            for (var i = 0; i < 6; i++ ) {
                color += letters[Math.floor(Math.random() * 16)];
            }
            return color;
        }

        $("#selectAll").click(function() {
            $(".cityCheckbox").prop('checked', true).parent().addClass("checked");
        });

        $("#selectNone").click(function() {
            $(".cityCheckbox").prop('checked', false).parent().removeClass("checked");
        });
    </script>

    <script src="{{ url("/lib/prism/prism.js") }}"></script>

@endsection