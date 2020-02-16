@extends('layouts.admin')

@php

    use App\Models\Defender;
    use App\Models\Attacker;

    $defender = Defender::where('id', $id)->first();
    $assignments = $defender->assignments;
    $attackerIDs = [];

    $attackingCities = 0;
    $attackingScore = 0;
    $attackingSoldiers = 0;
    $attackingTanks = 0;
    $attackingAircraft = 0;
    $attackingShips = 0;

    //calculate totals
    foreach ($assignments as $assignment)
    {
        $attackingCities += $assignment->attacker->cities;
        $attackingScore += $assignment->attacker->score;
        $attackingSoldiers += $assignment->attacker->soldiers;
        $attackingTanks += $assignment->attacker->tanks;
        $attackingAircraft += $assignment->attacker->aircraft;
        $attackingShips += $assignment->attacker->ships;
    }

    $min = $defender->score * 0.57;
    $max = $defender->score * 1.33;
    $inRanges = Attacker::whereBetween('score', [$min, $max])->get();
    $inRanges = $inRanges->sortByDesc('cities');

@endphp

@section('content')

    <section class="content-header">
        <h1>DEFENDER <strong>{{ $defender->nName }}</strong></h1>
    </section>
    <section class="content">
        @include("admin.alerts")
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">Defending Nation</h3>
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
                            <th>Ships</th>
                            <th>Slots</th>
                        </tr>

                        <tr>
                            <td>{{ $defender->nName }}</td>
                            <td>{{ $defender->nRuler }}</td>
                            <td>{{ $defender->alliance }}</td>
                            <td>{{ $defender->cities }}</td>
                            <td>{{ $defender->score }}</td>
                            <td>{{ $defender->soldiers }}</td>
                            <td>{{ $defender->tanks }}</td>
                            <td>{{ $defender->aircraft }}</td>
                            <td>{{ $defender->ships }}</td>
                            <td>{{ $defender->slots }}</td>
                        </tr>

                        <tr>
                            <td></td>
                            <th>Total Attacking:</th>
                            <td></td>
                            <th>{{ $attackingCities }}</th>
                            <th>{{ $attackingScore }}</th>
                            <th>{{ $attackingSoldiers }}</th>
                            <th>{{ $attackingTanks }}</th>
                            <th>{{ $attackingAircraft }}</th>
                            <th>{{ $attackingShips }}</th>
                            <th></th>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div align="center">
            <a href="{{ url("/defense/defenders") }}"><button class="btn btn-success"><strong>Back to Defenders</strong></button></a>
            <a href="{{ url("/defense/targets") }}"><button class="btn btn-info"><strong>Home</strong></button></a>
        </div>
        <br>
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">Assigned Attackers</h3>
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
                            <th>Ships</th>
                            <th>Slots</th>
                            <th>Assign</th>
                        </tr>

                        @if($assignments->count() == 0)
                        <tr>
                            <th>There are no attackers assigned to this defender.</th>
                        </tr>
                        @else
                            @foreach($assignments as $assignment)
                                @php(array_push($attackerIDs, $assignment->attacker->id))
                            <tr>
                                <td>{{ $assignment->attacker->nName }}</td>
                                <td>{{ $assignment->attacker->nRuler }}</td>
                                <td>{{ $assignment->attacker->cities }}</td>
                                <td>{{ $assignment->attacker->score }}</td>
                                <td>{{ $assignment->attacker->soldiers }}</td>
                                <td>{{ $assignment->attacker->tanks }}</td>
                                <td>{{ $assignment->attacker->aircraft }}</td>
                                <td>{{ $assignment->attacker->ships }}</td>
                                <td>{{ $assignment->attacker->slots }}</td>
                                <td>
                                    <form method="POST">
                                        <button class="btn btn-danger" value="unassign" name="assign">Unassign</button>
                                        <input type="hidden" value="{{ $assignment->attacker->id }}" name="attacker_id">
                                        <input type="hidden" value="{{ $defender->id }}" name="defender_id">
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
                <h3 class="box-title">Assignable Attackers</h3>
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
                            <th>Ships</th>
                            <th>Slots</th>
                            <th>Assign</th>
                        </tr>

                        @if($inRanges->count() == 0)
                            <tr>
                                <th>There are no attackers in range to assign to this defender.</th>
                            </tr>
                        @else
                            @foreach($inRanges as $inRange)
                                @if(!in_array($inRange->id, $attackerIDs))
                                <tr>
                                    <td>{{ $inRange->nName }}</td>
                                    <td>{{ $inRange->nRuler }}</td>
                                    <td>{{ $inRange->cities }}</td>
                                    <td>{{ $inRange->score }}</td>
                                    <td>{{ $inRange->soldiers }}</td>
                                    <td>{{ $inRange->tanks }}</td>
                                    <td>{{ $inRange->aircraft }}</td>
                                    <td>{{ $inRange->ships }}</td>
                                    <td>{{ $inRange->slots }}</td>
                                    <td>
                                        <form method="POST">
                                            <button class="btn btn-success" value="assign" name="assign">Assign</button>
                                            <input type="hidden" value="{{ $inRange->id }}" name="attacker_id">
                                            <input type="hidden" value="{{ $defender->id }}" name="defender_id">
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