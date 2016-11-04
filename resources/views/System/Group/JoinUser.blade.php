<script src="{{ url('/js/System/Group/JoinUser.js?x=2') }}"></script>
<div class="modal fade" id="JoinModal">
    <div class="modal-dialog">
        <div class="modal-content" >
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="JoinTitle"></h4>
            </div>
            <input type="hidden" id="joinGroup" name="joinGroup" value="">
            <form class="form-horizontal" style="max-height:600px;overflow: auto;">
                <div class="modal-body" id="modalBody">
                    <div class="row">
                        <div class="col-lg-10">
                            <div class="input-group">
                                <input type="text" class="form-control" id="joinID">
                                <ul class="dropdown-menu dropdown-menu-right" role="menu">
                                </ul>
                                <span class="input-group-btn">
                                    <button class="btn btn-primary">加入</button>
                                </span>
                            </div>
                        </div>
                        <button type="button" class="btn btn-primary" onclick="join()">加入</button>
                    </div>
                    <p>
                        <table class="table table-bordered table-condensed">
                            <thead>
                                <tr>
                                    <td class="col-md-2">員工編號</td>
                                    <td class="col-md-2">單位</td>
                                    <td class="col-md-2">姓名</td>
                                    <td class="col-md-1"></td>
                                </tr>
                            </thead>
                            <tbody id="memberTable">
                            </tbody>
                        </table>
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">關閉</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->