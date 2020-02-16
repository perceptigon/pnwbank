@extends('layouts.admin')

@php

    use App\Models\Defense\spyDefender;
    use App\Models\Defense\spyAttacker;
    use App\Models\Defense\spyParameter;

    $round = spyParameter::where('name', 'round')->first();

    $defender = spyDefender::where('id', $id)->first();
    $assignments = $defender->assignments;
    $assignments = $assignments->where('round', $round->value);
    $attackerIDs = [];

    $attackingScore = 0;
    $attackingSoldiers = 0;
    $attackingTanks = 0;
    $attackingAircraft = 0;
    $attackingShips = 0;

    //calculate totals
    foreach ($assignments as $assignment)
    {
        $attackingScore += $assignment->attacker->score;
        $attackingSoldiers += $assignment->attacker->soldiers;
        $attackingTanks += $assignment->attacker->tanks;
        $attackingAircraft += $assignment->attacker->aircraft;
        $attackingShips += $assignment->attacker->ships;
    }

    $min = $defender->score * 0.33;
    $max = $defender->score * 1.66;
    $inRanges = spyAttacker::whereBetween('score', [$min, $max])->get();
    $inRanges = $inRanges->sortByDesc('cities');

@endphp

@section('content')

    <section class="content-header">
        <h1>DEFENDER <strong>{{ $defender->nRuler }}</strong></h1>
    </section>
    <section class="content">
        @include("admin.alerts")
        <div class="box box-danger">
            <div class="box-header with-border">
                <h3 class="box-title">Defending Nation</h3>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <tr>
                            <th>Nation</th>
                            <th>Ruler</th>
                            <th>Alliance</th>
                            <th style="text-align: center">Turns Inactive</th>
                            <th>War Policy</th>
                            <th style="text-align: center">Score</th>
                            <th style="text-align: center">Missiles</th>
                            <th style="text-align: center">Nukes</th>
                            <th style="text-align: center">Spies</th>
                            <th style="text-align: center">Slots</th>
                        </tr>

                        <tr>
                            <td><a href="https://politicsandwar.com/nation/id={{ $defender->nID }}" target="_blank">{{ $defender->nName }}</a></td>
                            <td>{{ $defender->nRuler }}</td>
                            <td>{{ $defender->alliance }}</td>
                            <td style="text-align: center">{{ round($defender->lastActive / 120, 0) }}</td>
                            @if($defender->warPolicy == 'Tactician')
                                <td style="background: lightgreen">{{ $defender->warPolicy }}</td>
                            @elseif($defender->warPolicy == 'Arcane')
                                <td style="background: #cd8a82">{{ $defender->warPolicy }}</td>
                            @else
                                <td>{{ $defender->warPolicy }}</td>
                            @endif
                            <td style="text-align: center">{{ $defender->score }}</td>
                            <td style="text-align: center">{{ $defender->missiles }}</td>
                            <td style="text-align: center">{{ $defender->nukes }}</td>
                            <td style="text-align: center">{{ $defender->maxSpies }}</td>
                            <td style="text-align: center">{{ $defender->slots }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div align="center">
            <a href="{{ url("/defense/spydefenders") }}"><button class="btn btn-success"><strong>Back to Defenders</strong></button></a>
            <a href="{{ url("/defense/spies") }}"><button class="btn btn-info"><strong>Home</strong></button></a>
        </div>
        <br>
        <div class="box box-danger">
            <div class="box-header with-border">
                <h3 class="box-title">Assigned Attackers</h3>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <tr>
                            <th>Nation</th>
                            <th>Ruler</th>
                            <th>Alliance</th>
                            <th style="text-align: center">Turns Inactive</th>
                            <th>War Policy</th>
                            <th style="text-align: center">Score</th>
                            <th style="text-align: center">Spies</th>
                            <th style="text-align: center">Slots</th>
                            <th>Type</th>
                            <th>Assign</th>
                        </tr>

                        @if($assignments->count() == 0)
                            <tr>
                                <th>There are no attackers assigned to this defender.</th>
                            </tr>
                        @else
                            @foreach($assignments as $assignment)
                                @php
                                    array_push($attackerIDs, $assignment->attacker->id))

                                <tr>
                                    <td><a href="https://politicsandwar.com/nation/id={{ $assignment->attacker->nID }}" target="_blank">{{ $assignment->attacker->nName }}</a></td>
                                    <td>{{ $assignment->attacker->nRuler }}</td>
                                    <td>{{ $assignment->attacker->alliance }}</td>
                                    <td style="text-align: center">{{ round($assignment->attacker->lastActive / 120, 0) }}</td>
                                    @if($assignment->attacker->warPolicy == 'Covert')
                                        <td style="background: lightgreen">{{ $assignment->attacker->warPolicy }}</td>
                                    @else
                                        <td>{{ $assignment->attacker->warPolicy }}</td>
                                    @endif
                                    <td style="text-align: center">{{ $assignment->attacker->score }}</td>
                                    <td style="text-align: center"><strong>{{ $assignment->attacker->spies }}</strong></td>
                                    <td style="text-align: center">{{ $assignment->attacker->slots }}</td>
                                    @php
                                        switch ($assignment->type)
                                        {
                                            case 1:
                                                echo "<td>Gather Intelligence</td>";
                                                break;
                                            case 2:
                                                echo "<td>Assassinate Spies</td>";
                                                break;
                                            case 3:
                                                echo "<td>Terrorize Civilians</td>";
                                                break;
                                            case 4:
                                                echo "<td>Sabotage Soldiers</td>";
                                                break;
                                            case 5:
                                                echo "<td>Sabotage Tanks</td>";
                                                break;
                                            case 6:
                                                echo "<td>Sabotage Aircraft</td>";
                                                break;
                                            case 7:
                                                echo "<td>Sabotage Ships</td>";
                                                break;
                                            case 8:
                                                echo "<td>Sabotage Missiles</td>";
                                                break;
                                            case 9:
                                                echo "<td>Sabotage Nukes</td>";
                                                break;
                                            default:
                                                echo "<td></td>";
                                        }
                                    @endphp
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
        <div class="box box-danger">
            <div class="box-header with-border">
                <h3 class="box-title">Assignable Attackers</h3>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <tr>
                            <th>Nation</th>
                            <th>Ruler</th>
                            <th>Alliance</th>
                            <th style="text-align: center">Turns Inactive</th>
                            <th>War Policy</th>
                            <th style="text-align: center">Score</th>
                            <th style="text-align: center">Spies</th>
                            <th style="text-align: center">Slots</th>
                            <th>Type</th>
                            <th>Assign</th>
                        </tr>

                        @if($inRanges->count() == 0)
                            <tr>
                                <th>There are no attackers in range to assign to this defender.</th>
                            </tr>
                        @else
                            @foreach($inRanges as $inRange)
                                @if(!in_array($inRange->id, $attackerIDs))
                                    <form method="POST">
                                        <tr>
                                            <td><a href="https://politicsandwar.com/nation/id={{ $inRange->nID }}" target="_blank">{{ $inRange->nName }}</a></td>
                                            <td>{{ $inRange->nRuler }}</td>
                                            <td>{{ $inRange->alliance }}</td>
                                            <td style="text-align: center">{{ round($inRange->lastActive / 120, 0) }}</td>
                                            @if($inRange->warPolicy == 'Covert')
                                                <td style="background: lightgreen">{{ $inRange->warPolicy }}</td>
                                            @else
                                                <td>{{ $inRange->warPolicy }}</td>
                                            @endif
                                            <td style="text-align: center">{{ $inRange->score }}</td>
                                            <td style="text-align: center"><strong>{{ $inRange->spies }}</strong></td>
                                            <td style="text-align: center">{{ $inRange->slots }}</td>
                                            <td>
                                                <div class="form-group">
                                                    <select class="form-control" name="type">
                                                        <option value="1">Gather Intelligence</option>
                                                        <option value="2">Assassinate Spies</option>
                                                        <option value="3">Terrorize Civilians</option>
                                                        <option value="4">Sabotage Soldiers</option>
                                                        <option value="5">Sabotage Tanks</option>
                                                        <option value="6">Sabotage Aircraft</option>
                                                        <option value="7">Sabotage Ships</option>
                                                        <option value="8">Sabotage Missiles</option>
                                                        <option value="9">Sabotage Nukes</option>
                                                    </select>
                                                </div>
                                            </td>
                                            <td>
                                                <button class="btn btn-success" value="assign" name="assign">Assign</button>
                                                <input type="hidden" value="{{ $inRange->id }}" name="attacker_id">
                                                <input type="hidden" value="{{ $defender->id }}" name="defender_id">
                                                {{ csrf_field() }}
                                            </td>
                                        </tr>
                                    </form>
                                @endif
                            @endforeach
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </section>

@endsection