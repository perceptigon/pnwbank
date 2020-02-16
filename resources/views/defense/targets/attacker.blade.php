@extends('layouts.admin')

@php

    use App\Models\Defender;
    use App\Models\Attacker;

    $attacker = Attacker::where('id', $id)->first();
    $assignments = $attacker->assignments;
    $defenderIDs = [];

    $min = $attacker->score * 0.75;
    $max = $attacker->score * 1.75;
    $inRanges = Defender::whereBetween('score', [$min, $max])->get();
    $inRanges = $inRanges->sortByDesc('cities');

@endphp

@section('content')

    <section class="content-header">
        <h1>ATTACKER <strong>{{ $attacker->nName }}</strong></h1>
    </section>
    <section class="content">
        @include("admin.alerts")
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">Attacking Nation</h3>
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
                            <th>Nation</th>
                            <th>Ruler</th>
                            <th>Cities</th>
                            <th>Score</th>
                            <th>Soldiers</th>
                            <th>Tanks</th>
                            <th>Aircraft</th>
                            <th>Slots</th>
                        </tr>

                        <tr>
                            <td>{{ $attacker->nName }}</td>
                            <td>{{ $attacker->nRuler }}</td>
                            <td>{{ $attacker->cities }}</td>
                            <td>{{ $attacker->score }}</td>
                            <td>{{ $attacker->soldiers }}</td>
                            <td>{{ $attacker->tanks }}</td>
                            <td>{{ $attacker->aircraft }}</td>
                            <td>{{ $attacker->slots }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div align="center">
            <a href="{{ url("/defense/attackers") }}"><button class="btn btn-success"><strong>Back to Attackers</strong></button></a>
            <a href="{{ url("/defense/targets") }}"><button class="btn btn-info"><strong>Home</strong></button></a>
        </div>
        <br>
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">Assigned Targets</h3>
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
                            <th>Nation</th>
                            <th>Ruler</th>
                            <th>Cities</th>
                            <th>Score</th>
                            <th>Soldiers</th>
                            <th>Tanks</th>
                            <th>Aircraft</th>
                            <th>Slots</th>
                            <th>Assign</th>
                        </tr>

                        @if($assignments->count() == 0)
                            <tr>
                                <th>There are no defenders assigned to this attacker.</th>
                            </tr>
                        @else
                            @foreach($assignments as $assignment)
                                @php(array_push($defenderIDs, $assignment->defender->id))
                                <tr>
                                    <td>{{ $assignment->defender->nName }}</td>
                                    <td>{{ $assignment->defender->nRuler }}</td>
                                    <td>{{ $assignment->defender->cities }}</td>
                                    <td>{{ $assignment->defender->score }}</td>
                                    <td>{{ $assignment->defender->soldiers }}</td>
                                    <td>{{ $assignment->defender->tanks }}</td>
                                    <td>{{ $assignment->defender->aircraft }}</td>
                                    <td>{{ $assignment->defender->slots }}</td>
                                    <td>
                                        <form method="POST">
                                            <button class="btn btn-danger" value="unassign" name="assign">Unassign</button>
                                            <input type="hidden" value="{{ $assignment->defender->id }}" name="defender_id">
                                            <input type="hidden" value="{{ $attacker->id }}" name="attacker_id">
                                            {{ csrf_field() }}
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </table>
                </div>
            </div>
        </div>
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">Assignable Targets</h3>
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
                            <th>Nation</th>
                            <th>Ruler</th>
                            <th>Alliance</th>
                            <th>Cities</th>
                            <th>Score</th>
                            <th>Soldiers</th>
                            <th>Tanks</th>
                            <th>Aircraft</th>
                            <th>Slots</th>
                            <th>Assign</th>
                        </tr>

                        @if($inRanges->count() == 0)
                            <tr>
                                <th>There are no attackers in range to assign to this defender.</th>
                            </tr>
                        @else
                            @foreach($inRanges as $inRange)
                                @if(!in_array($inRange->id, $defenderIDs))
                                    <tr>
                                        <td>{{ $inRange->nName }}</td>
                                        <td>{{ $inRange->nRuler }}</td>
                                        <td>{{ $inRange->alliance }}</td>
                                        <td>{{ $inRange->cities }}</td>
                                        <td>{{ $inRange->score }}</td>
                                        <td>{{ $inRange->soldiers }}</td>
                                        <td>{{ $inRange->tanks }}</td>
                                        <td>{{ $inRange->aircraft }}</td>
                                        <td>{{ $inRange->slots }}</td>
                                        <td>
                                            <form method="POST">
                                                <button class="btn btn-success" value="assign" name="assign">Assign</button>
                                                <input type="hidden" value="{{ $inRange->id }}" name="defender_id">
                                                <input type="hidden" value="{{ $attacker->id }}" name="attacker_id">
                                                {{ csrf_field() }}
                                            </form>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </section>

@endsection