<!--新增功能節點視窗-->
<div class="modal fade" id="AddSideModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">新增功能</h4>
            </div>
            <form id="AddSideForm" class="form-horizontal" action method="POST">
                <div class="modal-body">
                    <div class="modal-body">
                        <input type="hidden" name="_token" value="{{{ csrf_token() }}}">
                        <input type="hidden" id="systemID" name="systemID" value="{{$setSystem}}">
                        <div class="form-group">
                            <label for="sideName" class="col-md-4 control-label">功能名稱</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" id="sideName" name="sideName" value="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="parentName" class="col-md-4 control-label">父功能名稱</label>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <input type="text" readonly class="form-control" id="parentName" name="parentName" value="" onclick="GetParentList('Add')">
                                    <input type="hidden" id="parentID" name="parentID" value="">
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-default"  onclick="GetParentList('Add')">
                                            <span class="glyphicon glyphicon-list-alt"></span>
                                        </button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="route" class="col-md-4 control-label">路由名稱</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" id="route" name="route" value="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="yield" class="col-md-4 control-label">動作名稱</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" id="yield" name="yield" value="">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">關閉</button>
                        <button type="submit" class="btn btn-primary" data-loading-text="資料送出中..." autocomplete="off" 
                            id="BtnAdd" name="BtnAdd" onclick="DoInsert()">儲存</button>
                    </div>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->