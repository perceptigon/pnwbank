@extends('layouts.app')

@section('content')
    <h1 class="text-center text-capitalize">{{ $account->name }}</h1>
    <div class="panel panel-default">
        <div class="panel-heading">Balance</div>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-hover table-striped">
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
                            <td>${{ number_format($account->money, 2) }}</td>
                            <td>{{ number_format($account->coal, 2) }}</td>
                            <td>{{ number_format($account->oil, 2) }}</td>
                            <td>{{ number_format($account->uranium, 2) }}</td>
                            <td>{{ number_format($account->lead, 2) }}</td>
                            <td>{{ number_format($account->iron, 2) }}</td>
                            <td>{{ number_format($account->bauxite, 2) }}</td>
                            <td>{{ number_format($account->gas, 2) }}</td>
                            <td>{{ number_format($account->munitions, 2) }}</td>
                            <td>{{ number_format($account->steel, 2) }}</td>
                            <td>{{ number_format($account->aluminum, 2) }}</td>
                            <td>{{ number_format($account->food, 2) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @include("bankAccounts.templates.deposit")
    @include("bankAccounts.templates.transactions")
    @if (Auth::user()->isAdmin && Gate::allows("accounts") && 
        @include("bankAccounts.templates.edit")
    @endif
@endsection