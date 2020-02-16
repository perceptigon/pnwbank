@extends('layouts.admin')

@php
    use App\Models\Noob;
    $noobs = Noob::whereIn('forum_mask', [77,133])->get();
@endphp

@section('content')
    <section class="content-header">
        <h1>Academy</h1>
    </section>
    <section class="content">
        @include("admin.alerts")
        <div class="box box-warning">
            <div class="box-header with-border">
                <h3 class="box-title">Academy Members</h3>
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
                        <tr>
                            <th>Nation ID</th>
                            <th>Nation Name</th>
                            <th>Nation Ruler</th>
                            <th>Forum Name</th>
                            <th>Rank</th>
                            <th>Actions</th>
                            <th>Notes</th>
                        </tr>

                        @foreach($noobs as $noob)
                            @if(strtotime($noob->created_at) < strtotime('-3 day') && $noob->forum_mask == 77)
                                <tr class="danger">
                            @elseif(strtotime($noob->created_at) < strtotime('-10 day') && $noob->forum_mask == 133)
                                <tr class="danger">
                            @elseif(strtotime($noob->created_at) < strtotime('-8 day') && $noob->forum_mask == 133)
                                <tr class="warning">
                            @else
                                <tr>
                            @endif
                                <td><a href="https://politicsandwar.com/nation/id={{ $noob->nation_id }}">{{ $noob->nation_id }}</a></td>
                                <td>{{ $noob->nation_name }}</td>
                                <td>{{ $noob->nation_ruler }}</td>
                                <td>{{ $noob->forum_name }}</td>
                                @if ($noob->forum_mask == 77)
                                    <td>Academy Student</td>
                                @elseif ($noob->forum_mask == 133)
                                    <td>Squire</td>
                                @endif
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