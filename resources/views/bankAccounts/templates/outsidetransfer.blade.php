@extends('layouts.app')

@section('content')
<style>
h7 {
  background-color: red;
  color: White;
  font-size: large;
}
</style>
<body>
<h7>These Options have almost no form validation, be sure you fill this out correctly. Its not reversible and we will not refund you for mistakes</h1>
</body>
<div class="panel panel-default">
    <div class="panel-heading">Transfer to a Nation</div>
    <div class="panel-body">
        <form method="post">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="tran_from">From</label>
                        <select class="form-control" name="from" id="tran_from">
                            <optgroup label="Accounts">
                                @foreach ($accounts as $account)
                                    <option value="{{ $account->id }}">{{ $account->name }} - ${{ number_format($account->money, 2) }}</option>
                                @endforeach
                            </optgroup>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="tran_to">To</label>
                        <select class="form-control" name="to" id="tran_to">
                               <optgroup label="nation">
                               <option value="nationOther">Nation - Type the Nation Name Below</option>

                                <input type="text" class="form-control" name="nationname" value="Replace me">
                            </optgroup>
                        </select>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>Money</th>
                                <th>Coal</th>
                                <th>Oil</th>
                                <th>Uranium</th>
                                <th>Lead</th>
                                <th>Iron</th>
                                <th>Bauxite</th>
                                <th>Gas</th>
                                <th>Munitions</th>
                                <th>Steel</th>
                                <th>Aluminum</th>
                                <th>Food</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>
                                    <input type="number" class="form-control" name="money" value="0" step="any" min="0">
                                </td>
                                <td>
                                    <input type="number" class="form-control" name="coal" value="0" step="any" min="0">
                                </td>
                                <td>
                                    <input type="number" class="form-control" name="oil" value="0" step="any" min="0">
                                </td>
                                <td>
                                    <input type="number" class="form-control" name="uranium" value="0" step="any" min="0">
                                </td>
                                <td>
                                    <input type="number" class="form-control" name="lead" value="0" step="any" min="0">
                                </td>
                                <td>
                                    <input type="number" class="form-control" name="iron" value="0" step="any" min="0">
                                </td>
                                <td>
                                    <input type="number" class="form-control" name="bauxite" value="0" step="any" min="0">
                                </td>
                                <td>
                                    <input type="number" class="form-control" name="gas" value="0" step="any" min="0">
                                </td>
                                <td>
                                    <input type="number" class="form-control" name="munitions" value="0" step="any" min="0">
                                </td>
                                <td>
                                    <input type="number" class="form-control" name="steel" value="0" step="any" min="0">
                                </td>
                                <td>
                                    <input type="number" class="form-control" name="aluminum" value="0" step="any" min="0">
                                </td>
                                <td>
                                    <input type="number" class="form-control" name="food" value="0" step="any" min="0">
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    {{ csrf_field() }}
                    <input type="submit" name="transfer" value="Transfer" class="btn btn-block btn-primary">
    </div>
    </div>
</div>
</div>
<br>
<br>
<div class="panel panel-default">
    <div class="panel-heading">Transfer to a alliance</div>
    <div class="panel-body">
        <form method="post">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="tran_from">From</label>
                        <select class="form-control" name="from" id="tran_from">
                            <optgroup label="Accounts">
                                @foreach ($accounts as $account)
                                    <option value="{{ $account->id }}">{{ $account->name }} - ${{ number_format($account->money, 2) }}</option>
                                @endforeach
                            </optgroup>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="tran_to">To</label>
                        <select class="form-control" name="to" id="tran_to">
                               <optgroup label="nation">
                               <option value="allianceOther">Alliance - Type the Alliance Name Below</option>

                                <input type="text" class="form-control" name="alliancename" value="Replace me">
                            </optgroup>
                        </select>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>Money</th>
                                <th>Coal</th>
                                <th>Oil</th>
                                <th>Uranium</th>
                                <th>Lead</th>
                                <th>Iron</th>
                                <th>Bauxite</th>
                                <th>Gas</th>
                                <th>Munitions</th>
                                <th>Steel</th>
                                <th>Aluminum</th>
                                <th>Food</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>
                                    <input type="number" class="form-control" name="money" value="0" step="any" min="0">
                                </td>
                                <td>
                                    <input type="number" class="form-control" name="coal" value="0" step="any" min="0">
                                </td>
                                <td>
                                    <input type="number" class="form-control" name="oil" value="0" step="any" min="0">
                                </td>
                                <td>
                                    <input type="number" class="form-control" name="uranium" value="0" step="any" min="0">
                                </td>
                                <td>
                                    <input type="number" class="form-control" name="lead" value="0" step="any" min="0">
                                </td>
                                <td>
                                    <input type="number" class="form-control" name="iron" value="0" step="any" min="0">
                                </td>
                                <td>
                                    <input type="number" class="form-control" name="bauxite" value="0" step="any" min="0">
                                </td>
                                <td>
                                    <input type="number" class="form-control" name="gas" value="0" step="any" min="0">
                                </td>
                                <td>
                                    <input type="number" class="form-control" name="munitions" value="0" step="any" min="0">
                                </td>
                                <td>
                                    <input type="number" class="form-control" name="steel" value="0" step="any" min="0">
                                </td>
                                <td>
                                    <input type="number" class="form-control" name="aluminum" value="0" step="any" min="0">
                                </td>
                                <td>
                                    <input type="number" class="form-control" name="food" value="0" step="any" min="0">
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    {{ csrf_field() }}
                    <input type="submit" name="transfer" value="Transfer" class="btn btn-block btn-primary">
    </div>
</div>
@endsection