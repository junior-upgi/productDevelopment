@extends('layouts.masterpage')
@section('project', 'active')
@section('content')
    <script src="{{url('/')}}/js/Project/ProjectList.js?x=3"></script>
    <!--breadcrumb-->
    <ol class="breadcrumb">
        <li class="active">開發案清單</li>
    </ol>
    <!--tool bar-->
    @php
        Auth::user()->authorization === '1' ? $UserRole = ' disabled' : $UserRole='';
    @endphp
    <nav class="navbar navbar-default" role="navigation">
        <ul class="nav navbar-nav">
            <form action="" class="navbar-form">
                <a href="{{url('/')}}/Project/AddProject" class="btn btn-primary {{$UserRole}}">新增</a>
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
                <td width="80">負責人</td>
                <td width="100" class="text-center">工期</td>
                <td width="100" class="text-center">交貨期限</td>
                <!--<td width="100" class="text-center">完成時間</td>-->
                <td class="80"></td>
            </tr>
        </thead>
        <tbody>
            @foreach($ProjectList as $list)
                <tr>
                    <td class="text-center"><a href="{{url('/')}}/Project/EditProject/{{$list->ID}}" class="btn btn-sm btn-default {{$UserRole}}">編輯</a></td>
                    <td class="text-center"><a href="{{url('/')}}/Product/ProductList/{{$list->ID}}" class="btn btn-sm btn-info">產品</a></td>
                    <td>{{$list->referenceNumber}}</td>
                    <td>{{$list->referenceName}}</td>
                    <td>{{$list->clientName}}</td>
                    <td>{{$list->salesName}}</td>
                    <td class="text-center">
                        @if(isset($list->startDate))
                            {{date('Y-m-d', strtotime($list->startDate))}}
                            
                            @if(isset($list->endDate))
                                ~{{date('Y-m-d', strtotime($list->endDate))}}
                            @endif
                        @endif
                    </td>
                    <td>
                        {{date('Y-m-d', strtotime($list->projectDeadline))}}
                    </td>
                    <!-- 保留完成時間
                    <td class="text-center">
                        @if(isset($list->maxCompleteTime))
                            {{date('Y-m-d', strtotime($list->maxCompleteTime))}}
                        @else
                            @if($list->completeStatus === '0')
                                @if($list->projectStatus === 2)
                                    <span class="label label-danger">進行中</span>
                                @elseif($list->projectStatus === 1)
                                    <span class="label label-warning">進行中</span>
                                @else
                                    <span>進行中</span>
                                @endif
                            @elseif($list->completeStatus === '1')
                                @if($list->projectStatus === 2)
                                    <span class="label label-danger">尚未開始</span>
                                @elseif($list->projectStatus === 1)
                                    <span class="label label-warning">尚未開始</span>
                                @else
                                    <span>尚未開始</span>
                                @endif
                            @endif
                        @endif
                    </td>
                    -->
                    <td class="text-center">
                        <input type="button" class="btn btn-sm btn-danger {{$UserRole}}" value="刪除" onclick="DoDelete('{{$list->ID}}')">
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="text-center">
        {{$ProjectList->links()}}
    </div>
@endsection