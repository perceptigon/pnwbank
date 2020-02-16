@extends('layouts.admin')

@section('content')

    <section class="content-header">
        <h1>All Results</h1>
    </section>
    <div align="center">
        <a href="{{ url("/defense/spies") }}"><button class="btn btn-info"><strong>Home</strong></button></a>
    </div>
    <section class="content">
        @include("admin.alerts")
        <div class="box box-warning">
            <div class="box-header with-border">
                <h3 class="box-title">Out-of-Range Results</h3>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <tr>
                            <th>Date</th>
                            <th>Round</th>
                            <th>Assigned To</th>
                            <th>Target</th>
                            <th>Type</th>
                            <th>Result (Hover for full text)</th>
                        </tr>

                        @foreach($outOfRanges as $assignment)
                            <tr>
                                <td>{{ date_format($assignment->created_at, 'F d, Y') }}</td>
                                <td>{{ $assignment->round }}</td>
                                <td>{{ $assignment->attacker->nRuler }}</td>
                                <td>{{ $assignment->defender->nRuler }}</td>
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
                                    <td data-toggle="tooltip" title="{{ $assignment->results }}" data-placement="left" style="color: green;">Successful</td>
                                @elseif(is_null($assignment->success))
                                    <td>No results submitted</td>
                                @elseif($assignment->success == false)
                                    <td data-toggle="tooltip" title="{{ $assignment->results }}" style="color: red;">Failure</td>
                                @endif
                            </tr>
                        @endforeach

                    </table>
                </div>
            </div>
        </div>

@endsection