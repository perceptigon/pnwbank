@extends('layouts.admin')

@section('content')
        {{ App\Classes\Output::genAlert(["You are not authorized to view this page"], "danger", "Error!") }}
@endsection