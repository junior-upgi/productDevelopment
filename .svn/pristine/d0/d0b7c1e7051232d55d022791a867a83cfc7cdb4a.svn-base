@extends('layouts.masterpage')

@section('content')
    <!--breadcrumb-->
    <ol class="breadcrumb">
        <li class="active">開發案清單</li>
    </ol>
    <!--tool bar-->
    <nav class="navbar navbar-default" role="navigation">
        <ul class="nav navbar-nav">
            <form action="" class="navbar-form">
                <a href="/Project/AddProject" class="btn btn-primary">新增</a>
            </form>
        </ul>
    </nav>
    <!--data table-->
    <table class="table table-bordered">
        <thead>
            <tr>
                <td class="col-md-1"></td>
                <td class="col-md-1"></td>
                <td>開發案號</td>
                <td>開發案名稱</td>
                <td>客戶名稱</td>
                <td>業務員</td>
                <td>開始時間</td>
                <td>完成時間</td>
                <td class="col-md-1">狀態</td>
                <td class="col-md-1"></td>
            </tr>
        </thead>
        <tbody>
            @foreach($ProjectList as $list)
                <tr>
                    <td class="text-center"><a href="/Project/EditProject/{{$list->ID}}" class="btn btn-sm btn-default">編輯</a></td>
                    <td class="text-center"><a href="/Product/ProductList/{{$list->ID}}" class="btn btn-sm btn-info">產品</a></td>
                    <td>{{$list->referenceNumber}}</td>
                    <td>{{$list->referenceName}}</td>
                    <td>{{$list->ClientName}}</td>
                    <td>{{$list->NodeName . '_' . $list->StaffName}}</td>
                    <td></td>
                    <td></td>
                    <td class="text-center"></td>
                    <td class="text-center"><input type="button" class="btn btn-sm btn-danger" value="刪除" onclick="DoDelete()"></td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection