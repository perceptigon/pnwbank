@extends('layouts.admin')

@php

    use App\Models\Defense\spyDefender;
    use App\Models\Defense\spyAttacker;
    use App\Models\Defense\spyParameter;

    $round = spyParameter::where('name', 'round')->first();

    $attacker = spyAttacker::where('id', $id)->first();
    $assignments = $attacker->assignments;
    $assignments = $assignments->where('round', $round->value);
    $defenderIDs = [];

    $min = $attacker->score * 0.40;
    $max = $attacker->score * 2.50;
    $inRanges = spyDefender::whereBetween('score', [$min, $max])->get();
    $inRanges = $inRanges->sortByDesc('cities');

@endphp

@section('content')

    <section class="content-header">
        <h1>SPY ATTACKER <strong>{{ $attacker->nRuler }}</strong></h1>
    </section>
    <section class="content">
        @include("admin.alerts")
        <div class="box box-danger">
            <div class="box-header with-border">
                <h3 class="box-title">Attacking Nation</h3>
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
                        </tr>

                        <tr>
                            <td><a href="https://politicsandwar.com/nation/id={{ $attacker->nID }}" target="_blank">{{ $attacker->nName }}</a></td>
                            <td>{{ $attacker->nRuler }}</td>
                            <td>{{ $attacker->alliance }}</td>
                            <td style="text-align: center">{{ round($attacker->lastActive / 120, 0) }}</td>
                            @if($attacker->warPolicy == 'Covert')
                                <td style="background: lightgreen">{{ $attacker->warPolicy }}</td>
                            @else
                                <td>{{ $attacker->warPolicy }}</td>
                            @endif
                            <td style="text-align: center">{{ $attacker->score }}</td>
                            <td style="text-align: center"><strong>{{ $attacker->spies }}</strong></td>
                            <td style="text-align: center">{{ $attacker->slots }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div align="center">
            <a href='{{ url("/defense/spyattackers") }}'><button class="btn btn-success"><strong>Back to Attackers</strong></button></a>
            <a href='{{ url("/defense/spyattackers/results/$attacker->id") }}'><button class="btn btn-success"><strong>Results</strong></button></a>
            <a href='{{ url("/defense/spies") }}'><button class="btn btn-info"><strong>Home</strong></button></a>
        </div>
        <br>
        <div class="box box-danger">
            <div class="box-header with-border">
                <h3 class="box-title">Assigned Targets</h3>
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
                            <th>Type</th>
                            <th>Actions</th>
                        </tr>

                        @if($assignments->count() == 0)
                            <tr>
                                <th>There are no defenders assigned to this attacker.</th>
                            </tr>
                        @else
                            @foreach($assignments as $assignment)
                                @php(array_push($defenderIDs, $assignment->defender->id))
                                <tr>
                                    <td><a href="https://politicsandwar.com/nation/id={{ $assignment->nID }}" target="_blank">{{ $assignment->defender->nName }}</a></td>
                                    <td>{{ $assignment->defender->nRuler }}</td>
                                    <td>{{ $assignment->defender->alliance }}</td>
                                    <td style="text-align: center">{{ round($assignment->defender->lastActive / 120, 0) }}</td>
                                    @if($assignment->defender->warPolicy == 'Tactician')
                                        <td style="background: lightgreen">{{ $assignment->defender->warPolicy }}</td>
                                    @elseif($assignment->defender->warPolicy == 'Arcane')
                                        <td style="background: #cd8a82">{{ $assignment->defender->warPolicy }}</td>
                                    @else
                                        <td>{{ $assignment->defender->warPolicy }}</td>
                                    @endif
                                    <td style="text-align: center">{{ $assignment->defender->score }}</td>
                                    <td style="text-align: center">{{ $assignment->defender->missiles }}</td>
                                    <td style="text-align: center">{{ $assignment->defender->nukes }}</td>
                                    <td style="text-align: center">{{ $assignment->defender->maxSpies }}</td>
                                    <td style="text-align: center">{{ $assignment->defender->slots }}</td>
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
        <div class="box box-danger">
            <div class="box-header with-border">
                <h3 class="box-title">Assignable Targets</h3>
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
                            <th>Type</th>
                            <th>Assign</th>
                        </tr>

                        @if($inRanges->count() == 0)
                            <tr>
                                <th>There are no attackers in range to assign to this defender.</th>
                            </tr>
                        @else
                            @foreach($inRanges as $inRange)
                                @if(!in_array($inRange->id, $defenderIDs))
                                    <form method="POST">
                                        <tr>
                                            <td><a href="https://politicsandwar.com/nation/id={{ $inRange->nID }}" target="_blank">{{ $inRange->nName }}</a></td>
                                            <td>{{ $inRange->nRuler }}</td>
                                            <td>{{ $inRange->alliance }}</td>
                                            <td style="text-align: center">{{ round($inRange->lastActive / 120, 0) }}</td>
                                            @if($inRange->warPolicy == 'Tactician')
                                                <td style="background: lightgreen">{{ $inRange->warPolicy }}</td>
                                            @elseif($inRange->warPolicy == 'Arcane')
                                                <td style="background: #cd8a82">{{ $inRange->warPolicy }}</td>
                                            @else
                                                <td>{{ $inRange->warPolicy }}</td>
                                            @endif
                                            <td style="text-align: center">{{ $inRange->score }}</td>
                                            <td style="text-align: center">{{ $inRange->missiles }}</td>
                                            <td style="text-align: center">{{ $inRange->nukes }}</td>
                                            <td style="text-align: center">{{ $inRange->maxSpies }}</td>
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
                                                <input type="hidden" value="{{ $inRange->id }}" name="defender_id">
                                                <input type="hidden" value="{{ $attacker->id }}" name="attacker_id">
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