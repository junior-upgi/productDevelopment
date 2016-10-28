<div class="modal fade" id="AddModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="modalTitle"></h4>
            </div>
            <form id="AddForm" class="form-horizontal" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="_token" value="{{{ csrf_token() }}}">
                    <input type="hidden" id="type" name="type" value="">
                    <input type="hidden" id="id" name="id" value="">
                    <div class="form-group">
                        <label for="reference" class="col-md-4 control-label">群組名稱</label>
                        <div class="col-md-5">
                            <input type="text" class="form-control" id="reference" name="reference" value="" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">關閉</button>
                    <button type="submit" class="btn btn-primary" data-loading-text="資料送出中..." autocomplete="off" id="btnSave"></button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->