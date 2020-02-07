<div class="modal fade" id="importCredModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel">Import credentials</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <p>Import a csv file with the following format:</p>
                <pre>site,username,password,notes</pre>
                <p class="text-danger">Warning: Malformed rows will be skipped.</p>
                <form method="post" action="/import" id="creduploadform" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="group" value="{{ $groupid }}">
                    <div class="form-group">
                        <input type="file" name="csvfile" id="csvfile">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="importCredSave">Import</button>
            </div>
        </div>
    </div>
</div>
