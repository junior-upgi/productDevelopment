@extends('layouts.masterpage')
@section('project', 'active')
@section('content')
    <script src="{{url('/')}}/js/Project/AddProject.js?x=2"></script>
    <!--breadcrumb-->
    <ol class="breadcrumb">
        <li><a href="{{url('/')}}/Project/ProjectList">開發案清單</a></li>
        <li class="active">新增開發案</li>
    </ol>
    <!--add project form-->
    <form id="AddProjectForm" class="form-horizontal" role="form" action method="POST">
        <input type="hidden" name="_token" value="{{{ csrf_token() }}}">
        <div class="form-group">
            <label for="referenceNumber" class="col-md-2 control-label">開發案代號</label>
            <div class="col-md-5">
                <input type="text" class="form-control" name="referenceNumber" value="" required>
            </div>
        </div>
        <div class="form-group">
            <label for="ProjectName" class="col-md-2 control-label">開發案名稱</label>
            <div class="col-md-5">
                <input type="text" class="form-control" name="ProjectName" value="" required>
            </div>
        </div>
        <div class="form-group">
            <label for="searchID" class="col-md-2 control-label">顧客名稱</label>
            <div class="col-md-5">
                <div class="input-group">
                    <input type="hidden" id="ClientID" name="ClientID" value="">
                    <input type="text" class="form-control col-md-5" id="searchID">
                    <ul class="dropdown-menu dropdown-menu-right" role="menu" style="height: 350px;">
                    </ul>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="NodeID" class="col-md-2 control-label">負責人</label>
            <div class="col-md-3">
                <select class="form-control" id="NodeID" name="NodeID" onchange="GetSales()" required>
                    <option value="">請選擇單位</option>
                    @foreach($NodeList as $list)
                        <option value="{{$list->ID}}">{{$list->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select class="form-control" id="SalesID" name="SalesID" required>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="ProjectDeadline" class="col-md-2 control-label date">交貨期限</label>
            <div class="col-md-3">
                @php
                    $date = Carbon\Carbon::now();
                    $date = date('Y-m-d', strtotime($date));
                @endphp
                <input type="text" class="form-control date form_datetime" readonly id="ProjectDeadline" name="ProjectDeadline" value="{{ $date }}" required>
            </div>
        </div>
        <div class="form-group text-right col-md-7">
            <a href="{{url('/')}}/Project/ProjectList" class="btn btn-default">取消</a>
            <button type="submit" class="btn btn-primary" data-loading-text="資料送出中..." autocomplete="off" 
                id="BtnSave" name="BtnSave" onclick="DoInsert()">儲存</button>
        </div>
    </form>
@endsection