@extends('layouts.admin')

@section('content')
    <section class="content-header">
        <h1>Users</h1>
    </section>
    <section class="content">
        @include("admin.alerts")
        <div class="box box-warning">
            <div class="box-header with-border">
                <h3 class="box-title">Search</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                        <i class="fa fa-minus"></i></button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
                        <i class="fa fa-times"></i></button>
                </div>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-offset-4 col-md-4">
                        <form method="get">
                            <div class="form-group">
                                <label class="control-label">Username</label>
                                <div class="input-group">
                                    <input type="text" placeholder="Username" name="username" class="form-control" required>
                                    <span class="input-group-btn">
                                        <input type="submit" value="Search" class="btn btn-warning">
                                    </span>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="box box-warning">
            <div class="table-responsive">
                <table class="table table-hover">
                    <tr>
                        <th>Username</th>
                        <th>Is Admin</th>
                        <th>Edit</th>
                    </tr>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->username }}</td>
                            <td class="text-{{ ($user->isAdmin) ? "success" : "danger" }}">{{ ($user->isAdmin) ? "Yes" : "No" }}</td>
                            <td><a href="{{ url("/admin/users/edit/$user->id") }}">Edit</a></td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
        <div class="text-center">
            {{ $users->links() }}
        </div>
    </section>
@endsection