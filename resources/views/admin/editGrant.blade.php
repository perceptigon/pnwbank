<div id="editGrant" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Edit Grant</h4>
            </div>
            <!-- Edit grant dialog -->
            <form method="post">
                <div class="modal-body">
                    <div class="xsForm">
                        <div class="form-group">
                            <label>Grant Number</label>
                            <input type="number" min="2" name="grantNum" class="form-control" required id="gNumber">
                        </div>
                        <div class="form-group">
                            <label>Amount ($)</label>
                            <input type="number" min="1" name="amount" class="form-control" required id="gAmount">
                        </div>
                        <div class="form-group">
                            <label>Infra Per City</label>
                            <input type="number" min="0" name="infPerCity" class="form-control" required id="gInf">
                        </div>
                        <div class="form-group">
                            <label>Projects</label>
                            <div class="checkbox">
                                <label><input type="checkbox" value="1" name="irondome" id="gIron"> CIA</label>
                            </div>
                            <div class="checkbox">
                                <label><input type="checkbox" value="1" name="NRF" id="gNRF"> Nuclear Research Facility</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>MMR Score</label>
                            <input type="number" min="0" name="mmrScore" class="form-control" id="gMMR">
                        </div>
                        <div class="form-group">
                            <label>Notes</label>
                            <textarea class="form-control" name="notes" id="gNotes"></textarea>
                            <p class="help-block">Use to add info to this grant on the grant page. Use <code>&lt;li&gt;</code> to separate things</p>
                        </div>
                        <div class="form-group">
                            <label>Enabled</label>
                            <div class="checkbox">
                                <label><input type="checkbox" value="1" name="enabled" id="gEnabled"> Enabled</label>
                            </div>
                            <p class="help-block">If disabled, no one will be able to apply for this city grant.</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" value="" id="editGID" name="id">
                    <input type="submit" name="editGrant" class="btn btn-success" value="Save">
                    <input type="submit" name="deleteGrant" class="btn btn-danger" value="Delete" form="deleteGrant">
                    <input type="hidden" value="" id="delGID" name="gID" form="deleteGrant">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
                {{ csrf_field() }}
            </form>
            <form method="post" id="deleteGrant">
                {{ csrf_field() }}
            </form>
        </div>
    </div>
</div>