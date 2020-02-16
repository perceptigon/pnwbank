<div class="panel panel-default">
    <div class="panel-heading">Deposit</div>
    <div class="panel-body">
        <form method="post">
            <div class="form-group">
                <input type="submit" class="btn btn-primary btn-block" value="Create Deposit Request" name="createDeposit">
                {{ csrf_field() }}
                <p class="help-block">After clicking this button, a message will be sent to you in-game with instructions on how to deposit your money or resources.</p>
            </div>
        </form>
    </div>
</div>
