<div class="modal fade" id="addCredModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel">Add credentials</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="site">Site</label>
                    <input type="text" name="site" id="site" class="form-control" autocomplete="off">
                </div>
                <div class="form-group">
                    <label for="user">Username</label>
                    <input type="text" name="user" id="user" class="form-control" autocomplete="off" >
                </div>
                <div class="form-group">
                    <label for="pass">Password</label>
                    <input type="password" name="pass" id="pass" class="form-control" autocomplete="off">
                </div>
                <div class="form-group">
                    <label for="notes">Notes</label>
                    <textarea class="form-control" name="notes" id="notes"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveCred">Save changes</button>
            </div>
        </div>
    </div>
</div>
