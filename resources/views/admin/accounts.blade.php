@extends('layouts.admin')

@section('content')
    <section class="content-header">
        <h1>Bank Accounts</h1>
    </section>
    <section class="content">
        @include("admin.alerts")

        <div class="box box-warning">
            <div class="box-header with-border">
                <h3 class="box-title">Total In Accounts</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                        <i class="fa fa-minus"></i></button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
                        <i class="fa fa-times"></i></button>
                </div>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>Money</th>
                            <th>Coal</th>
                            <th>Oil</th>
                            <th>Uranium</th>
                            <th>Lead</th>
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
                        <tr>
                            <td>${{ number_format($accounts->sum("money"), 2) }}</td>
                            <td>{{ number_format($accounts->sum("coal"), 2) }}</td>
                            <td>{{ number_format($accounts->sum("oil"), 2) }}</td>
                            <td>{{ number_format($accounts->sum("uranium"), 2) }}</td>
                            <td>{{ number_format($accounts->sum("lead"), 2) }}</td>
                            <td>{{ number_format($accounts->sum("iron"), 2) }}</td>
                            <td>{{ number_format($accounts->sum("bauxite"), 2) }}</td>
                            <td>{{ number_format($accounts->sum("gas"), 2) }}</td>
                            <td>{{ number_format($accounts->sum("munitions"), 2) }}</td>
                            <td>{{ number_format($accounts->sum("steel"), 2) }}</td>
                            <td>{{ number_format($accounts->sum("aluminum"), 2) }}</td>
                            <td>{{ number_format($accounts->sum("food"), 2) }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="box box-warning">
            <div class="box-header with-border">
                <h3 class="box-title">Accounts</h3>
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
                        <thead>
                        <tr>
                            <th>Nation ID</th>
                            <th>Name</th>
                            <th>Money</th>
                            <th>Coal</th>
                            <th>Oil</th>
                            <th>Uranium</th>
                            <th>Lead</th>
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
                        @foreach ($accounts as $acc)
                            <tr>
                                <td><a href="https://politicsandwar.com/nation/id={{ $acc->nID }}">{{ $acc->nID }}</a></td>
                                <td><a href="{{ url("/accounts/".$acc->id) }}" target="_blank">{{ $acc->name }}</a></td>
                                <td>${{ number_format($acc->money, 2) }}</td>
                                <td>{{ number_format($acc->coal, 2) }}</td>
                                <td>{{ number_format($acc->oil, 2) }}</td>
                                <td>{{ number_format($acc->uranium, 2) }}</td>
                                <td>{{ number_format($acc->lead, 2) }}</td>
                                <td>{{ number_format($acc->iron, 2) }}</td>
                                <td>{{ number_format($acc->bauxite, 2) }}</td>
                                <td>{{ number_format($acc->gas, 2) }}</td>
                                <td>{{ number_format($acc->munitions, 2) }}</td>
                                <td>{{ number_format($acc->steel, 2) }}</td>
                                <td>{{ number_format($acc->aluminum, 2) }}</td>
                                <td>{{ number_format($acc->food, 2) }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection