@extends('layouts.masterpage')

@section('content')
    <link rel="stylesheet" href="/css/Process/ProcessList.css">
    <script src="/script/jquery.sortable.js?x=1"></script>
    <script src="/js/Process/ProcessList.js?x=1"></script>
    <!--breadcrumb-->
    <ol class="breadcrumb">
        <li><a href="/Project/ProjectList">開發案清單</a></li>
        <li><a href="/Product/ProductList/{{$ProductData->projectID}}">開發產品清單</a></li>
        <li class="active">產品開發流程</li>
    </ol>
    <!--product info panel-->
    <p class="bg-info">
        <span style="margin-right:20px;">產品代碼：{{$ProductData->referenceNumber}}</span>
        <span style="margin-right:20px;">產品名稱：{{$ProductData->referenceName}}</span>
        <span style="margin-right:20px;">需求數量：{{$ProductData->requiredQuantity}}</span>
        <span style="margin-right:20px;">交貨時限：{{$ProductData->deadline}}</span>   
    </p>
    <!--tool bar-->
    <nav class="navbar navbar-default" role="navigation">
        <ul class="nav navbar-nav">
            <form action="" class="navbar-form">
                <a href="" class="btn btn-primary">新增流程</a>
                <button type="button" class="btn btn-primary" onclick="AddShow()">互動新增</button>
                <button type="button" class="btn btn-primary" onclick="EditShow()">互動編輯</button>
            </form>
        </ul>
    </nav>
    <div id="Sort">
        <ul class="items" id="Process" style="list-style-type:none;">
            @foreach($ProcessList as $list)
            <li class="list bg-warning" id="{{$list->ID}}">
                <label for="">
                    {{$list->PhaseName}}
                    {{$list->referenceNumber}}
                    {{$list->referenceName}}
                    {{$list->NodeName}}
                    {{$list->name}}
                </label>
            </li>
            @endforeach
        </ul>
    </div>
    <button type="button" onclick="a()">send</button>
    @include('Process.AddProcess')
    @include('Process.EditProcess')
@endsection