<!--編輯員工資料視窗-->
<div class="modal fade" id="EditModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">編輯員工資料</h4>
            </div>
            <form id="EditStaffForm" class="form-horizontal" action method="POST">
                <div class="modal-body">
                    <div class="modal-body">
                        <input type="hidden" name="_token" value="{{{ csrf_token() }}}">
                        <input type="hidden" id="ID" name="ID" value="">
                        <div class="form-group">
                            <label for="" class="col-md-4 control-label">單位/姓名</label>
                            <div class="col-md-6">
                                <p><span ID="StaffName"></span></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="SuNodeID" class="col-md-4 control-label">主管</label>
                            <div class="col-md-3">
                                <select class="form-control node" id="SuNodeID" name="SuNodeID" onchange="GetStaff('Su')" required>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select class="form-control" id="SuperivisorID" name="SuperivisorID" required>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="PrNodeID" class="col-md-4 control-label">代理人1</label>
                            <div class="col-md-3">
                                <select class="form-control node" id="PrNodeID" name="PrNodeID" onchange="GetStaff('Pr')" required>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select class="form-control" id="PrimaryDelegateID" name="PrimaryDelegateID" required>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="SeNodeID" class="col-md-4 control-label">代理人2</label>
                            <div class="col-md-3">
                                <select class="form-control node" id="SeNodeID" name="SeNodeID" onchange="GetStaff('Se')" required>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select class="form-control" id="SecondaryDelegateID" name="SecondaryDelegateID" required>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">關閉</button>
                        <button type="submit" class="btn btn-primary" data-loading-text="資料送出中..." autocomplete="off" 
                            id="BtnEdit" name="BtnEdit" onclick="DoUpdate()">更新</button>
                    </div>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->