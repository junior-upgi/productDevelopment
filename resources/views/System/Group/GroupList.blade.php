@extends('layouts.systemOption')
@section('content')
    <script src="{{ url('/js/System/Group/GroupList.js?x=1') }}"></script>
    <!--tool bar-->
    <nav class="navbar navbar-default" role="navigation">
        <ul class="nav navbar-nav">
            <div class="navbar-form">
                <button type="button" class="btn btn-primary" onclick="doAdd('')">新增</button>
            </div>
        </ul>
        <ul class="nav navbar-nav navbar-right" style="margin-right:0px">
            <form id="searchForm" action="{{ url('/SysOption/GroupList') }}" class="navbar-form" method="GET">
                <div class="form-group">
                    <input type="text" name="search" class="form-control" value="{{$search}}" placeholder="">
                </div>
                <button type="submit" class="btn btn-default">搜尋</button>
            </form>
        </ul>
    </nav>
    <!--data table-->
    <table class="table table-bordered">
        <thead>
            <tr>
                <td class="col-md-1 text-center">成員</td>
                <td>群組名稱</td>
                <td class="col-md-1">
                <td class="col-md-1">
            </tr>
        </thead>
        <tbody>
            @foreach($group as $g)
                @php
                    $json = (string) $g;
                @endphp
                <tr>
                    <td class="text-center">
                        <button type="button" class="btn btn-sm btn-default" onclick="member('{{ $g->ID }}', '{{ $g->reference }}')">管理</button>
                    </td>
                    <td>{{ $g->reference }}</td>
                    <td class="text-center">
                        <button type="button" class="btn btn-sm btn-default" onclick="doAdd('{{ $json }}')">編輯</button>
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-sm btn-danger" onclick="doDelete('{{ $g->ID }}')">刪除</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @include('System.Group.AddGroup')
    @include('System.Group.JoinUser')
@endsection