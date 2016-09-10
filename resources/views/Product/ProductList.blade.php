@extends('layouts.masterpage')
@section('project', 'active')
@section('content')
    <script src="{{url('/')}}/js/Product/ProductList.js?x=3"></script>
    <!--breadcrumb-->
    <ol class="breadcrumb">
        <li><a href="{{url('/')}}/Project/ProjectList">開發案清單</a></li>
        <li class="active">開發產品清單</li>
    </ol>
    <!--project info panel-->
    <p class="bg-info">
        <span style="margin-right:20px;">專案代碼：{{$ProjectData->referenceNumber}}</span>
        <span style="margin-right:20px;">專案名稱：{{$ProjectData->referenceName}}</span>
        <span style="margin-right:20px;">客戶名稱：{{$ProjectData->clientName}}</span>
        <span style="margin-right:20px;">業務員：{{$ProjectData->salesName}}</span>   
    </p>
    <!--tool bar-->
    @php
        Auth::user()->authorization === '1' ? $UserRole = ' disabled' : $UserRole='';
    @endphp
    <nav class="navbar navbar-default" role="navigation">
        <ul class="nav navbar-nav">
            <form action="" class="navbar-form">
                <a href="{{url('/')}}/Project/ProjectList" class="btn btn-default">
                    <span class="glyphicon glyphicon-chevron-left"></span>
                </a>
                <a href="{{url('/')}}/Product/AddProduct/{{$ProjectData->ID}}" class="btn btn-primary {{$UserRole}}">新增</a>
            </form>
        </ul>
    </nav>
    <!--data table-->
    <table class="table table-bordered" style="margin-top:20px;">
        <thead>
            <tr>
                <td width=60></td>
                <td width=60></td>
                <td width=140>產品代號</td>
                <td>產品名稱</td>
                <td width=80>需求數量</td>
                <td width=80>交貨數量</td>
                <td width=110>工期</td>
                <td width=100>完工期限</td>
                <td width=80 class="text-center">完成時間</td>
                <td width=60></td>
            </tr>
        </thead>
        <tbody>
            @php
                use Carbon\Carbon;
                $Now = strtotime(Carbon::now() . '-1 day');
            @endphp
            @foreach($ProductList as $list)
                <tr>
                    <td><a href="{{url('/')}}/Product/EditProduct/{{$list->ID}}" class="btn btn-sm btn-default {{$UserRole}}">編輯</a></td>
                    <td><a href="{{url('/')}}/Process/ProcessList/{{$list->ID}}" class="btn btn-sm btn-info">時程</a></td>
                    <td>{{$list->referenceNumber}}</td>
                    <td>{{$list->referenceName}}</td>
                    <td class="text-right">{{$list->requiredQuantity}}</td>
                    <td class="text-right">{{$list->deliveredQuantity}}</td>
                    <td>
                        @if(isset($list->startDate))
                            {{date('Y-m-d', strtotime($list->startDate))}}
                            ~
                            {{date('Y-m-d', strtotime($list->endDate))}}
                        @endif
                    </td>
                    <td>{{date('Y-m-d', strtotime($list->deadline))}}</td>
                    <td class="text-center">
                        @php 
                            $Status = "";
                            if (($list->execute === '1') && ($list->productStatus === '0')) {
                                if (strtotime($list->nowEndDate) < $Now) {
                                    $Status = "label label-warning";
                                }
                                if (strtotime($list->deadline) < $Now) {
                                    $Status = "label label-danger";
                                }
                            }
                        @endphp
                        @if($list->productStatus === '1')
                            <span class="{{$Status}}">尚未開始</span>
                        @elseif($list->productStatus === '0')
                            <span class="{{$Status}}">進行中</span>
                        @elseif($list->productStatus === '2')
                            @php 
                                if (strtotime($list->maxCompleteTime) <= strtotime($list->deadline)) {
                                    $class = "label label-success";
                                } else {
                                    $class = "label label-danger";
                                }
                            @endphp
                            <span class="{{$class}}">{{date('Y-m-d', strtotime($list->maxCompleteTime))}}</span>
                        @endif
                    </td>
                    <td><input type="button" class="btn btn-sm btn-danger {{$UserRole}}" value="刪除" onclick="DoDelete('{{$ProjectData->ID}}', '{{$list->ID}}')"></td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="text-center">
        {{$ProductList->links()}}
    </div>
@endsection