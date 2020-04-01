@extends('layouts.admin')

@php

    use App\Models\Defense\spyDefender;

    $defender = spyDefender::where('id', $id)->first();

    $assignments = $defender->assignments;
    $assignments = $assignments->sortBy('round');

@endphp

@section('content')

    <section class="content-header">
        <h1>RESULTS: <strong>{{ $defender->nName }}</strong> (Defender)</h1>
    </section>

    <section class="content">
        @include("admin.alerts")
        <div class="box box-danger">
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <tr>
                            <th>Date</th>
                            <th>Round</th>
                            <th>Rothschilds & Co. Nation</th>
                            <th>Type</th>
                            <th>Result (Hover for full text)</th>
                        </tr>

                        @foreach($assignments as $assignment)
                            <tr>
                                <td>{{ date_format($assignment->created_at, 'F d, Y') }}</td>
                                <td>{{ $assignment->round }}</td>
                                <td>{{ $assignment->attacker->nName }}</td>
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
                                @if($assignment->success == true)
                                    <td data-toggle="tooltip" title="{{ $assignment->results }}" style="color: green;">Successful</td>
                                @elseif($assignment->success == false)
                                    <td data-toggle="tooltip" title="{{ $assignment->results }}" style="color: red;">Failure</td>
                                @elseif($assignment->success == 2)
                                    <td data-toggle="tooltip" title="{{ $assignment->results }}" style="color: darkgoldenrod;">Successful, but identified</td>
                                @else
                                    <td>No results submitted</td>
                                @endif
                            </tr>
                        @endforeach

                    </table>
                </div>
            </div>
        </div>

@endsection