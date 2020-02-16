@extends('layouts.admin')

@section('content')
    <section class="content-header">
        <h1>Bank Transfers</h1>
    </section>
    <section class="content">
        @include("admin.alerts")
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="box box-warning">
                    <div class="box-header with-border">
                        <h3 class="box-title">Send Money</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <form method="post" onsubmit="confirm('Are you sure you want send this transaction?')">
                            <div class="form-group">
                                <label>$$ Money</label>
                                <input type="number" min="1" name="money" class="form-control" placeholder="Money">
                            </div>
                            <div class="form-group">
                                <label><img src="/images/resources/food.png"> Food</label>
                                <input type="number" min="1" name="food" class="form-control" placeholder="Food">
                            </div>
                            <div class="form-group">
                                <label><img src="/images/resources/coal.png"> Coal</label>
                                <input type="number" min="1" name="coal" class="form-control" placeholder="Coal">
                            </div>
                            <div class="form-group">
                                <label><img src="/images/resources/oil.png"> Oil</label>
                                <input type="number" min="1" name="oil" class="form-control" placeholder="Oil">
                            </div>
                            <div class="form-group">
                                <label><img src="/images/resources/uranium.png"> Uranium</label>
                                <input type="number" min="1" name="uranium" class="form-control" placeholder="Uranium">
                            </div>
                            <div class="form-group">
                                <label><img src="/images/resources/lead.png"> Lead</label>
                                <input type="number" min="1" name="lead" class="form-control" placeholder="Lead">
                            </div>
                            <div class="form-group">
                                <label><img src="/images/resources/iron.png"> Iron</label>
                                <input type="number" min="1" name="iron" class="form-control" placeholder="Iron">
                            </div>
                            <div class="form-group">
                                <label><img src="/images/resources/bauxite.png"> Bauxite</label>
                                <input type="number" min="1" name="bauxite" class="form-control" placeholder="Bauxite">
                            </div>
                            <div class="form-group">
                                <label><img src="/images/resources/gasoline.png"> Gasoline</label>
                                <input type="number" min="1" name="gasoline" class="form-control" placeholder="Gasoline">
                            </div>
                            <div class="form-group">
                                <label><img src="/images/resources/munitions.png"> Munitions</label>
                                <input type="number" min="1" name="munitions" class="form-control" placeholder="Munitions">
                            </div>
                            <div class="form-group">
                                <label><img src="/images/resources/steel.png"> Steel</label>
                                <input type="number" min="1" name="steel" class="form-control" placeholder="Steel">
                            </div>
                            <div class="form-group">
                                <label><img src="/images/resources/aluminum.png"> Aluminum</label>
                                <input type="number" min="1" name="aluminum" class="form-control" placeholder="Aluminum">
                            </div>
                            <div class="form-group">
                                <label>Recipient (Nation Name or Alliance)</label>
                                <input type="text" name="recipient" class="form-control" placeholder="Recipient" required>
                                <p class="help-block">Must be <strong>exact</strong></p>
                            </div>
                            <div class="form-group">
                                <label>Alliance or Nation</label>
                                <select class="form-control" name="type">
                                    <option value="Nation">Nation</option>
                                    <option value="Alliance">Alliance</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Transaction Note</label>
                                <input type="text" name="note" class="form-control" placeholder="Transaction Note" required>
                            </div>
                            <div class="form-group">
                                {{ csrf_field() }}
                                <input type="submit" class="btn btn-warning" value="Send" name="SOSend">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection