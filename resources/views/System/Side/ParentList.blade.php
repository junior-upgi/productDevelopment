<!--設定前置功能節點視窗-->
<div class="modal fade" id="ParentListModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">設定前置功能</h4>
            </div>
            <form id="ParentListForm" class="form-horizontal" action method="POST">
                <div class="modal-body">
                    <input type="hidden" name="_token" value="{{{ csrf_token() }}}">
                    <input type="hidden" id="ID" name="ID" value="">
                    <table class="table table-bordered">
                        <thead>
                            <td width=40></td>
                            <td width=120>功能名稱</td>
                            <td width=110>路由名稱</td>
                        </thead>
                        <tbody id="ParentListTable">
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">關閉</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->