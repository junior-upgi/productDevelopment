<!--設定前置流程視窗-->
<div class="modal fade" id="PreparationModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">設定前置流程</h4>
            </div>
            <form id="SetPreparationForm" class="form-horizontal" action method>
                <div class="modal-body">
                    <input type="hidden" name="_token" value="{{{ csrf_token() }}}">
                    <input type="hidden" id="ProductID" value="">
                    <input type="hidden" id="ProcessID" value="">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <td width=40></td>
                                <td width=40>#</td>
                                <td width=60 class="text-center">類別</td>
                                <td width=120>代號</td>
                                <td>名稱</td>
                                <td width=120>負責人</td>
                                <td width=50 class="text-center">工時</td>
                                <td width=110 class="text-center">工期</td>
                            </thead>
                            <tbody id="PreparationList">
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">關閉</button>
                    <button type="button" class="btn btn-primary" data-loading-text="資料儲存中..." autocomplete="off" 
                        id="BtnAdd" name="BtnAdd" onclick="DoSetPreparation()">儲存</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->