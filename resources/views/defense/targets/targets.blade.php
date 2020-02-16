@extends('layouts.admin')

@php

    use App\Models\Belligerent;
    use App\Models\Attacker;
    use App\Models\Defender;

    $belligerents = Belligerent::all();

    $active = false;
    $attackers = Attacker::all();
    $defenders = Defender::all();

    if (count($attackers) != 0 || count($defenders) != 0) $active = true;

@endphp

@section('content')

    <section class="content-header">
        <h1>Target Wizard</h1>
        <br>
        <p>Welcome to the BK Target Wizard. To begin, enter the alliance IDs of both the attacking and defending alliance(s). Once you are done, simply click either "continue to attackers" or "continue to defenders", depending on how you want to assign targets. The first time you click this it will load in all the nations, and depending on the size of the alliance, <strong>this can take up to several minutes</strong>, so don't worry.</p>
        <div>
            @if($active != false)
                <h3><strong>Status: </strong><span style="color:red">Active</span></h3>
            @else
                <h3><strong>Status: </strong><span style="color:green">Inactive</span></h3>
            @endif
            <p><em>If status is active, you need to use the reset button before adding or removing alliances.</em></p>
        </div>
    </section>


    <section class="content">
        @include("admin.alerts")

        <div class="row">
            <div class="col-md-6">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Attackers</h3>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped">
                                <tr>
                                    <th>Alliance</th>
                                    <th></th>
                                </tr>

                                @foreach($belligerents as $belligerent)

                                    @if($belligerent->is_attacking == true)
                                    <tr>
                                        <td>{{$belligerent->aName}}</td>
                                        <form method="post" action="">
                                            <input type="hidden" value="{{$belligerent->id}}" name="status">
                                            <td><button class="btn btn-danger" value="delete" name="submit" style="width:100px;">Delete</button></td>
                                            {{ csrf_field() }}
                                        </form>
                                    </tr>
                                    @endif

                                @endforeach

                                <tr>
                                    <form method="post" action="">
                                        <td><input placeholder="Alliance ID" type="text" id="alliance_id" name="alliance_id" class="form-control" size="10" required></td>
                                        <input type="hidden" value="1" name="status">
                                        <td><button class="btn btn-success" value="add" name="submit" style="width:100px;">Add</button></td>
                                        {{ csrf_field() }}
                                    </form>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div align="center">
                            <a href="{{ url("/defense/attackers") }}"><button class="btn btn-success"><strong>Continue to Attackers</strong></button></a>
                </div>
            </div>
            <div class="col-md-6">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Defenders</h3>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped">
                                <tr>
                                    <th>Alliance</th>
                                    <th></th>
                                </tr>

                                @foreach($belligerents as $belligerent)

                                    @if($belligerent->is_attacking == false)
                                    <tr>
                                        <td>{{$belligerent->aName}}</td>
                                        <form method="post" action="">
                                            <input type="hidden" value="{{$belligerent->id}}" name="status">
                                            <td><button class="btn btn-danger" value="delete" name="submit" style="width:100px;">Delete</button></td>
                                            {{ csrf_field() }}
                                        </form>
                                    </tr>
                                    @endif

                                @endforeach

                                <tr>
                                    <form method="post" action="">
                                        <td><input placeholder="Alliance ID" type="text" id="alliance_id" name="alliance_id" class="form-control" size="10" required></td>
                                        <input type="hidden" value="0" name="status">
                                        <td><button class="btn btn-success" value="add" name="submit" style="width:100px;">Add</button></td>
                                        {{ csrf_field() }}
                                    </form>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div align="center">
                    <a href="{{ url("/defense/defenders") }}"><button class="btn btn-success"><strong>Continue to Defenders</strong></button></a>
                </div>
            </div>
        </div>
    </section>
    <section>

        <div align="center">
            <h3>Other Actions</h3>
            <a href="{{ url("/defense/reset") }}" onClick="return confirm('Are you sure you want to reset the system?')"><button class="btn btn-danger"><strong>Reset</strong></button></a>
            <a href="{{ url("/defense/message") }}" onClick="return confirm('Are you sure you want to send the target messages?')"><button class="btn btn-warning"><strong>Send Target Messages</strong></button></a>
            <a href="{{ url("/defense/spreadsheet") }}"><button class="btn btn-info"><strong>Spreadsheet</strong></button></a>
        </div>

    </section>

@endsection