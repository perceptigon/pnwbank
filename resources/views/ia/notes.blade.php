@extends('layouts.admin')

@php

    use App\Models\Noob;
    $noob = Noob::where('id', $noob_id)->first();

@endphp

@section('content')
    <section class="content-header">
        <h1>City Grants</h1>
    </section>
    <section class="content">
        @include("admin.alerts")
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="box box-warning" id="lookup">
                    <div class="box-header with-border">
                        <h3 class="box-title">Notes for {{ $noob->forum_name }}</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                                <i class="fa fa-minus"></i></button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
                                <i class="fa fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body">
                        <form method="post">
                            <div class="form-group label-floating">
                                <textarea name="note" id="note" cols="30" rows="10" class="form-control">{{ $noob->notes }}</textarea>
                                <br>
                                <div class="form-group text-center">
                                    {{ csrf_field() }}
                                    <input type="submit" name="submit" class="btn btn-raised btn-success" value="Update">
                                    <input type="submit" name="submit" class="btn btn-raised btn-danger" value="Delete">
                                    <input type="hidden" value="{{$noob->id}}" name="noob_id">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection