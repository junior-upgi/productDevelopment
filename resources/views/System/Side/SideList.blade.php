@extends('layouts.systemOption')
@section('content')
    <script src="{{url('/')}}/js/System/Side/SideList.js?x=4"></script>
    <!--tool bar-->
    <nav class="navbar navbar-default" role="navigation">
        <ul class="nav navbar-nav">
            <form action="" class="navbar-form">
                <button type="button" class="btn btn-primary" onclick="AddShow()">新增</button>
            </form>
        </ul>
        <ul class="nav navbar-nav navbar-right" style="margin-right:0px">
            <form id="SetSystemForm" action="{{url('/')}}/SysOption/SideList" class="navbar-form" method="GET">
                <input type="hidden" name="_token" value="{{{ csrf_token() }}}">
                <div class="form-group">
                    <span>選擇系統分類：</span>
                    <select class="form-control" id="setSystem'" name="setSystem" onchange="SetSystemChange()">
                        @foreach($SystemList as $list)
                            <option value="{{$list->ID}}" {{$list->ID == $setSystem ? "selected" : ''}}>{{$list->systemName}}</option>
                        @endforeach
                    </select>
                </div>
            </form>
        </ul>
    </nav>
    <!--data table-->
    <table class="table table-bordered">
        <thead>
            <tr>
                <td width="60"></td>
                <td width="140">功能名稱</td>
                <td width="140">父功能名稱</td>
                <td width="200">路由名稱</td>
                <td width="120">動作名稱</td>
                <td width="60"></td>
            </tr>
        </thead>
        <tbody>
            @foreach($SideList as $list)
                <tr>
                    <td class="text-center">
                        <button type="button" class="btn btn-sm btn-default" onclick="EditShow('{{$list->ID}}')">編輯</button>
                    </td>
                    <td>{{$list->sideName}}</td>
                    <td>{{isset($list->getParent['sideName']) ? $list->getParent['sideName'] : '--'}}</td>
                    <td>{{$list->route}}</td>
                    <td>{{$list->yield}}</td>
                    <td class="text-center">
                        <button type="button" class="btn btn-sm btn-danger" onclick="DoDelete('{{$list->ID}}')">刪除</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="text-center">
        {{$SideList->links()}}
    </div>
    @include('System.Side.AddSide')
    @include('System.Side.EditSide')
    @include('System.Side.ParentList')
@endsection