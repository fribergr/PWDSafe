<div class="modal fade" id="showCredModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Show credentials</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="s_site">Site</label>
                    <input type="text" name="s_site" id="s_site" class="form-control">
                </div>
                <div class="form-group">
                    <label for="s_user">Username</label>
                    <input type="text" name="s_user" id="s_user" class="form-control">
                </div>
                <div class="form-group">
                    <label for="s_pass">Password</label>
                    <input type="text" name="s_pass" id="s_pass" class="form-control">
                </div>
                <div class="form-group">
                    <label for="s_notes">Notes</label>
                    <textarea class="form-control" name="s_notes" id="s_notes"></textarea>
                </div>
                <div class="form-group">
                    <label for="s_group">Move to group</label>
                    <select name="s_group" id="s_group" class="form-control">
                        @foreach (auth()->user()->groups as $group)
                            <option value="{{ $group->id }}" @if (!empty($currentgroup) && $currentgroup->id === $group->id) selected="selected" @endif >{{ $group->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-danger mr-auto" id="deleteCred" data-id="">Delete</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="updateCred" data-id="">Save</button>
            </div>
        </div>
    </div>
</div>
