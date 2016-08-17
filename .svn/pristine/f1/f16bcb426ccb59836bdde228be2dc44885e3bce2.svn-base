@extends('layouts.masterpage')

@section('content')
    <link rel="stylesheet" type="" href="{{url('/')}}/css/Process/ProcessList.css">
    <script src="{{url('/')}}/js/Process/ProcessList.js?x=1"></script>
    <!--breadcrumb-->
    <ol class="breadcrumb">
        <li><a href="{{url('/')}}/Project/ProjectList">開發案清單</a></li>
        <li><a href="{{url('/')}}/Product/ProductList/{{$ProductData->projectID}}">開發產品清單</a></li>
        <li class="active">產品開發流程</li>
    </ol>
    <!--product info panel-->
    <p class="bg-info">
        <span style="margin-right:20px;">產品代碼：{{$ProductData->referenceNumber}}</span>
        <span style="margin-right:20px;">產品名稱：{{$ProductData->referenceName}}</span>
        <span style="margin-right:20px;">需求數量：{{$ProductData->requiredQuantity}}</span>
        <span style="margin-right:20px;">交貨期限：{{date('Y-m-d', strtotime($ProductData->deadline))}}</span>   
    </p>
    <!--tool bar-->
    <nav class="navbar navbar-default" role="navigation">
        <ul class="nav navbar-nav">
            <form action="" class="navbar-form">
                <button type="button" class="btn btn-primary" onclick="AddShow()">新增</button>
                <button type="button" class="btn btn-warning" onclick="SaveSort()">儲存排序</button>
            </form>
        </ul>
    </nav>
    <input type="hidden" id="ProductID" name="ProductID" value="{{$ProductData->ID}}">
    <table class="table table-bordered">
        <thead>
            <tr>
                <td width="60"></td>
                <td>#</td>
                <td class="text-center">類別</td>
                <td>代號</td>
                <td>名稱</td>
                <td>負責人</td>
                <td class="col-md-1">工時</td>
                <td class="col-md-3">工期</td>
                <td width="100" class="text-center">完成時間</td>
                <td width="60"></td>
            </tr>
        </thead>
        <tbody id="tableSort">
            {{--*/ $CostCount = 0 /*--}}
            {{--*/ $i = 1 /*--}}
            @foreach($ProcessList as $list)
                {{--*/ $StartDate = $CostCount /*--}}
                {{--*/ $EndDate = ($CostCount += $list->timeCost) - 1 /*--}}
                @if($list->complete == "0")
                    <tr id="{{$list->ID}}">
                @else
                    <tr id="{{$list->ID}}" class="ui-state-disabled">
                @endif
                    <td>
                        <button type="button" class="btn btn-sm btn-default" onclick="EditShow('{{$list->ID}}')">編輯</button>
                    </td>
                    <td>{{$i++}}</td>
                    <td class="text-center">
                        <span>{{$list->PhaseName}}</span>
                    </td>
                    <td>
                        <span>{{$list->referenceNumber}}</span>
                    </td>
                    <td>
                        <span>{{$list->referenceName}}</span>
                    </td>
                    <td>
                        <span>{{$list->NodeName}}</span>
                        <span>{{$list->name}}</span>
                    </td>
                    <td>
                        <span>{{$list->timeCost}}</span>
                    </td>
                    <td>
                        <span>{{date('Y-m-d', strtotime($ProductData->startDate . '+' . $StartDate . ' day'))}} 
                            ~ {{date('Y-m-d', strtotime($ProductData->startDate . '+' . $EndDate . ' day'))}}</span>
                    </td>
                    <td class="text-center">
                        @if($list->complete == "0")
                            <button type="button" class="btn btn-sm btn-default" onclick="Complete('{{$list->ID}}')">完成</button>
                        @else
                            <span class="label label-danger">
                                {{date('Y-m-d', strtotime($list->completeTime))}}
                            </span>
                        @endif
                    </td>
                    <td>
                        <button type="button" class="btn btn-sm btn-danger">刪除</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
   
    @include('Process.AddProcess')
    @include('Process.EditProcess')
@endsection