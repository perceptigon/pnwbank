@extends('layouts.admin')

@section('content')
    <section class="content-header">
        <h1>Edit {{ $user->username }}</h1>
    </section>
    <section class="content">
        @include("admin.alerts")
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="box box-warning">
                    <div class="box-header with-border">
                        <h3 class="box-title">Edit User</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <form method="post">
                            <div class="form-group">
                                <label>Username</label>
                                <input type="text" name="username" class="form-control" value="{{ $user->username }}">
                            </div>
                            <div class="form-group">
                                <label>Title</label>
                                <input type="text" name="title" class="form-control" value="{{ $user->title }}">
                            </div>
                            <div class="form-group">
                                <label>Nation ID</label>
                                <input type="number" name="nID" class="form-control" value="{{ $user->nID }}">
                                <p class="help-block"><strong>Warning</strong>: Bank accounts are tied to nation IDs, NOT users. If you change their nation ID, they may not be able to access their bank accounts and could access someone else's. This also does no verification, so be careful.</p>
                            </div>
                            <div class="form-group">
                                <div class="checkbox">
                                    <label><input type="checkbox" name="isAdmin" value="1" {{ $user->isAdmin === 1 ? 'checked' : '' }} class="checkbox"> Admin</label>
                                </div>
                            </div>
                            <h4>Permissions</h4>
                            <div class="form-group">
                                <h5>Loans</h5>
                                <label class="radio-inline">
                                    <input type="radio" name="loans" value="yes" {{ ($perms["loans"] ?? "no") === "yes" ? 'checked' : '' }}>Yes
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="loans" value="no" {{ ($perms["loans"] ?? "no") === "no" ? 'checked' : '' }}>No
                                </label>
                            </div>
                            <div class="form-group">
                                <h5>Accounts</h5>
                                <label class="radio-inline">
                                    <input type="radio" name="accounts" value="yes" {{ ($perms["accounts"] ?? "no") === "yes" ? 'checked' : '' }}>Yes
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="accounts" value="no" {{ ($perms["accounts"] ?? "no") === "no" ? 'checked' : '' }}>No
                                </label>
                            </div>
                            <div class="form-group">
                                <h5>Grants</h5>
                                <label class="radio-inline">
                                    <input type="radio" name="grants" value="yes" {{ ($perms["grants"] ?? "no") === "yes" ? 'checked' : '' }}>Yes
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="grants" value="no" {{ ($perms["grants"] ?? "no") === "no" ? 'checked' : '' }}>No
                                </label>
                            </div>
                            <div class="form-group">
                                <h5>Market</h5>
                                <label class="radio-inline">
                                    <input type="radio" name="market" value="yes" {{ ($perms["market"] ?? "no") === "yes" ? 'checked' : '' }}>Yes
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="market" value="no" {{ ($perms["market"] ?? "no") === "no" ? 'checked' : '' }}>No
                                </label>
                            </div>
                            <div class="form-group">
                                <h5>Settings</h5>
                                <label class="radio-inline">
                                    <input type="radio" name="settings" value="yes" {{ ($perms["settings"] ?? "no") === "yes" ? 'checked' : '' }}>Yes
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="settings" value="no" {{ ($perms["settings"] ?? "no") === "no" ? 'checked' : '' }}>No
                                </label>
                            </div>
                            <div class="form-group">
                                <h5>Stratton Oakmont</h5>
                                <label class="radio-inline">
                                    <input type="radio" name="so" value="yes" {{ ($perms["so"] ?? "no") === "yes" ? 'checked' : '' }}>Yes
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="so" value="no" {{ ($perms["so"] ?? "no") === "no" ? 'checked' : '' }}>No
                                </label>
                            </div>
                            <div class="form-group">
                                <h5>Users</h5>
                                <label class="radio-inline">
                                    <input type="radio" name="users" value="yes" {{ ($perms["users"] ?? "no") === "yes" ? 'checked' : '' }}>Yes
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="users" value="no" {{ ($perms["users"] ?? "no") === "no" ? 'checked' : '' }}>No
                                </label>
                            </div>
                            <div class="form-group">
                                <h5>Taxes</h5>
                                <label class="radio-inline">
                                    <input type="radio" name="taxes" value="yes" {{ ($perms["taxes"] ?? "no") === "yes" ? 'checked' : '' }}>Yes
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="taxes" value="no" {{ ($perms["taxes"] ?? "no") === "no" ? 'checked' : '' }}>No
                                </label>
                            </div>
                            <div class="form-group">
                                <h5>Members</h5>
                                <label class="radio-inline">
                                    <input type="radio" name="members" value="yes" {{ ($perms["members"] ?? "no") === "yes" ? 'checked' : '' }}>Yes
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="members" value="no" {{ ($perms["members"] ?? "no") === "no" ? 'checked' : '' }}>No
                                </label>
                            </div>
                            <div class="form-group">
                                <h5>Targets</h5>
                                <label class="radio-inline">
                                    <input type="radio" name="targets" value="yes" {{ ($perms["targets"] ?? "no") === "yes" ? 'checked' : '' }}>Yes
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="targets" value="no" {{ ($perms["targets"] ?? "no") === "no" ? 'checked' : '' }}>No
                                </label>
                            </div>

                            <div class="form-group">
                                <input type="hidden" name="id" value="{{ $user->id }}">
                                {{ csrf_field() }}
                                <input type="submit" value="Edit" name="editUser" class="btn btn-primary">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="box box-warning">
            <div class="box-header with-border">
                <h3 class="box-title">Accounts</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Money</th>
                            <th>Coal</th>
                            <th>Oil</th>
                            <th>Uranium</th>
                            <th>Iron</th>
                            <th>Bauxite</th>
                            <th>Gas</th>
                            <th>Munitions</th>
                            <th>Steel</th>
                            <th>Aluminum</th>
                            <th>Food</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($user->accounts as $account)
                            <tr>
                                <td><a href="{{ url("/accounts/".$account->id) }}">{{ $account->name }}</a></td>
                                <td>${{ number_format($account->money, 2) }}</td>
                                <td>{{ number_format($account->coal, 2) }}</td>
                                <td>{{ number_format($account->oil, 2) }}</td>
                                <td>{{ number_format($account->uranium, 2) }}</td>
                                <td>{{ number_format($account->iron, 2) }}</td>
                                <td>{{ number_format($account->bauxite, 2) }}</td>
                                <td>{{ number_format($account->gas, 2) }}</td>
                                <td>{{ number_format($account->munitions, 2) }}</td>
                                <td>{{ number_format($account->steel, 2) }}</td>
                                <td>{{ number_format($account->aluminum, 2) }}</td>
                                <td>{{ number_format($account->food, 2) }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </section>
@endsection