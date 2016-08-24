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
                <a href="{{url('/')}}/Project/AddProject" class="btn btn-primary">新增</a>
            </form>
        </ul>
    </nav>
    <!--data table-->
    <table class="table table-bordered">
        <thead>
            <tr>
                <td width="60"></td>
                <td width="60"></td>
                <td>開發案號</td>
                <td>開發案名稱</td>
                <td>客戶名稱</td>
                <td>負責人</td>
                <td width="120">開始時間</td>
                <td width="120">完成時間</td>
                <td class="col-md-1">狀態</td>
                <td class="col-md-1"></td>
            </tr>
        </thead>
        <tbody>
            @foreach($ProjectList as $list)
                <tr>
                    <td class="text-center"><a href="{{url('/')}}/Project/EditProject/{{$list->ID}}" class="btn btn-sm btn-default">編輯</a></td>
                    <td class="text-center"><a href="{{url('/')}}/Product/ProductList/{{$list->ID}}" class="btn btn-sm btn-info">產品</a></td>
                    <td>{{$list->referenceNumber}}</td>
                    <td>{{$list->referenceName}}</td>
                    <td>{{$list->clientName}}</td>
                    <td>{{$list->salesNodeName . '_' . $list->salesName}}</td>
                    <td></td>
                    <td></td>
                    <td class="text-center"></td>
                    <td class="text-center"><input type="button" class="btn btn-sm btn-danger" value="刪除" onclick="DoDelete()"></td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="text-center">
        {{$ProjectList->links()}}
    </div>
@endsection