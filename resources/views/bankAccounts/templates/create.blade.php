<div class="row">
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Create Account</h3>
            </div>
            <div class="panel-body">
                <form method="post">
                    <div class="form-group">
                        <label class="control-label" for="accountName">Account Name</label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="accountName" id="accountName" placeholder="Account Name" max="40" required>
                            <span class="input-group-btn">
                        <input type="submit" class="btn btn-primary" value="Create" name="createAccount">
                    </span>
                        </div>
                    </div>
                    {{ csrf_field() }}
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="panel panel-danger">
            <div class="panel-heading">
                <h3 class="panel-title">Delete Account</h3>
            </div>
            <div class="panel-body">
                <form method="post" onsubmit="return confirm('Are you sure you want to delete this account?')">
                    <div class="form-group">
                        <label class="control-label" for="del_accountName">Account Name</label>
                        <div class="input-group">
                            <select class="form-control" id="del_accountName" name="accountID">
                                @foreach ($accounts as $account)
                                    <option value="{{ $account->id }}">{{ $account->name }} - ${{ number_format($account->money, 2) }}</option>
                                @endforeach
                            </select>
                            <span class="input-group-btn">
                        <input type="submit" class="btn btn-danger" value="Delete" name="deleteAccount">
                    </span>
                        </div>
                    </div>
                    {{ csrf_field() }}
                </form>
            </div>
        </div>
    </div>
</div>
