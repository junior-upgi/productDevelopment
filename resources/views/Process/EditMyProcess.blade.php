<!--編輯產品開發流程視窗-->
<div class="modal fade" id="EditModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">編輯產品開發流程</h4>
            </div>
            <form id="EditProcessForm" class="form-horizontal" action method="POST">
                <div class="modal-body">
                    <div class="modal-body">
                        <input type="hidden" name="_token" value="{{{ csrf_token() }}}">
                        <input type="hidden" id="ProcessID" name="ProcessID" value="">
                        <input type="hidden" id="fileSet" name="fileSet" value="">
                        @php 
                            $disabled = 'disabled';
                            if (Auth::user()->authorization === '99') {
                                $disabled = '';
                            }
                        @endphp
                        <div class="form-group">
                            <label for="ProcessNumber" class="col-md-4 control-label">開發程序代號</label>
                            <div class="col-md-5">
                                <input type="text" class="form-control" {{$disabled}} id="ProcessNumber" name="ProcessNumber" value="" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="ProcessName" class="col-md-4 control-label">開發程序名稱</label>
                            <div class="col-md-5">
                                <input type="text" class="form-control" {{$disabled}} id="ProcessName" name="ProcessName" value="" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="PhaseID" class="col-md-4 control-label">程序類別</label>
                            <div class="col-md-3">
                                <select class="form-control" {{$disabled}} id="PhaseID" name="PhaseID" required>
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
                                <input type="text" class="form-control date form_datetime" {{$disabled}} readonly id="ProcessStartDate" name="ProcessStartDate" value="" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="TimeCost" class="col-md-4 control-label">工時(日)</label>
                            <div class="col-md-5">
                                <input type="text" class="form-control" id="TimeCost" name="TimeCost" value="" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="NodeID" class="col-md-4 control-label">負責人</label>
                            <div class="col-md-3">
                                <select class="form-control" {{$disabled}} id="NodeID" name="NodeID" onchange="GetStaff('Edit')" required>
                                    <option value="">請選擇單位</option>
                                    @foreach($NodeList as $list)
                                        <option value="{{$list->ID}}">{{$list->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select class="form-control" {{$disabled}} id="StaffID" name="StaffID" required>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4">完成品圖片</label>
                            <div class="col-md-6">
                                <div id="editImgDiv">
                                </div>
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