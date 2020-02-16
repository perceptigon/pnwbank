@extends('layouts.admin')

@php

    use App\Models\Noob;
    $noobs = Noob::whereNotIn('forum_mask', [3,77,133])->get();

@endphp

@section('content')
    <section class="content-header">
        <h1>Tracking</h1>
    </section>
    <section class="content">
        @include("admin.alerts")
        <div class="box box-warning" id="lookup">
            <div class="box-header with-border">
                <h3 class="box-title">Tracking</h3>
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
                            <th>Nation Name</th>
                            <th>Nation Ruler</th>
                            <th>Forum Name</th>
                            <th>Forum Mask</th>
                            <th>Actions</th>
                            <th>Notes</th>
                        </tr>

                        @foreach ($noobs as $noob)
                            @if(strtotime($noob->last_city_built) < strtotime('-12 day'))
                                <tr class="danger">
                            @elseif(strtotime($noob->last_city_built) < strtotime('-10 day'))
                                <tr class="warning">
                            @else
                                <tr>
                            @endif
                                <td><a href="https://politicsandwar.com/nation/id={{ $noob->nation_id }}" target="_blank">{{ $noob->nation_name }}</a></td>
                                <td>{{ $noob->nation_ruler }}</td>
                                <td>{{ $noob->forum_name }}</td>
                                <td>{{ ($noob->forum_mask == 77) ? "Academy Student" : "Squire" }}</td>
                                <td>
                                    <form method="POST" onsubmit="return confirm('Are you sure you want to remove this applicant?')">
                                        <button class="btn btn-danger" value="remove" name="remove">Remove</button>
                                        <input type="hidden" value="{{$noob->nation_id}}" name="nation_id">
                                        {{ csrf_field() }}
                                    </form>
                                </td>
                                <td>
                                    @if($noob->notes == ' ' || $noob->notes == '')
                                        <a href="{{url("/ia/notes/$noob->id")}}"><img src="{{ url("/images/sticky-gray.png") }}" height='40' width='40'></a>
                                    @else
                                        <a href="{{url("/ia/notes/$noob->id")}}" data-toggle="tooltip" title="{{$noob->notes}}"><img src="{{ url("/images/sticky.png") }}" height='40' width='40'></a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </section>

@endsection