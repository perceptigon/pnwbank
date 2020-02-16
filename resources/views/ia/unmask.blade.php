@extends('layouts.admin')

@php

    use App\Models\Unmask;
    $unmasks = Unmask::all();

@endphp

@section('content')
    <section class="content-header">
        <h1>Unmask</h1>
    </section>
    <section class="content">
        @include("admin.alerts")
        <div class="box box-warning" id="lookup">
            <div class="box-header with-border">
                <h3 class="box-title">Masked forum accounts that are no longer members:</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                        <i class="fa fa-minus"></i></button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
                        <i class="fa fa-times"></i>
                    </button>
                </div>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <tr>
                            <th>Forum Name</th>
                            <th>Forum ID</th>
                            <th>Nation ID</th>
                        </tr>

                        @foreach ($unmasks as $unmask)
                            <tr>
                                <td><a href="{{url("https://bkpw.net/profile/$unmask->fID-$unmask->f_name")}}">{{ $unmask->f_name }}</a></td>
                                <td>{{ $unmask->fID }}</td>
                                <td>{{ $unmask->nID }}</td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </section>

@endsection