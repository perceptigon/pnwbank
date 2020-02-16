@extends('layouts.admin')

@section('content')
    <section class="content-header">
        <h1>NRF Grants</h1>
    </section>
    <section class="content">
        @include("admin.alerts")
        <div class="box box-warning">
            <div class="box-header with-border">
                <h3 class="box-title">Pending Grants</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                        <i class="fa fa-minus"></i></button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
                        <i class="fa fa-times"></i></button>
                </div>
            </div>
            <div class="box-body">
                @if (count($pendGrants) === 0)
                    {{ App\Classes\Output::genAlert(["No pending NRF grants"]) }}
                @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <tr>
                                <th>Timestamp</th>
                                <th>Leader</th>
                                <th>Approve</th>
                                <th>Deny</th>
                            </tr>
                            @foreach ($pendGrants as $req)
                                <tr>
                                    <td>{{ $req->created_at }}</td>
                                    <td><a href="https://politicsandwar.com/nation/id={{ $req->nID }}" target="_blank">{{ $req->leader }}</a></td>
                                    <td>
                                        <form method="post" onsubmit="confirm('Are you sure you want to approve this grant?')">
                                            <input type="submit" name="approveGrant" class="btn btn-success" value="Approve">
                                            {{ csrf_field() }}
                                            <input type="hidden" value="{{ $req->id }}" name="gID">
                                        </form>
                                    </td>
                                    <td>
                                        <form method="post" onsubmit="confirm('Are you sure you want to deny this grant?')">
                                            <input type="submit" name="denyGrant" class="btn btn-danger" value="Deny">
                                            {{ csrf_field() }}
                                            <input type="hidden" value="{{ $req->id }}" name="gID">
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection