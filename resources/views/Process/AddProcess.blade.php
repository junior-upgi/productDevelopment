<!--新增產品開發流程視窗-->
<div class="modal fade" id="AddModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">新增產品開發流程</h4>
            </div>
            <form id="AddProcessForm" class="form-horizontal" action method="POST">
                <div class="modal-body">
                    <input type="hidden" name="_token" value="{{{ csrf_token() }}}">
                    <input type="hidden" id="ProductID" name="ProductID" value="{{$ProductData->ID}}">
                    <div class="form-group">
                        <label for="ProcessNumber" class="col-md-4 control-label">開發程序代號</label>
                        <div class="col-md-5">
                            <input type="text" class="form-control" id="ProcessNumber" name="ProcessNumber" value="" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="ProcessName" class="col-md-4 control-label">開發程序名稱</label>
                        <div class="col-md-5">
                            <input type="text" class="form-control" id="ProcessName" name="ProcessName" value="" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="PhaseID" class="col-md-4 control-label">程序類別</label>
                        <div class="col-md-3">
                            <select class="form-control" id="PhaseID" name="PhaseID" required>
                                <option value="">請選擇類別</option>
                                @foreach($PhaseList as $list)
                                    <option value="{{$list->paracodeno}}">{{$list->paracodename}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="ProcessStartDate" class="col-md-4 control-label date">開始時間</label>
                        <div class="col-md-3">
                            <input type="text" class="form-control date form_datetime" readonly id="ProcessStartDate" name="ProcessStartDate" value="" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="TimeCost" class="col-md-4 control-label">工時(日)</label>
                        <div class="col-md-5">
                            <input type="text" class="form-control" id="TimeCost" name="TimeCost" value="" required>
                        </div>
                    </div>
                    @if($UserRole != 'disabled')
                        <div class="form-group">
                            <label for="NodeID" class="col-md-4 control-label">負責人</label>
                            <div class="col-md-3">
                                <select class="form-control" id="NodeID" name="NodeID" onchange="GetStaff('Add')" required>
                                    <option value="">請選擇單位</option>
                                    @foreach($NodeList as $list)
                                        <option value="{{$list->ID}}">{{$list->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select class="form-control" id="StaffID" name="StaffID" required>
                                </select>
                            </div>
                        </div>
                    @else
                        <input type="hidden" name="StaffID" value="{{ $user->erpID }}">
                    @endif
                    <div class="form-group">
                        <label for="note" class="col-md-4 control-label">備註</label>
                        <div class="col-md-7">
                            <textarea class="form-control" name="note" id="note" rows="5" style="resize: none;"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-4">完成品圖片</label>
                        <div class="col-md-6">
                            <input id="img" name="img" type="file" class="file-loading" data-show-upload="false" accept="image/*">
                        </div>
                        <script>
                            $("#img").fileinput({
                                language: 'zh-TW',
                                previewFileType: "image",
                                allowedFileExtensions: ["jpg", "jpeg", "png", "gif"],
                                previewClass: "bg-warning",
                                browseClass: "btn btn-success",
                                browseLabel: "選擇圖片",
                                browseIcon: "<i class=\"glyphicon glyphicon-picture\"></i> ",
                                removeClass: "btn btn-danger",
                                removeLabel: "移除",
                                removeIcon: "<i class=\"glyphicon glyphicon-trash\"></i> ",
                                fileActionSettings: {
                                    showZoom: false,
                                    showDrag: false,
                                }
                            });
                        </script>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">關閉</button>
                    <button type="submit" class="btn btn-primary" data-loading-text="資料送出中..." autocomplete="off" 
                        id="BtnAdd" name="BtnAdd" onclick="DoInsert()">新增</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->