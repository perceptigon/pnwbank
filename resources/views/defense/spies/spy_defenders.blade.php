@extends('layouts.admin')

@php

    use App\Models\Defense\spyDefender;
    use App\Models\Defense\spyBelligerent;
    $defenders = spyDefender::all()->sortByDesc('score');
    $belligerents = spyBelligerent::where('is_attacking', 0)->get();


    $first = true;
    $names = '';
    foreach ($belligerents as $belligerent)
    {
        if ($first == true)
        {
            $names .=  $belligerent->aName;
            $first = false;
        }
        else $names .= ', ' . $belligerent->aName;
    }

@endphp

@section('content')

    <section class="content-header">
        <h1>Defenders <strong>{{ $names }}</strong></h1>
    </section>
    <div align="center">
        <a href="{{ url("/defense/spyattackers") }}"><button class="btn btn-success"><strong>Switch to Attackers</strong></button></a>
        <a href="{{ url("/defense/spies") }}"><button class="btn btn-info"><strong>Home</strong></button></a>
    </div>
    <section class="content">
        @include("admin.alerts")
        <div class="box box-danger">
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
                            <th>Assign</th>
                        </tr>

                        @foreach($defenders as $defender)
                            <tr>
                                <td><a href="https://politicsandwar.com/nation/id={{ $defender->nID }}">{{ $defender->nName }}</a></td>
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
                                <td>
                                    <a href="{{ url("/defense/spydefenders/$defender->id") }}"><button class="btn btn-success" value="assign" name="assign">Assign</button></a>
                                    <a href="{{ url("/defense/spydefenders/results/$defender->id") }}"><button class="btn btn-info">Results</button></a>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </section>

@endsection