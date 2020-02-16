@extends('layouts.app')

@section('content')
    <h1 class="text-center">Bad Boy</h1>
    {{ \App\Classes\Output::genAlert(["You do not own that account"], "danger", "Bad Boy!") }}
@endsection