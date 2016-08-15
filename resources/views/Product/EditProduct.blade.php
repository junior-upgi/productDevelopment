@extends('layouts.masterpage')

@section('content')
    <script src="/js/Product/EditProduct.js?x=1"></script>
    <!--breadcrumb-->
    <ol class="breadcrumb">
        <li><a href="/Project/ProjectList">開發案清單</a></li>
        <li><a href="/Product/ProductList/{{$ProductData->projectID}}">開發產品清單</a></li>
        <li class="active">編輯開發產品</li>
    </ol>
    <!--add project form-->
    <form id="EditProductForm" class="form-horizontal" action method="POST">
        <input type="hidden" name="_token" value="{{{ csrf_token() }}}">
        <input type="hidden" id="ProjectID" name="ProjectID" value="{{$ProductData->projectID}}">
        <input type="hidden" id="ProductID" name="ProductID" value="{{$ProductData->ID}}">
        <div class="form-group">
            <label for="ProductNumber" class="col-md-2 control-label">開發產品代號</label>
            <div class="col-md-5">
                <input type="text" class="form-control" name="ProductNumber" value="{{$ProductData->referenceNumber}}" required>
            </div>
        </div>
        <div class="form-group">
            <label for="ProductName" class="col-md-2 control-label">開發產品名稱</label>
            <div class="col-md-5">
                <input type="text" class="form-control" name="ProductName" value="{{$ProductData->referenceName}}" required>
            </div>
        </div>
        <div class="form-group">
            <label for="RequiredQuantity" class="col-md-2 control-label">需求數量</label>
            <div class="col-md-2">
                <input type="number" class="form-control text-right" name="RequiredQuantity" value="{{$ProductData->requiredQuantity}}" required>
            </div>
        </div>
        <div class="form-group">
            <label for="DeliveredQuantity" class="col-md-2 control-label">交貨數量</label>
            <div class="col-md-2">
                <input type="number" class="form-control text-right" name="DeliveredQuantity" value="{{$ProductData->deliveredQuantity}}" required>
            </div>
        </div>
        <div class="form-group">
            <label for="Deadline" class="col-md-2 control-label">交貨期限</label>
            <div class="col-md-2">
                <input type="text" class="form-control date form_datetime" readonly id="Deadline" name="Deadline" value="{{date('Y-m-d', strtotime($ProductData->deadline))}}" required>
            </div>
        </div>
         <div class="form-group">
            <label for="StartDate" class="col-md-2 control-label date">開發啟始時間</label>
            <div class="col-md-2">
                <input type="text" class="form-control date form_datetime" readonly id="StartDate" name="StartDate" value="{{date('Y-m-d', strtotime($ProductData->startDate))}}" required>
            </div>
        </div>
        <div class="form-group">
            <label for="PriorityLevel" class="col-md-2 control-label">優先等級</label>
            <div class="col-md-2">
                <select name="PriorityLevel" id="PriorityLevel" class="form-control" required>
                    <option value="">請選擇優先等級</option>
                    @foreach($PriorityLevelList as $list)
                        @if($list->paracodeno == $ProductData->priorityLevel)
                            <option value="{{$list->paracodeno}}" selected>{{$list->paracodename}}</option>
                        @else
                            <option value="{{$list->paracodeno}}">{{$list->paracodename}}</option>
                        @endif
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group text-right col-md-7">
            <a href="/Product/ProductList/{{$ProductData->projectID}}" class="btn btn-default">取消</a>
            <button type="submit" class="btn btn-primary" data-loading-text="資料送出中..." autocomplete="off" 
                id="BtnSave" name="BtnSave" onclick="DoUpdate()">儲存</button>
        </div>
    </form>
@endsection