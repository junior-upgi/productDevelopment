@extends('layouts.masterpage')
@section('project', 'active')
@section('content')
    <script src="{{url('/')}}/js/Project/EditProject.js?x=2"></script>
    <!--breadcrumb-->
    <ol class="breadcrumb">
        <li><a href="{{url('/')}}/Project/ProjectList">開發案清單</a></li>
        <li class="active">編輯開發案</li>
    </ol>
    <!--add project form-->
    <form id="EditProjectForm" class="form-horizontal" role="form" action method="POST">
        <input type="hidden" name="_token" value="{{{ csrf_token() }}}">
        <input type="hidden" id="ProjectID" name="ProjectID" value="{{$ProjectData->ID}}"> 
        <div class="form-group">
            <label for="referenceNumber" class="col-md-2 control-label">開發案代號</label>
            <div class="col-md-5">
                <input type="text" class="form-control" name="referenceNumber" value="{{$ProjectData->referenceNumber}}" required>
            </div>
        </div>
        <div class="form-group">
            <label for="ProjectName" class="col-md-2 control-label">開發案名稱</label>
            <div class="col-md-5">
                <input type="text" class="form-control" name="ProjectName" value="{{$ProjectData->referenceName}}" required>
            </div>
        </div>
        <div class="form-group">
            <label for="ClientID" class="col-md-2 control-label">顧客名稱</label>
            <div class="col-md-5">
                <select class="form-control" id="ClientID" name="ClientID" required>
                    <option value="">請選擇顧客</option>
                    @foreach($ClientList as $list)
                        @if($list->ID == $ProjectData->clientID)
                            <option value="{{$list->ID}}" selected="selected">{{$list->name}}</option>
                        @else
                            <option value="{{$list->ID}}">{{$list->name}}</option>
                        @endif
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="NodeID" class="col-md-2 control-label">負責人</label>
            <div class="col-md-3">
                <select class="form-control" id="NodeID" name="NodeID" onchange="GetSales()" required>
                    <option value="">請選擇單位</option>
                    @foreach($NodeList as $list)
                        @if($list->ID == $ProjectData->nodeID)
                            <option value="{{$list->ID}}" selected="selected">{{$list->name}}</option>
                        @else
                            <option value="{{$list->ID}}">{{$list->name}}</option>
                        @endif
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select class="form-control" id="SalesID" name="SalesID" required>
                    <option value="">請選擇姓名</option>
                    @foreach($StaffList as $list)
                        @if($list->ID == $ProjectData->salesID)
                            <option value="{{$list->ID}}" selected="selected">{{$list->name}}</option>
                        @else
                            <option value="{{$list->ID}}">{{$list->name}}</option>
                        @endif
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="ProjectDeadline" class="col-md-2 control-label date">交貨期限</label>
            <div class="col-md-3">
                <input type="text" class="form-control date form_datetime" readonly id="ProjectDeadline" name="ProjectDeadline" value="{{date('Y-m-d', strtotime($ProjectData->projectDeadline))}}" required>
            </div>
        </div>
        <div class="form-group col-md-7 text-right">
            <a href="{{url('/')}}/Project/ProjectList" class="btn btn-default">取消</a> 
            <button type="submit" class="btn btn-primary" data-loading-text="資料送出中..." autocomplete="off" 
                id="BtnSave" name="BtnSave" onclick="DoUpdate()">儲存</button>
        </div>
    </form>
@endsection