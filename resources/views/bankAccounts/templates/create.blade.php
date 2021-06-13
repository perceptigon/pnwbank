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
  
</div>
