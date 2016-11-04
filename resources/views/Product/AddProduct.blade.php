@extends('layouts.masterpage')
@section('project', 'active')
@section('content')
    <script src="{{url('/')}}/js/Product/AddProduct.js?x=1"></script>
    <!--breadcrumb-->
    <ol class="breadcrumb">
        <li><a href="{{url('/')}}/Project/ProjectList">開發案清單</a></li>
        <li><a href="{{url('/')}}/Product/ProductList/{{$ProjectID}}">開發產品清單</a></li>
        <li class="active">新增開發產品</li>
    </ol>
    <!--add project form-->
    <form id="AddProductForm" class="form-horizontal" action method="POST">
        <input type="hidden" name="_token" value="{{{ csrf_token() }}}">
        <input type="hidden" id="ProjectID" name="ProjectID" value="{{$ProjectID}}">
        <div class="form-group">
            <label for="ProductNumber" class="col-md-2 control-label">開發產品代號</label>
            <div class="col-md-5">
                <input type="text" class="form-control" name="ProductNumber" value="" required>
            </div>
        </div>
        <div class="form-group">
            <label for="ProductName" class="col-md-2 control-label">開發產品名稱</label>
            <div class="col-md-5">
                <input type="text" class="form-control" name="ProductName" value="" required>
            </div>
        </div>
        <div class="form-group">
            <label for="RequiredQuantity" class="col-md-2 control-label">需求數量</label>
            <div class="col-md-2">
                <input type="number" class="form-control text-right" min="0" max="9999999" name="RequiredQuantity" value="0" required>
            </div>
        </div>
        <div class="form-group">
            <label for="DeliveredQuantity" class="col-md-2 control-label">交貨數量</label>
            <div class="col-md-2">
                <input type="number" class="form-control text-right" min="0" max="9999999" name="DeliveredQuantity" value="0">
            </div>
        </div>
        <div class="form-group">
            <label for="Deadline" class="col-md-2 control-label date">完工期限</label>
            <div class="col-md-2">
                <input type="text" class="form-control date form_datetime" readonly id="Deadline" name="Deadline" value="" required>
            </div>
        </div>
        <!--
        <div class="form-group">
            <label for="StartDate" class="col-md-2 control-label date">開發啟始時間</label>
            <div class="col-md-2">
                <input type="text" class="form-control date form_datetime" readonly id="StartDate" name="StartDate" value="" required>
            </div>
        </div>
        -->
        <div class="form-group">
            <label for="PriorityLevel" class="col-md-2 control-label">優先等級</label>
            <div class="col-md-2">
                <select name="PriorityLevel" id="PriorityLevel" class="form-control" required>
                    <option value="">請選擇優先等級</option>
                    @foreach($PriorityLevelList as $list)
                        <option value="{{$list->paracodeno}}">{{$list->paracodename}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="Group" class="col-md-2 control-label">發送訊息群組</label>
            <div class="col-md-2">
                <select name="Group" id="Group" class="form-control" required>
                    <option value="">不發送</option>
                    @foreach($group as $g)
                        @if($g->reference == 'DevelopmentTeam')
                            <option value="{{$g->ID}}" selected="selected">{{$g->reference}}</option>
                        @else
                            <option value="{{$g->ID}}">{{$g->reference}}</option>
                        @endif
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-2">產品圖片</label>
            <div class="col-md-5">
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
                        zoomIcon: "",
                        zoomClass: "",
                        zoomTitle: "",
                    }
                });
            </script>
        </div>
        <div class="form-group text-right col-md-7">
            <a href="{{url('/')}}/Product/ProductList/{{$ProjectID}}" class="btn btn-default">取消</a>
            <button type="submit" class="btn btn-primary" data-loading-text="資料送出中..." autocomplete="off" 
                id="BtnSave" name="BtnSave" onclick="DoInsert()">儲存</button>
        </div>
    </form>
@endsection