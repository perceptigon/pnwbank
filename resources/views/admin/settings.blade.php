@extends('layouts.admin')

@section('content')
    <section class="content-header">
        <h1>Settings</h1>
    </section>
    <section class="content">
        @include("admin.alerts")
        <div class="row">
            <form method="post">
                <div class="col-md-3">
                    <div class="box box-warning" style="min-height: 390px;">
                        <div class="box-header with-border">
                            <h3 class="box-title">General</h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                                    <i class="fa fa-minus"></i></button>
                                <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
                                    <i class="fa fa-times"></i></button>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <label>War Mode</label>
                                <div class="radio">
                                    <label><input type="radio" name="warMode" value="1" class="radio" {{ $settings["warMode"] === 1 ? 'checked' : '' }}> On</label>
                                </div>
                                <div class="radio">
                                    <label><input type="radio" name="warMode" value="0" class="radio" {{ $settings["warMode"] === 0 ? 'checked' : '' }}> Off</label>
                                </div>
                                <p class="help-block">If enabled, the system will not check for late loans</p>
                            </div>
                            <div class="form-group">
                                <label>Dev Mode</label>
                                <div class="radio">
                                    <label><input type="radio" name="devMode" value="1" class="radio" {{ $settings["devMode"] === 1 ? 'checked' : '' }} > On</label>
                                </div>
                                <div class="radio">
                                    <label><input type="radio" name="devMode" value="0" class="radio" {{ $settings["devMode"] === 0 ? 'checked' : '' }}> Off</label>
                                </div>
                                <p class="help-block">If enabled, the system will not send money or messages when accepting or denying anything</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="box box-warning" style="min-height: 390px;">
                        <div class="box-header with-border">
                            <h3 class="box-title">Loans</h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                                    <i class="fa fa-minus"></i></button>
                                <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
                                    <i class="fa fa-times"></i></button>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <label>Loan System</label>
                                <div class="radio">
                                    <label><input type="radio" name="loanSystem" value="1" class="radio" {{ $settings["loanSystem"] === 1 ? 'checked' : '' }}>On</label>
                                </div>
                                <div class="radio">
                                    <label><input type="radio" name="loanSystem" value="0" class="radio" {{ $settings["loanSystem"] === 0 ? 'checked' : '' }}>Off</label>
                                </div>
                                <p class="help-block">If disabled, the system will not accept any new loan requests</p>
                            </div>
                            <div class="form-group">
                                <label>Max Loan</label>
                                <input type="number" name="maxLoan" min="1" placeholder="Max Loan" class="form-control" value="{{ $settings["maxLoan"] }}" required>
                            </div>
                            <div class="form-group">
                                <label>Loan Duration (days)</label>
                                <input type="number" name="loanDuration" placeholder="10" min="1" class="form-control" value="{{ $settings["loanDuration"] }}" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="box box-warning" style="min-height: 390px;">
                        <div class="box-header with-border">
                            <h3 class="box-title">City Grants</h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                                    <i class="fa fa-minus"></i></button>
                                <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
                                    <i class="fa fa-times"></i></button>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <label>Grant System</label>
                                <div class="radio">
                                    <label><input type="radio" name="cityGrantSystem" value="1" class="radio" {{ $settings["cityGrantSystem"] === 1 ? 'checked' : '' }}> On</label>
                                </div>
                                <div class="radio">
                                    <label><input type="radio" name="cityGrantSystem" value="0" class="radio" {{ $settings["cityGrantSystem"] === 0 ? 'checked' : '' }}> Off</label>
                                </div>
                                <p class="help-block">If disabled, the system will not accept any new grant requests</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="box box-warning" style="min-height: 390px;">
                        <div class="box-header with-border">
                            <h3 class="box-title">Alliance Market</h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                                    <i class="fa fa-minus"></i></button>
                                <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
                                    <i class="fa fa-times"></i></button>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <label>Market System</label>
                                <div class="radio">
                                    <label><input type="radio" name="allianceMarketSystem" value="1" class="radio" {{ $settings["allianceMarketSystem"] === 1 ? 'checked' : '' }}> On</label>
                                </div>
                                <div class="radio">
                                    <label><input type="radio" name="allianceMarketSystem" value="0" class="radio" {{ $settings["allianceMarketSystem"] === 0 ? 'checked' : '' }}> Off</label>
                                </div>
                                <p class="help-block">If disabled, the system will not sell any resources</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="box box-warning" style="min-height: 390px;">
                        <div class="box-header with-border">
                            <h3 class="box-title">Entrance Aid</h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                                    <i class="fa fa-minus"></i></button>
                                <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
                                    <i class="fa fa-times"></i></button>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <label>Entrance Aid System</label>
                                <div class="radio">
                                    <label><input type="radio" name="entranceAidSystem" value="1" class="radio" {{ $settings["entranceAidSystem"] === 1 ? 'checked' : '' }}> On</label>
                                </div>
                                <div class="radio">
                                    <label><input type="radio" name="entranceAidSystem" value="0" class="radio" {{ $settings["entranceAidSystem"] === 0 ? 'checked' : '' }}> Off</label>
                                </div>
                                <p class="help-block">If disabled, the system will not accept any new entrance aid requests</p>
                            </div>
                            <div class="form-group">
                                <label>Grant Amount</label>
                                <input type="number" name="entranceAidAmount" class="form-control" value="{{ $settings["entranceAidAmount"] }}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="box box-warning" style="min-height: 390px;">
                        <div class="box-header with-border">
                            <h3 class="box-title">Activity Grants</h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                                    <i class="fa fa-minus"></i></button>
                                <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
                                    <i class="fa fa-times"></i></button>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <label>Activity Grants System</label>
                                <div class="radio">
                                    <label><input type="radio" name="activityGrantSystem" value="1" class="radio" {{ $settings["activityGrantSystem"] === 1 ? 'checked' : '' }}> On</label>
                                </div>
                                <div class="radio">
                                    <label><input type="radio" name="activityGrantSystem" value="0" class="radio" {{ $settings["activityGrantSystem"] === 0 ? 'checked' : '' }}> Off</label>
                                </div>
                                <p class="help-block">If disabled, the system will not accept any new entrance aid requests</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="box box-warning" style="min-height: 390px;">
                        <div class="box-header with-border">
                            <h3 class="box-title">Project Grants</h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                                    <i class="fa fa-minus"></i></button>
                                <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
                                    <i class="fa fa-times"></i></button>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <label>ID Grants System</label>
                                <div class="radio">
                                    <label><input type="radio" name="idGrantSystem" value="1" class="radio" {{ $settings["idGrantSystem"] === 1 ? 'checked' : '' }}> On</label>
                                </div>
                                <div class="radio">
                                    <label><input type="radio" name="idGrantSystem" value="0" class="radio" {{ $settings["idGrantSystem"] === 0 ? 'checked' : '' }}> Off</label>
                                </div>
                                <p class="help-block">If disabled, the system will not accept any new entrance aid requests</p>
                            </div>
                            <div class="form-group">
                                <label>Grant Amount</label>
                                <input type="number" class="form-control" name="idGrantAmount" value="{{ $settings["idGrantAmount"] }}">
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-md-3">
                    <div class="box box-warning" style="min-height: 390px;">
                        <div class="box-header with-border">
                            <h3 class="box-title">Defense System</h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                                    <i class="fa fa-minus"></i></button>
                                <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
                                    <i class="fa fa-times"></i></button>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <label>Target Test Mode</label>
                                <div class="radio">
                                    <label><input type="radio" name="targetTestMode" value="1" class="radio" {{ $settings["targetTestMode"] === 1 ? 'checked' : '' }}> On</label>
                                </div>
                                <div class="radio">
                                    <label><input type="radio" name="targetTestMode" value="0" class="radio" {{ $settings["targetTestMode"] === 0 ? 'checked' : '' }}> Off</label>
                                </div>
                                <p class="help-block">If enabled, the system will not send any target messages.</p>
                            </div>
                            <div class="form-group">
                                <label>Spy Test Mode</label>
                                <div class="radio">
                                    <label><input type="radio" name="spyTestMode" value="1" class="radio" {{ $settings["spyTestMode"] === 1 ? 'checked' : '' }}> On</label>
                                </div>
                                <div class="radio">
                                    <label><input type="radio" name="spyTestMode" value="0" class="radio" {{ $settings["spyTestMode"] === 0 ? 'checked' : '' }}> Off</label>
                                </div>
                                <p class="help-block">If enabled, the system will not send any spy target messages.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="box box-warning" style="min-height: 390px;">
                        <div class="box-header with-border">
                            <h3 class="box-title">Oil and EGR Grants</h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                                    <i class="fa fa-minus"></i></button>
                                <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
                                    <i class="fa fa-times"></i></button>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <label>Oil & EGR Grants System</label>
                                <div class="radio">
                                    <label><input type="radio" name="oilSystem" value="1" class="radio" {{ $settings["oilSystem"] === 1 ? 'checked' : '' }}> On</label>
                                </div>
                                <div class="radio">
                                    <label><input type="radio" name="oilSystem" value="0" class="radio" {{ $settings["oilSystem"] === 0 ? 'checked' : '' }}> Off</label>
                                </div>
                                <p class="help-block">If disabled, the system will not accept any new oil or EGR aid requests</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="box box-warning" style="min-height: 390px;">
                        <div class="box-header with-border">
                            <h3 class="box-title">NRF Grants</h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                                    <i class="fa fa-minus"></i></button>
                                <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
                                    <i class="fa fa-times"></i></button>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <label>NRF Grants System</label>
                                <div class="radio">
                                    <label><input type="radio" name="nukeprojectSystem" value="1" class="radio" {{ $settings["nukeprojectSystem"] === 1 ? 'checked' : '' }}> On</label>
                                </div>
                                <div class="radio">
                                    <label><input type="radio" name="nukeprojectSystem" value="0" class="radio" {{ $settings["nukeprojectSystem"] === 0 ? 'checked' : '' }}> Off</label>
                                </div>
                                <p class="help-block">If disabled, the system will not accept any new NRF Project requests</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="box box-warning" style="min-height: 390px;">
                        <div class="box-header with-border">
                            <h3 class="box-title">Nukes Grants</h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                                    <i class="fa fa-minus"></i></button>
                                <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
                                    <i class="fa fa-times"></i></button>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <label>Nukes Grants System</label>
                                <div class="radio">
                                    <label><input type="radio" name="nukesSystem" value="1" class="radio" {{ $settings["nukesSystem"] === 1 ? 'checked' : '' }}> On</label>
                                </div>
                                <div class="radio">
                                    <label><input type="radio" name="nukesSystem" value="0" class="radio" {{ $settings["nukesSystem"] === 0 ? 'checked' : '' }}> Off</label>
                                </div>
                                <p class="help-block">If disabled, the system will not accept any new nukes requests (Nukes themselfs, does not include nrf)</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="box box-warning">
                        <div class="box-header with-border">
                            <h3 class="box-title">Save</h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                                    <i class="fa fa-minus"></i></button>
                                <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
                                    <i class="fa fa-times"></i></button>
                            </div>
                        </div>
                        <div class="box-body">
                            {{ csrf_field() }}
                            <div class="text-center">
                                <input type="submit" class="btn btn-warning btn-lg" name="editConf" value="Edit Settings">
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
@endsection