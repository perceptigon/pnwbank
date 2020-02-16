@extends('layouts.admin')

@section('content')
    <section class="content-header">
        <h1>Taxes (30 Days)</h1>
    </section>
    <section class="content">
        @include("admin.alerts")
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Money</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                        <i class="fa fa-minus"></i></button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
                        <i class="fa fa-times"></i></button>
                </div>
            </div>
            <div class="box-body">
                <canvas id="money" height="200" width="600"></canvas>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Steel</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                                <i class="fa fa-minus"></i></button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
                                <i class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <canvas id="steel" height="200" width="600"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="box box-warning">
                    <div class="box-header with-border">
                        <h3 class="box-title">Gas</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                                <i class="fa fa-minus"></i></button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
                                <i class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <canvas id="gas" height="200" width="600"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title">Munitions</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                                <i class="fa fa-minus"></i></button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
                                <i class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <canvas id="munitions" height="200" width="600"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="box box-danger">
                    <div class="box-header with-border">
                        <h3 class="box-title">Aluminum</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                                <i class="fa fa-minus"></i></button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
                                <i class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <canvas id="aluminum" height="200" width="600"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Coal</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                                <i class="fa fa-minus"></i></button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
                                <i class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <canvas id="coal" height="200" width="600"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Oil</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                                <i class="fa fa-minus"></i></button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
                                <i class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <canvas id="oil" height="200" width="600"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Uranium</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                                <i class="fa fa-minus"></i></button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
                                <i class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <canvas id="uranium" height="200" width="600"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Lead</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                                <i class="fa fa-minus"></i></button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
                                <i class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <canvas id="lead" height="200" width="600"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Iron</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                                <i class="fa fa-minus"></i></button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
                                <i class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <canvas id="iron" height="200" width="600"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Bauxite</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                                <i class="fa fa-minus"></i></button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
                                <i class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <canvas id="bauxite" height="200" width="600"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="box box-warning">
                    <div class="box-header with-border">
                        <h3 class="box-title">Resource Breakdown</h3>

                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                                <i class="fa fa-minus"></i></button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
                                <i class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <canvas id="pieChart"/>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="info-box bg-blue">
                    <span class="info-box-icon"><i class="fa fa-usd"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Money 30 Days</span>
                        <span class="info-box-number">${{ number_format($taxes->taxMoneyTotal) }}</span>

                        <div class="progress">
                            <div class="progress-bar" style="width: 100%"></div>
                        </div>
                  <span class="progress-description">
                  </span>
                    </div>
                </div>
                <div class="info-box bg-aqua">
                    <span class="info-box-icon"><i class="fa fa-star"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Steel 30 Days</span>
                        <span class="info-box-number">{{ number_format($taxes->taxSteelTotal) }}</span>

                        <div class="progress">
                            <div class="progress-bar" style="width: 100%"></div>
                        </div>
                  <span class="progress-description">
                  </span>
                    </div>
                </div>
                <div class="info-box bg-yellow">
                    <span class="info-box-icon"><i class="fa fa-battery-full"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Gas 30 Days</span>
                        <span class="info-box-number">{{ number_format($taxes->taxGasTotal) }}</span>

                        <div class="progress">
                            <div class="progress-bar" style="width: 100%"></div>
                        </div>
                  <span class="progress-description">
                  </span>
                    </div>
                </div>
                <div class="info-box bg-green">
                    <span class="info-box-icon"><i class="fa fa-bullseye"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Munitions 30 Days</span>
                        <span class="info-box-number">{{ number_format($taxes->taxMunitionsTotal) }}</span>

                        <div class="progress">
                            <div class="progress-bar" style="width: 100%"></div>
                        </div>
                  <span class="progress-description">
                  </span>
                    </div>
                </div>
                <div class="info-box bg-red">
                    <span class="info-box-icon"><i class="fa fa-paper-plane"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Aluminum 30 Days</span>
                        <span class="info-box-number">{{ number_format($taxes->taxAluminumTotal) }}</span>

                        <div class="progress">
                            <div class="progress-bar" style="width: 100%"></div>
                        </div>
                  <span class="progress-description">
                  </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Money</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                                <i class="fa fa-minus"></i></button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
                                <i class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <table class="sortable table table-hover table-bordered">
                            <tr class="topRow">
                                <th>Date</th>
                                <th>Value</th>
                            </tr>
                            @foreach ($taxes->taxMoney as $x)
                                <tr>
                                    <td>{{ $x->date }} </td>
                                    <td>${{ number_format($x->value) }}</td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Steel</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                                <i class="fa fa-minus"></i></button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
                                <i class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <table class="sortable table table-hover table-bordered">
                            <tr class="topRow">
                                <th>Date</th>
                                <th>Value</th>
                            </tr>
                            @foreach ($taxes->taxSteel as $x)
                                <tr>
                                    <td>{{ $x->date }} </td>
                                    <td>{{ number_format($x->value) }}</td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="box box-warning">
                    <div class="box-header with-border">
                        <h3 class="box-title">Gas</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                                <i class="fa fa-minus"></i></button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
                                <i class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <table class="sortable table table-hover table-bordered">
                            <tr class="topRow">
                                <th>Date</th>
                                <th>Value</th>
                            </tr>
                            @foreach ($taxes->taxGas as $x)
                                <tr>
                                    <td>{{ $x->date }} </td>
                                    <td>{{ number_format($x->value) }}</td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title">Munitions</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                                <i class="fa fa-minus"></i></button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
                                <i class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <table class="sortable table table-hover table-bordered">
                            <tr class="topRow">
                                <th>Date</th>
                                <th>Value</th>
                            </tr>
                            @foreach ($taxes->taxMunitions as $x)
                                <tr>
                                    <td>{{ $x->date }} </td>
                                    <td>{{ number_format($x->value) }}</td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="box box-danger">
                    <div class="box-header with-border">
                        <h3 class="box-title">Aluminum</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                                <i class="fa fa-minus"></i></button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
                                <i class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <table class="sortable table table-hover table-bordered">
                            <tr class="topRow">
                                <th>Date</th>
                                <th>Value</th>
                            </tr>
                            @foreach ($taxes->taxAluminum as $x)
                                <tr>
                                    <td>{{ $x->date }} </td>
                                    <td>{{ number_format($x->value) }}</td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Coal</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                                <i class="fa fa-minus"></i></button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
                                <i class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <table class="sortable table table-hover table-bordered">
                            <tr class="topRow">
                                <th>Date</th>
                                <th>Value</th>
                            </tr>
                            @foreach ($taxes->taxCoal as $x)
                                <tr>
                                    <td>{{ $x->date }} </td>
                                    <td>{{ number_format($x->value) }}</td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="box box-danger">
                    <div class="box-header with-border">
                        <h3 class="box-title">Oil</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                                <i class="fa fa-minus"></i></button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
                                <i class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <table class="sortable table table-hover table-bordered">
                            <tr class="topRow">
                                <th>Date</th>
                                <th>Value</th>
                            </tr>
                            @foreach ($taxes->taxOil as $x)
                                <tr>
                                    <td>{{ $x->date }} </td>
                                    <td>{{ number_format($x->value) }}</td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="box box-danger">
                    <div class="box-header with-border">
                        <h3 class="box-title">Uranium</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                                <i class="fa fa-minus"></i></button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
                                <i class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <table class="sortable table table-hover table-bordered">
                            <tr class="topRow">
                                <th>Date</th>
                                <th>Value</th>
                            </tr>
                            @foreach ($taxes->taxUranium as $x)
                                <tr>
                                    <td>{{ $x->date }} </td>
                                    <td>{{ number_format($x->value) }}</td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="box box-danger">
                    <div class="box-header with-border">
                        <h3 class="box-title">Lead</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                                <i class="fa fa-minus"></i></button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
                                <i class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <table class="sortable table table-hover table-bordered">
                            <tr class="topRow">
                                <th>Date</th>
                                <th>Value</th>
                            </tr>
                            @foreach ($taxes->taxLead as $x)
                                <tr>
                                    <td>{{ $x->date }} </td>
                                    <td>{{ number_format($x->value) }}</td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="box box-danger">
                    <div class="box-header with-border">
                        <h3 class="box-title">Iron</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                                <i class="fa fa-minus"></i></button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
                                <i class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <table class="sortable table table-hover table-bordered">
                            <tr class="topRow">
                                <th>Date</th>
                                <th>Value</th>
                            </tr>
                            @foreach ($taxes->taxIron as $x)
                                <tr>
                                    <td>{{ $x->date }} </td>
                                    <td>{{ number_format($x->value) }}</td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="box box-danger">
                    <div class="box-header with-border">
                        <h3 class="box-title">Bauxite</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                                <i class="fa fa-minus"></i></button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
                                <i class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <table class="sortable table table-hover table-bordered">
                            <tr class="topRow">
                                <th>Date</th>
                                <th>Value</th>
                            </tr>
                            @foreach ($taxes->taxBauxite as $x)
                                <tr>
                                    <td>{{ $x->date }} </td>
                                    <td>{{ number_format($x->value) }}</td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
<script>
    var money = {
        labels : [
            @foreach ($taxes->taxMoney as $d)
                "{{ $d->date }}",
            @endforeach
            ],
        datasets : [
            {
                label: "Money",
                backgroundColor : "rgba(60, 141, 188, 0.7)",
                data : [
                    @foreach ($taxes->taxMoney as $r)
                        {{ round($r->value) }},
                    @endforeach
                ]
            }
        ]

    };
    var steel = {
        labels : [
            @foreach ($taxes->taxSteel as $d)
                    "{{ $d->date }}",
            @endforeach
            ],
        datasets : [
            {
                label: "Steel",
                backgroundColor : "rgba(0, 192, 239, 0.7)",
                data : [
                    @foreach ($taxes->taxSteel as $r)
                        {{ round($r->value) }},
                    @endforeach
                ]
        }
        ]
    };
    var gas = {
        labels : [
            @foreach ($taxes->taxGas as $d)
                    "{{ $d->date }}",
            @endforeach
            ],
        datasets : [
            {
                label: "Gas",
                backgroundColor : "rgba(243, 156, 18, 0.7)",
                data : [
                    @foreach ($taxes->taxGas as $r)
                        {{ round($r->value) }},
                    @endforeach
                ]
            },
        ]
    };
    var munitions = {
        labels : [
            @foreach ($taxes->taxMunitions as $d)
                    "{{ $d->date }}",
            @endforeach
            ],
        datasets : [
            {
                label: "Munitions",
                backgroundColor : "rgba(0, 166, 90, 0.7)",
                data : [
                    @foreach ($taxes->taxMunitions as $r)
                        {{ round($r->value) }},
                    @endforeach
                ]
            }
        ]
    };
    var aluminum = {
        labels : [
            @foreach ($taxes->taxAluminum as $d)
                    "{{ $d->date }}",
            @endforeach
            ],
        datasets : [
            {
                label: "Aluminum",
                backgroundColor : "rgba(221, 75, 57, 0.7)",
                data : [
                    @foreach ($taxes->taxAluminum as $r)
                        {{ round($r->value) }},
                    @endforeach
                ]
            }
        ]
    };
    var coal = {
        labels : [
            @foreach ($taxes->taxCoal as $d)
                "{{ $d->date }}",
            @endforeach
        ],
        datasets : [
            {
                label: "Coal",
                backgroundColor : "rgba(156, 39, 176, 0.7)",
                data : [
                    @foreach ($taxes->taxCoal as $r)
                    {{ round($r->value) }},
                    @endforeach
                ]
            }
        ]
    };
    var oil = {
        labels : [
            @foreach ($taxes->taxOil as $d)
                "{{ $d->date }}",
            @endforeach
        ],
        datasets : [
            {
                label: "Oil",
                backgroundColor : "rgba(233, 30, 99, 0.7)",
                data : [
                    @foreach ($taxes->taxOil as $r)
                    {{ round($r->value) }},
                    @endforeach
                ]
            }
        ]
    };
    var uranium = {
        labels : [
            @foreach ($taxes->taxUranium as $d)
                "{{ $d->date }}",
            @endforeach
        ],
        datasets : [
            {
                label: "Uranium",
                backgroundColor : "rgba(96, 125, 139, 0.7)",
                data : [
                    @foreach ($taxes->taxUranium as $r)
                    {{ round($r->value) }},
                    @endforeach
                ]
            }
        ]
    };
    var lead = {
        labels : [
            @foreach ($taxes->taxLead as $d)
                "{{ $d->date }}",
            @endforeach
        ],
        datasets : [
            {
                label: "Lead",
                backgroundColor : "rgba(205, 220, 57, 0.7)",
                data : [
                    @foreach ($taxes->taxLead as $r)
                    {{ round($r->value) }},
                    @endforeach
                ]
            }
        ]
    };
    var iron = {
        labels : [
            @foreach ($taxes->taxIron as $d)
                "{{ $d->date }}",
            @endforeach
        ],
        datasets : [
            {
                label: "Iron",
                backgroundColor : "rgba(121, 85, 72, 0.7)",
                data : [
                    @foreach ($taxes->taxIron as $r)
                    {{ round($r->value) }},
                    @endforeach
                ]
            }
        ]
    };
    var bauxite = {
        labels : [
            @foreach ($taxes->taxBauxite as $d)
                "{{ $d->date }}",
            @endforeach
        ],
        datasets : [
            {
                label: "Bauxite",
                backgroundColor : "rgba(158, 158, 158, 0.7)",
                data : [
                    @foreach ($taxes->taxBauxite as $r)
                    {{ round($r->value) }},
                    @endforeach
                ]
            }
        ]
    };

    var doughnutData = {
        type: 'pie',
        data: {
            datasets: [{
                data: [
                    {{ round($taxes->taxSteelTotal, 2) }},
                    {{ round($taxes->taxGasTotal, 2) }},
                    {{ round($taxes->taxMunitionsTotal, 2) }},
                    {{ round($taxes->taxAluminumTotal, 2) }},
                    {{ round($taxes->taxCoalTotal, 2) }},
                    {{ round($taxes->taxOilTotal, 2) }},
                    {{ round($taxes->taxUraniumTotal, 2) }},
                    {{ round($taxes->taxLeadTotal, 2) }},
                    {{ round($taxes->taxIronTotal, 2) }},
                    {{ round($taxes->taxBauxiteTotal, 2) }}
                ],
                backgroundColor: [
                    "rgba(0, 192, 239, 1)",
                    "rgba(243, 156, 18, 1)",
                    "rgba(0, 166, 90, 1)",
                    "rgba(221, 75, 57, 1)",
                    "rgba(156, 39, 176, 1)",
                    "rgba(233, 30, 99, 1)",
                    "rgba(96, 125, 139, 1)",
                    "rgba(205, 220, 57, 1)",
                    "rgba(121, 85, 72, 1)",
                    "rgba(158, 158, 158, 1)"
                ]
            }],
            labels: [
                "Steel",
                "Gas",
                "Munitions",
                "Aluminum",
                "Coal",
                "Oil",
                "Uranium",
                "Lead",
                "Iron",
                "Bauxite"
            ]
        }
    };

    window.onload = function(){
        var graph1 = document.getElementById("money").getContext("2d");
        window.myLine = new Chart(graph1, {
            type: 'line',
            data: money
        });
        var graph2 = document.getElementById("steel").getContext("2d");
        window.myLine = new Chart(graph2, {
            type: 'line',
            data: steel
        });
        var graph3 = document.getElementById("gas").getContext("2d");
        window.myLine = new Chart(graph3, {
            type: 'line',
            data: gas
        });
        var graph4 = document.getElementById("munitions").getContext("2d");
        window.myLine = new Chart(graph4, {
            type: 'line',
            data: munitions
        });
        var graph5 = document.getElementById("aluminum").getContext("2d");
        window.myLine = new Chart(graph5, {
            type: 'line',
            data: aluminum
        });
        var graph6 = document.getElementById("coal").getContext("2d");
        window.myLine = new Chart(graph6, {
            type: 'line',
            data: coal
        });
        var graph7 = document.getElementById("oil").getContext("2d");
        window.myLine = new Chart(graph7, {
            type: 'line',
            data: oil
        });
        var graph8 = document.getElementById("uranium").getContext("2d");
        window.myLine = new Chart(graph8, {
            type: 'line',
            data: uranium
        });
        var graph9 = document.getElementById("lead").getContext("2d");
        window.myLine = new Chart(graph9, {
            type: 'line',
            data: lead
        });
        var graph10 = document.getElementById("iron").getContext("2d");
        window.myLine = new Chart(graph10, {
            type: 'line',
            data: iron
        });
        var graph11 = document.getElementById("bauxite").getContext("2d");
        window.myLine = new Chart(graph11, {
            type: 'line',
            data: bauxite
        });
        var pie = document.getElementById("pieChart").getContext("2d");
        window.myPie = new Chart(pie, doughnutData);
    };
</script>
@endsection