@extends('layouts.masterpage')
@section('project', 'active')
@section('content')
    <script src="{{url('/')}}/js/Product/ProductList.js?x=2"></script>
    <style>
        .panel-body .btn {
            margin-bottom: 10px;
        }
        .panel-body {
            padding-bottom: 0px;
        }
    </style>
    <!--breadcrumb-->
    <ol class="breadcrumb">
        <li><a href="{{url('/')}}/Project/ProjectList">開發案清單</a></li>
        <li class="active">開發產品清單</li>
    </ol>
    @inject('system', 'App\Presenters\SystemPresenter')
    <!--project info panel-->
    <p class="bg-info">
        {{ "[$ProjectData->referenceNumber] [$ProjectData->referenceName] [$ProjectData->clientName] [$ProjectData->salesName]" }}
        <!--
        <span style="margin-right:20px;">專案代碼：{{$ProjectData->referenceNumber}}</span>
        <span style="margin-right:20px;">專案名稱：{{$ProjectData->referenceName}}</span>
        <span style="margin-right:20px;">客戶名稱：{{$ProjectData->clientName}}</span>
        <span style="margin-right:20px;">業務員：{{$ProjectData->salesName}}</span>   
        -->
    </p>
    <!--tool bar-->
    @php
        Auth::user()->authorization === '1' ? $UserRole = ' disabled' : $UserRole='';
    @endphp
    <div class="panel panel-default">
        <div class="panel-body">
            <a href="{{url('/')}}/Project/ProjectList" class="btn btn-default">
                <span class="glyphicon glyphicon-chevron-left"></span>
            </a>
            <a href="{{url('/')}}/Product/AddProduct/{{$ProjectData->ID}}" class="btn btn-primary {{$UserRole}}"><span class="glyphicon glyphicon-plus">新增</span></a>
            <button type="button" class="btn btn-warning" onclick="Notify()"><span class="glyphicon glyphicon-phone">發送推播</button>
        </div>
    </div>
    <!--data table-->
    <div class="table-responsive">
        <table class="table table-bordered table-condensed">
            <thead>
                <tr>
                    <td width=50></td>
                    <td width=60></td>
                    <td width=140>產品代號</td>
                    <td>產品名稱</td>
                    <td width=80>需求數量</td>
                    <td width=80>交貨數量</td>
                    <td width=110>工期</td>
                    <td width=100>完工期限</td>
                    <td width=80 class="text-center">完成時間</td>
                    <td width=50></td>
                </tr>
            </thead>
            <tbody>
                @php
                    use Carbon\Carbon;
                    $Now = strtotime(Carbon::now() . '-1 day');
                @endphp
                @foreach($ProductList as $list)
                    <tr>
                        <td><a href="{{url('/')}}/Product/EditProduct/{{$list->ID}}" data-toggle="tooltip" data-placement="top" title="編輯" class="btn btn-sm btn-default {{$UserRole}}"><span class="glyphicon glyphicon-edit"></span></a></td>
                        <td><a href="{{url('/')}}/Process/ProcessList/{{$list->ID}}" class="btn btn-sm btn-info">時程</a></td>
                        <td>{{$list->referenceNumber}}</td>
                        <td>
                            {{$list->referenceName}}
                            <!--{!! $system->getProductPic($list->ID) !!}-->
                            {!! $system->getAttach($list->contentAttach) !!}
                        </td>
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
                        <td>
                            <button class="btn btn-sm btn-danger {{$UserRole}}" data-toggle="tooltip" data-placement="top" title="刪除" onclick="DoDelete('{{$ProjectData->ID}}', '{{$list->ID}}')"><span class="glyphicon glyphicon-trash"></span></button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="text-center">
        {{$ProductList->links()}}
    </div>
    @include('Process.PicModal')
    @include('System.Service.Notify')
@endsection