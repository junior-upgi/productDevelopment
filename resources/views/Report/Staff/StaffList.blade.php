@extends('layouts.masterpage')
@section('sysoption', 'active')
@section('content')
    <script src="{{url('/')}}/js/Staff/StaffList.js?x=3"></script>
    @inject('sys', 'App\Presenters\SystemPresenter')
    <ol class="breadcrumb">
        <li class="active">員工資料維護</li>
    </ol>
    <!--tool bar-->
    <nav class="navbar navbar-default" role="navigation">
        <ul class="nav navbar-nav">
            <form action="" class="navbar-form">
                <button type="button" class="btn btn-warning" onclick="DataSyn()">資料同步</button>
            </form>
        </ul>
        <ul class="nav navbar-nav navbar-right" style="margin-right:0px">
            <form action="{{url('/')}}/SysOption/StaffList" class="navbar-form" method="GET">
                <div class="form-group">
                    <input type="hidden" name="_token" value="{{{ csrf_token() }}}">
                    <input type="text" name="Search" class="form-control" value="{{$Search}}" placeholder="編號/姓名/單位/職稱">
                </div>
                <button type="submit" class="btn btn-default">搜尋</button>
            </form>
        </ul>
    </nav>
    <!--data table-->
    <table class="table table-bordered">
        <thead>
            <tr>
                <td width="60"></td>
                <td width="120">單位</td>
                <td width="80">姓名</td>
                <td width="140">職稱</td>
                <td width="200">主管</td>
                <td>代理人1</td>
                <td>代理人2</td>
                <td>推播</td>
            </tr>
        </thead>
        <tbody>
            @foreach($StaffList as $list)
                <tr>
                    <td class="text-center">
                        <button type="button" class="btn btn-sm btn-default" onclick="EditShow('{{$list['ID']}}')">編輯</button>
                    </td>
                    <td>{{$sys->getUTF8($list['nodeName'])}}</td>
                    <td>{{$sys->getUTF8($list['name'])}}</td>
                    <td>{{$sys->getUTF8($list['position'])}}</td>
                    <td>{{$sys->getUTF8($list->mapping['superivisor']['nodeName'] . '_' . $list->mapping['superivisor']['name'])}}</td>
                    <td>{{$sys->getUTF8($list->mapping['primaryDelegate']['nodeName'] . '_' . $list->mapping['primaryDelegate']['name'])}}</td>
                    <td>{{$sys->getUTF8($list->mapping['secondaryDelegate']['nodeName'] . '_' . $list->mapping['secondaryDelegate']['name'])}}</td>
                    <td></td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="text-center">
        {{$StaffList->links()}}
    </div>
    @include('Staff.EditStaff')
@endsection