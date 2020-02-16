@extends('layouts.admin')

@section('content')
    <section class="content-header">
        <h1>Logs</h1>
    </section>
    <section class="content">
        @include("admin.alerts")
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="box box-warning">
                    <div class="box-header with-border">
                        <h3 class="box-title">Category</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                                <i class="fa fa-minus"></i></button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
                                <i class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <form method="get">
                            <div class="form-group">
                                <label>Category</label>
                                <select name="c" class="form-control" id="category">
                                    <option value="all">All</option>
                                    <option value="loan">Loans</option>
                                    <option value="cityGrant">City Grants</option>
                                    <option value="market">Market</option>
                                    <option value="entrance">Entrance</option>
                                    <option value="id">CIA</option>
                                    <option value="activity">Activity Grants</option>
                                    <option value="system">System</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <input type="button" class="btn btn-default" value="Search" onclick="getLogs()">
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
                    <table class="table table-hover table-striped">
                        <tr class="topRow">
                            <th>Timestamp</th>
                            <th>Category</th>
                            <th>User</th>
                            <th>Message</th>
                        </tr>
                        @foreach ($logs as $log)
                            <tr>
                                <td>{{ $log->timestamp }}</td>
                                <td class="text-capitalize">{{ $log->category }}</td>
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
                <div class="text-center">
                    {{ $logs->links() }}
                </div>
            </div>
        </div>
    </section>
    <script>
        function getLogs() {
            // Get category
            var category = document.getElementById("category").value;
            // Redirect to page
            document.location.href="{{ url("/admin/logs") }}/" + category;
        }
    </script>
@endsection