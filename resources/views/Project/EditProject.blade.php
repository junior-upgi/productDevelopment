@extends('layouts.masterpage')
@section('project', 'active')
@section('content')
    <script src="{{ url('/js/Project/EditProject.js?x=1') }}"></script>
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
            <label for="searchID" class="col-md-2 control-label">顧客名稱</label>
            <div class="col-md-5">
                <div class="input-group">
                    <input type="hidden" id="ClientID" name="ClientID" value="{{ $ProjectData->clientID }}">
                    <input type="text" class="form-control col-md-5" id="searchID" value="{{ $ProjectData->clientName }}">
                    <ul class="dropdown-menu dropdown-menu-right" role="menu" style="height: 350px;">
                    </ul>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="searchUser" class="col-md-2 control-label">負責人</label>
            <div class="col-md-5">
                <div class="input-group">
                    <input type="hidden" id="SalesID" name="SalesID" value="{{ $ProjectData->salesID }}">
                    <input type="text" class="form-control" id="searchUser" value="{{ $ProjectData->salesName }}">
                    <ul class="dropdown-menu dropdown-menu-right" role="menu" style="height: 350px;">
                    </ul>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="ProjectDeadline" class="col-md-2 control-label date">交貨期限</label>
            <div class="col-md-3">
                <input type="text" class="form-control date form_datetime" readonly id="ProjectDeadline" name="ProjectDeadline" value="{{ date('Y-m-d', strtotime($ProjectData->projectDeadline)) }}" required>
            </div>
        </div>
        <div class="form-group col-md-7 text-right">
            <a href="{{ url('/Project/ProjectList') }}" class="btn btn-default">取消</a> 
            <button type="button" class="btn btn-primary" data-loading-text="資料送出中..." autocomplete="off" 
                id="BtnSave" name="BtnSave" onclick="DoUpdate()">儲存</button>
        </div>
    </form>
@endsection