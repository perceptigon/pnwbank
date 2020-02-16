@extends('layouts.app')

@section('content')
    <h1 class="text-center">Accounts</h1>
    @if (Auth::user()->nID == 0 || Auth::user()->nID === null)
        @include("bankAccounts.templates.noNID")
    @elseif ($accounts->count() === 0)
        @include("bankAccounts.templates.noAccounts")
    @else
        @include("bankAccounts.templates.overview")
        @include("bankAccounts.templates.transfer")
    @endif
@endsection