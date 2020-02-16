@extends('layouts.admin')

@php

    use App\Models\Attacker;
    use App\Models\Belligerent;
    $attackers = Attacker::all()->sortByDesc('cities');
    $belligerents = Belligerent::where('is_attacking', 1)->get();

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
        <a href="{{ url("/defense/defenders") }}"><button class="btn btn-success"><strong>Switch to Defenders</strong></button></a>
        <a href="{{ url("/defense/targets") }}"><button class="btn btn-info"><strong>Home</strong></button></a>
    </div>
    <section class="content">
        @include("admin.alerts")
        <div class="box box-info">
            <div class="box-header with-border">
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
                            <th>Soldiers</th>
                            <th>Tanks</th>
                            <th>Aircraft</th>
                            <th>Slots</th>
                            <th>Assign</th>
                        </tr>

                        @foreach($attackers as $attacker)
                            <tr>
                                <td><a href="https://politicsandwar.com/nation/id={{ $attacker->nID }}">{{ $attacker->nName }}</a></td>
                                <td>{{ $attacker->nRuler }}</td>
                                <td>{{ $attacker->cities }}</td>
                                <td>{{ $attacker->soldiers }}</td>
                                <td>{{ $attacker->tanks }}</td>
                                <td>{{ $attacker->aircraft }}</td>
                                <td>{{ $attacker->slots }}</td>
                                <td>
                                    <a href="{{ url("/defense/attackers/$attacker->id") }}"><button class="btn btn-success" value="assign" name="assign">Assign</button></a>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </section>

@endsection