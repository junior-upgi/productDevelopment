@extends('layouts.masterpage')
@section('project', 'active')
@section('content')
    <link rel="stylesheet" type="" href="{{url('/')}}/css/Process/ProcessList.css">
    <script src="{{url('/')}}/js/Process/ProcessList.js?x=3"></script>
    <!--breadcrumb-->
    <ol class="breadcrumb">
        <li><a href="{{url('/')}}/Project/ProjectList">開發案清單</a></li>
        <li><a href="{{url('/')}}/Product/ProductList/{{$ProductData->projectID}}">開發產品清單</a></li>
        <li class="active">產品開發流程</li>
    </ol>
    <!--product info panel-->
    @inject('system', 'App\Presenters\SystemPresenter')
    @php 
        $Deadline = strtotime($ProductData->deadline);
        Auth::user()->authorization === '1' ? $UserRole = ' disabled' : $UserRole='';
    @endphp
    <p class="bg-info">
        <span style="margin-right:20px;">產品代碼：{{$ProductData->referenceNumber}}</span>
        <span style="margin-right:20px;">產品名稱：{{$ProductData->referenceName}}</span>
        <span style="margin-right:20px;">需求數量：{{$ProductData->requiredQuantity}}</span>
        <span style="margin-right:20px;">完工期限：{{date('Y-m-d', $Deadline)}}</span>   
    </p>
    <!--tool bar-->
    <nav class="navbar navbar-default" role="navigation">
        <ul class="nav navbar-nav">
            <form action="" class="navbar-form">
                <a href="{{url('/')}}/Product/ProductList/{{$ProductData->projectID}}" class="btn btn-default">
                    <span class="glyphicon glyphicon-chevron-left"></span>
                </a>
                <button type="button" class="btn btn-primary {{$UserRole}}" onclick="AddShow()"><span class="glyphicon glyphicon-plus">新增</span></button>
                <button type="button" class="btn btn-warning {{$UserRole}}" onclick="SaveSort()"><span class="glyphicon glyphicon-floppy-save">儲存排序</button>
                @if($ProductData->execute == "0")
                    <button type="button" class="btn btn-warning {{$UserRole}}" onclick="Execute('run')"><span class="glyphicon glyphicon-play">執行開發</button>
                @else
                    <button type="button" class="btn btn-warning {{$UserRole}}" onclick="Execute('pause')"><span class="glyphicon glyphicon-pause">停止開發</span></button>
                @endif
            </form>
        </ul>
    </nav>
    <input type="hidden" id="ProductID" name="ProductID" value="{{$ProductData->ID}}">
    <input type="hidden" id="Deadline" name="Deadline" value="{{date('Y-m-d', $Deadline)}}">
    <input type="hidden" id="StartDate" name="StartDate" value="{{$ProductData->startDate}}">
    <table class="table table-bordered">
        <thead>
            <tr>
                <td width=60></td>
                <td width=40>#</td>
                <td width=60 class="text-center">類別</td>
                <td width=140>代號</td>
                <td>名稱</td>
                <td width=120>負責人</td>
                <td width=50 class="text-center">工時</td>
                <td width=110 class="text-center">工期</td>
                <td width=100 class="text-center">完成時間</td>
                <td width=50></td>
            </tr>
        </thead>
        <tbody id="tableSort">
            @php 
                $CostCount = 0;
                $i = 1;
                use Carbon\Carbon;
                $Now = strtotime(Carbon::now() . '-1 day');
            @endphp
            @foreach($ProcessList as $list)
                @php 
                    //$StartDays = $CostCount;
                    //$EndDays = ($CostCount += $list->timeCost) - 1;
                    //$StartDate = strtotime($ProductData->startDate . '+' . $StartDays . ' day');
                    //$EndDate = strtotime($ProductData->startDate . '+' . $EndDays . ' day') 
                    $StartDate = strtotime($list->processStartDate);
                    $EndDate = strtotime($list->processStartDate . '+' . ((int)$list->timeCost - 1)  . ' day');
                    $admin = 'disabled';
                    $self = 'disabled';
                    $complete = 'disabled';
                    if (Auth::user()->authorization === '99') {
                        $admin = '';
                    }
                    if (Auth::user()->erpID === $list->staffID || Auth::user()->authorization === '99') {
                        $self = '';
                    }
                    if ($list->complete == '0') {
                        $complete = '';
                    }
                @endphp
                @if($list->complete == "0")
                    <tr id="{{$list->ID}}" class="sTD">
                @elseif($list->complete == '1')
                    <tr id="{{$list->ID}}" class="sTD">
                @endif
                    <td>
                        <!--<button type="button" class="btn btn-sm btn-default" onclick="EditShow('{{$list->ID}}')">編輯</button>-->
                        @if($admin == '')
                            <div class="dropdown ">
                                <button type="button" id="SetBtn" class="btn btn-default" {{$complete}} data-toggle="dropdown" aria-haspopup="true" role="button" aria-expanded="flase">
                                    <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
                                </button>
                                <ul class="dropdown-menu" role="menu" aria-labelledby="SetBtn">
                                    <li role="presentation">
                                        <a role="menuitem" onclick="EditShow('{{$list->ID}}')" href="#{{$list->ID}}"><span class="glyphicon glyphicon-edit">編輯</span></a>
                                    </li>
                                    <li role="presentation">
                                        <a role="menuitem" onclick="SetPreparationShow('{{$ProductData->ID}}', '{{$list->ID}}')" href="#{{$list->ID}}"><span class="glyphicon glyphicon-tasks">前置流程</a>
                                    </li>
                                </ul>
                            </div>
                        @else
                            <button type="button" class="btn btn-sm btn-default" {{$complete}} {{$self}} onclick="EditShow('{{$list->ID}}')"><span class="glyphicon glyphicon-edit"></span></button>
                        @endif
                    </td>
                    <td>{{$list->sequentialIndex}}</td>
                    <td class="text-center">
                        <span>{{$list->PhaseName}}</span>
                    </td>
                    <td>
                        <span>{{$list->referenceNumber}}</span>
                    </td>
                    <td>
                        <span>{{$list->referenceName}}</span>
                        {!! $system->getIconPic($list->processImg) !!}
                    </td>
                    <td>
                        <span>{{$list->name}}</span>
                    </td>
                    <td class="text-center">
                        <span class="sCost">{{$list->timeCost}}</span>
                    </td>
                    <td>
                        @if($StartDate > $Deadline)
                            <span class="label label-danger sStart">
                                {{date('Y-m-d', $StartDate)}}
                            </span>
                        @else
                            <span class="sStart">
                                {{date('Y-m-d', $StartDate)}}
                            </span>
                        @endif
                        <span>~</span>
                        @if($EndDate > $Deadline)
                            <span class="label label-danger  sEnd">
                                {{date('Y-m-d', $EndDate)}}
                            <span>
                        @else
                            <span class="sEnd">
                                {{date('Y-m-d', $EndDate)}}
                            <span>
                        @endif 
                    </td>
                    <td class="text-center">
                        @if ($ProductData->execute != "0")
                            @if ($list->complete == "0")
                                @if($Now > $Deadline)
                                    <button type="button" class="btn btn-sm btn-danger bCom {{$self}}" onclick="Complete('{{$list->ID}}')"><span class="glyphicon glyphicon-ok"></span></button>
                                @elseif($Now > $EndDate)
                                    <button type="button" class="btn btn-sm btn-warning bCom {{$self}}" onclick="Complete('{{$list->ID}}')"><span class="glyphicon glyphicon-ok"></span></button>
                                @else
                                    <button type="button" class="btn btn-sm btn-success bCom {{$self}}" onclick="Complete('{{$list->ID}}')"><span class="glyphicon glyphicon-ok"></span></button>
                                @endif
                            @else
                                @php 
                                    $CompleteDate = strtotime($list->completeTime . '-1 day') 
                                @endphp
                                
                                @if($CompleteDate > $Deadline)
                                    <span class="label label-danger sCom">
                                        {{date('Y-m-d', strtotime($list->completeTime))}}
                                    </span>
                                @elseif($CompleteDate > $EndDate)
                                    <span class="label label-warning sCom">
                                        {{date('Y-m-d', strtotime($list->completeTime))}}
                                    </span>
                                @else
                                    <span class="label label-success sCom">
                                        {{date('Y-m-d', strtotime($list->completeTime))}}
                                    </span>
                                @endif
                            @endif
                        @endif
                    </td>
                    <td>
                        <button type="button" class="btn btn-sm btn-danger {{$complete}} {{$admin}}" onclick="DoDelete('{{$ProductData->ID}}', '{{$list->ID}}')"><span class="glyphicon glyphicon-trash"></span></button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @include('Process.AddProcess')
    @include('Process.EditProcess')
    @include('Process.SetPreparation')
    @include('Process.PicModal')
@endsection