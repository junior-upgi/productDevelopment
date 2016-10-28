<script src="{{ url('/js/System/Group/JoinUser.js?x=1') }}"></script>
<div class="modal fade" id="JoinModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="modalTitle">加入群組成員</h4>
            </div>
            <form class="form-horizontal">
                <div class="modal-body">
                    <div class="form-group">
                        <div class="col-md-6">
                            <div class="input-group">
                                <input type="text" class="form-control" id="joinID" placeholder="請輸入員工編號">
                                <span class="input-group-btn">
                                    <button class="btn btn-primary" onclick="join()">加入</button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <table class="table table-bordered table-condensed">
                        <thead>
                            <tr>
                                <td class="col-md-2">員工編號</td>
                                <td class="col-md-2">單位</td>
                                <td class="col-md-2">姓名</td>
                                <td class="col-md-1"></td>
                            </tr>
                        </thead>
                        <tbody id="tbody">
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