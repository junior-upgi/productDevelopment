@extends('layouts.masterpage')

@section('content')
    <script src="/js/Project/EditProject.js?x=2"></script>
    <!--breadcrumb-->
    <ol class="breadcrumb">
        <li><a href="/Project/ProjectList">開發案清單</a></li>
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
                        @if($list->id == $ProjectData->clientID)
                            <option value="{{$list->id}}" selected="selected">{{$list->reference}}</option>
                        @else
                            <option value="{{$list->id}}">{{$list->reference}}</option>
                        @endif
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="NodeID" class="col-md-2 control-label">業務員</label>
            <div class="col-md-3">
                <select class="form-control" id="NodeID" name="NodeID" onchange="GetSales()" required>
                    <option value="">請選擇單位</option>
                    @foreach($NodeList as $list)
                        @if($list->id == $ProjectData->nodeID)
                            <option value="{{$list->id}}" selected="selected">{{$list->reference}}</option>
                        @else
                            <option value="{{$list->id}}">{{$list->reference}}</option>
                        @endif
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-control" id="SalesID" name="SalesID" required>
                    <option value="">請選擇姓名</option>
                    @foreach($StaffList as $list)
                        @if($list->id == $ProjectData->salesID)
                            <option value="{{$list->id}}" selected="selected">{{$list->name}}</option>
                        @else
                            <option value="{{$list->id}}">{{$list->name}}</option>
                        @endif
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group col-md-7 text-right">
            <a href="/Project/ProjectList" class="btn btn-default">取消</a> 
            <button type="submit" class="btn btn-primary" data-loading-text="資料送出中..." autocomplete="off" 
                id="BtnSave" name="BtnSave" onclick="DoUpdate()">儲存</button>
        </div>
    </form>
    <div>
        
    </div>
@endsection