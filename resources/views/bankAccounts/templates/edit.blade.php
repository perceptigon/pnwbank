<div class="panel panel-default">
    <div class="panel-heading">Edit Account</div>
    <div class="panel-body">
        <form method="post">
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
                            <input type="number" class="form-control" name="money" value="0" step="any">
                        </td>
                        <td>
                            <input type="number" class="form-control" name="coal" value="0" step="any">
                        </td>
                        <td>
                            <input type="number" class="form-control" name="oil" value="0" step="any">
                        </td>
                        <td>
                            <input type="number" class="form-control" name="uranium" value="0" step="any">
                        </td>
                        <td>
                            <input type="number" class="form-control" name="lead" value="0" step="any">
                        </td>
                        <td>
                            <input type="number" class="form-control" name="iron" value="0" step="any">
                        </td>
                        <td>
                            <input type="number" class="form-control" name="bauxite" value="0" step="any">
                        </td>
                        <td>
                            <input type="number" class="form-control" name="gas" value="0" step="any">
                        </td>
                        <td>
                            <input type="number" class="form-control" name="munitions" value="0" step="any">
                        </td>
                        <td>
                            <input type="number" class="form-control" name="steel" value="0" step="any">
                        </td>
                        <td>
                            <input type="number" class="form-control" name="aluminum" value="0" step="any">
                        </td>
                        <td>
                            <input type="number" class="form-control" name="food" value="0" step="any">
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            {{ csrf_field() }}
            <p class="help-block">Enter positive numbers to add to account and negative numbers to subtract from account</p>
            <input type="submit" name="editAccount" value="Edit" class="btn btn-block btn-primary">
        </form>
    </div>
</div>