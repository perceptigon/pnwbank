@extends('layouts.admin')

@php

    use App\Models\Defense\spyAttacker;
    use App\Models\Defense\spyBelligerent;
    $attackers = spyAttacker::all()->sortByDesc('score');
    $belligerents = spyBelligerent::where('is_attacking', 1)->get();

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
        <h1>ATTACKERS <strong>{{ $names }}</strong></h1>
    </section>
    <div align="center">
        <a href="{{ url("/defense/spydefenders") }}"><button class="btn btn-success"><strong>Switch to Defenders</strong></button></a>
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
                            <th style="text-align: center">Spies</th>
                            <th style="text-align: center">Slots</th>
                            <th>Actions</th>
                        </tr>

                        @foreach($attackers as $attacker)
                            <tr>
                                <td><a href="https://politicsandwar.com/nation/id={{ $attacker->nID }}">{{ $attacker->nName }}</a></td>
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
                                <td>
                                    <a href="{{ url("/defense/spyattackers/$attacker->id") }}"><button class="btn btn-success" value="assign" name="assign">Assign</button></a>
                                    <a href="{{ url("/defense/spyattackers/results/$attacker->id") }}"><button class="btn btn-info">Results</button></a>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </section>

@endsection