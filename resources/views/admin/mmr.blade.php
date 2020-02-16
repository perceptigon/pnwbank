@extends('layouts.admin')

@section('content')
    <section class="content-header">
        <h1>Minimum Military Requirements</h1>
    </section>
    <section class="content">
        @include("admin.alerts")
        <div class="box box-warning">
            <div class="box-header with-border">
                <h3 class="box-title">MMR Tiers</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                        <i class="fa fa-minus"></i></button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
                        <i class="fa fa-times"></i></button>
                </div>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <form method="post">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>City Number</th>
                                <th>Money</th>
                                <th>Food</th>
                                <th>Uranium</th>
                                <th>Gas</th>
                                <th>Munitions</th>
                                <th>Steel</th>
                                <th>Aluminum</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($requirements as $req)
                                <tr>
                                    <td>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-building" aria-hidden="true"></i></span>
                                            <input type="number" class="form-control" name="cityNum[]" value="{{ $req->cityNum }}" required min="0">
                                            <input type="hidden" name="id[]" value="{{ $req->id }}">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-usd" aria-hidden="true"></i></span>
                                            <input type="number" class="form-control" name="money[]" value="{{ $req->money }}" required min="0">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <span class="input-group-addon"><img src="{{ url("/images/resources/food.png") }}"></span>
                                            <input type="number" class="form-control" name="food[]" value="{{ $req->food }}" required min="0">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <span class="input-group-addon"><img src="{{ url("/images/resources/uranium.png") }}"></span>
                                            <input type="number" class="form-control" name="uranium[]" value="{{ $req->uranium }}" required min="0">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <span class="input-group-addon"><img src="{{ url("/images/resources/gasoline.png") }}"></span>
                                            <input type="number" class="form-control" name="gas[]" value="{{ $req->gas }}" required min="0">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <span class="input-group-addon"><img src="{{ url("/images/resources/munitions.png") }}"></span>
                                            <input type="number" class="form-control" name="munitions[]" value="{{ $req->munitions }}" required min="0">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <span class="input-group-addon"><img src="{{ url("/images/resources/steel.png") }}"></span>
                                            <input type="number" class="form-control" name="steel[]" value="{{ $req->steel }}" required min="0">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <span class="input-group-addon"><img src="{{ url("/images/resources/aluminum.png") }}"></span>
                                            <input type="number" class="form-control" name="aluminum[]" value="{{ $req->aluminum }}" required min="0">
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <input type="submit" class="btn btn-warning" name="editTiers" value="Save">
                        {{ csrf_field() }}
                    </form>
                    <button class="btn btn-warning" data-toggle='modal' data-target='#createTier'>Create New Tier</button>
                </div>
            </div>
        </div>
    </section>

    <div id="createTier" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Create MMR Tier</h4>
                </div>
                <!-- Edit grant dialog -->
                <form method="post">
                    <div class="modal-body">
                        <div class="xsForm">
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-building" aria-hidden="true"></i></span>
                                    <input type="number" name="cityNum" class="form-control" placeholder="City Number" required min="0">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-dollar" aria-hidden="true"></i></span>
                                    <input type="number" name="money" class="form-control" placeholder="Money" required min="0">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon"><img src="{{ url("/images/resources/food.png") }}"></span>
                                    <input type="number" name="food" class="form-control" placeholder="Food" required min="0">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon"><img src="{{ url("/images/resources/uranium.png") }}"></span>
                                    <input type="number" name="uranium" class="form-control" placeholder="Uranium" required min="0">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon"><img src="{{ url("/images/resources/gasoline.png") }}"></span>
                                    <input type="number" name="gas" class="form-control" placeholder="Gasoline" required min="0">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon"><img src="{{ url("/images/resources/munitions.png") }}"></span>
                                    <input type="number" name="munitions" class="form-control" placeholder="Munitions" required min="0">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon"><img src="{{ url("/images/resources/steel.png") }}"></span>
                                    <input type="number" name="steel" class="form-control" placeholder="Steel" required min="0">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon"><img src="{{ url("/images/resources/aluminum.png") }}"></span>
                                    <input type="number" name="aluminum" class="form-control" placeholder="Aluminum" required min="0">
                                </div>
                            </div>
                            <div class="form-group">
                                <input type="submit" value="Add Tier" name="addTier" class="btn btn-warning" required min="0">
                            </div>
                        </div>
                    </div>
                    {{ csrf_field() }}
                </form>
            </div>
        </div>
    </div>

@endsection