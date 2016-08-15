@extends('layouts.masterpage')

@section('content')
    <!--breadcrumb-->
    <ol class="breadcrumb">
        <li><a href="/Project/ProjectList">開發案清單</a></li>
        <li class="active">開發產品清單</li>
    </ol>
    <!--project info panel-->
    <p class="bg-info">
        <span style="margin-right:20px;">專案代碼：{{$ProjectData->referenceNumber}}</span>
        <span style="margin-right:20px;">專案名稱：{{$ProjectData->referenceName}}</span>
        <span style="margin-right:20px;">客戶名稱：{{$ProjectData->ClientName}}</span>
        <span style="margin-right:20px;">業務員：{{$ProjectData->StaffName}}</span>   
    </p>
    <!--tool bar-->
    <nav class="navbar navbar-default" role="navigation">
        <ul class="nav navbar-nav">
            <form action="" class="navbar-form">
                <a href="/Product/AddProduct/{{$ProjectData->ID}}" class="btn btn-primary">新增</a>
            </form>
        </ul>
    </nav>
    <!--data table-->
    <table class="table table-bordered" style="margin-top:20px;">
        <thead>
            <tr>
                <td width=60></td>
                <td width=60></td>
                <td>產品代號</td>
                <td>產品名稱</td>
                <td>需求數量</td>
                <td>交貨數量</td>
                <td>開始時間</td>
                <td>交貨時限</td>
                <td width=50>狀態</td>
                <td width=60></td>
            </tr>
        </thead>
        <tbody>
            @foreach($ProductList as $list)
                <tr>
                    <td><a href="/Product/EditProduct/{{$list->ID}}" class="btn btn-sm btn-default">編輯</a></td>
                    <td><a href="/Process/ProcessList/{{$list->ID}}" class="btn btn-sm btn-info">時程</a></td>
                    <td>{{$list->referenceNumber}}</td>
                    <td>{{$list->referenceName}}</td>
                    <td>{{$list->requiredQuantity}}</td>
                    <td>{{$list->deliveredQuantity}}</td>
                    <td>{{date('Y-m-d', strtotime($list->startDate))}}</td>
                    <td>{{date('Y-m-d', strtotime($list->deadline))}}</td>
                    <td></td>
                    <td><input type="button" class="btn btn-sm btn-danger" value="刪除" onclick="DoDelete()"></td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection