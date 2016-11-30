@extends('layouts.masterpage')
@section('project', 'active')
@section('content')
    <link rel="stylesheet" type="" href="{{ url('/css/Process/ProcessList.css') }}">
    <script src="{{ url('/js/Process/ProcessList.js?x=6') }}"></script>
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
        <li><a href="{{ url('/') }}/Project/ProjectList">開發案清單</a></li>
        <li><a href="{{ url('/') }}/Product/ProductList/{{ $ProductData->projectID }}">開發產品清單</a></li>
        <li class="active">產品開發流程</li>
    </ol>
    <!--product info panel-->
    @inject('system', 'App\Presenters\SystemPresenter')
    @php 
        $Deadline = strtotime($ProductData->deadline);
        $user = Auth::user();
        $user->authorization === '1' ? $UserRole = 'disabled' : $UserRole='';
    @endphp
    <p class="bg-info">
        {{ "[$ProductData->referenceNumber] [$ProductData->referenceName] [需求數量: $ProductData->requiredQuantity] [完工期限: ".date('Y-m-d', $Deadline)."]" }} 
    </p>
    <!--tool bar-->
    <div class="panel panel-default">
        <div class="panel-body">
            <a href="{{ url('/Product/ProductList').'/'.$ProductData->projectID }}" class="btn btn-default">
                <span class="glyphicon glyphicon-chevron-left"></span>
            </a>
            <button type="button" class="btn btn-primary" onclick="AddShow({{ $UserRole }})"><span class="glyphicon glyphicon-plus">新增</span></button>
            <button type="button" class="btn btn-warning" onclick="SaveSort()"><span class="glyphicon glyphicon-floppy-save">儲存排序</button>
            @if($ProductData->execute == 0)
                <button type="button" class="btn btn-warning {{ $UserRole }}" onclick="Execute('run')"><span class="glyphicon glyphicon-play">執行開發</span></button>
            @else
                <button type="button" class="btn btn-warning {{ $UserRole }}" onclick="Execute('pause')"><span class="glyphicon glyphicon-pause">停止開發</span></button>
            @endif
            <button type="button" class="btn btn-warning" onclick="Notify()"><span class="glyphicon glyphicon-phone">發送推播</button>
        </div>
    </div>
    <input type="hidden" id="ProductID" name="ProductID" value="{{ $ProductData->ID }}">
    <input type="hidden" id="Deadline" name="Deadline" value="{{ date('Y-m-d', $Deadline) }}">
    <input type="hidden" id="StartDate" name="StartDate" value="{{ $ProductData->startDate }}">
    <div class="table-responsive">
        <table class="table table-bordered table-condensed">
            <thead>
                <tr>
                    <td width=50></td>
                    <td width=50></td>
                    <td width=40>#</td>
                    <td width=60 class="text-center">類別</td>
                    <td width=140>代號</td>
                    <td>名稱</td>
                    <td width=120>負責人</td>
                    <td width=50 class="text-center">工時</td>
                    <td width=110 class="text-center">工期</td>
                    <td width=100 class="text-center">完成時間</td>
                    <td width=150>備註</td>
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
                        $StartDate = strtotime($list->processStartDate);
                        $EndDate = strtotime($list->processStartDate . '+' . ((int)$list->timeCost - 1)  . ' day');
                        $admin = 'disabled';
                        $self = 'disabled';
                        $complete = 'disabled';
                        if ($user->authorization == '99') {
                            $admin = '';
                        }
                        if ($user->erpID == $list->staffID || $user->authorization == '99') {
                            $self = '';
                        }
                        if ($list->complete == '0') {
                            $complete = '';
                        }
                    @endphp
                    @if($list->complete == "0")
                        <tr id="{{ $list->ID }}" class="sTD">
                    @elseif($list->complete == '1')
                        <tr id="{{ $list->ID }}" class="sTD">
                    @endif
                        <td>
                            <button type="button" class="btn btn-default {{ $complete }} {{ $self }}" data-toggle="tooltip" data-placement="top" title="編輯" onclick="EditShow('{{ $list->ID }}')">
                                <span class="glyphicon glyphicon-edit"></span>
                            </button>
                        </td>
                        <td>
                            <button type="button" class="btn btn-default {{ $complete }} {{ $self }}" data-toggle="tooltip" data-placement="top" title="前置流程" onclick="SetPreparationShow('{{ $ProductData->ID }}', '{{ $list->ID }}')"><span class="glyphicon glyphicon-tasks"></button>
                        </td>
                        <td>{{ $list->sequentialIndex }}</td>
                        <td class="text-center">
                            <span>{{ $list->PhaseName }}</span>
                        </td>
                        <td>
                            <span>{{ $list->referenceNumber }}</span>
                        </td>
                        <td>
                            <span>{{ $list->referenceName }}</span>
                            {!! $system->getIconPic($list->processImg) !!}
                        </td>
                        <td>
                            <span>{{ $list->name }}</span>
                        </td>
                        <td class="text-center">
                            <span class="sCost">{{ $list->timeCost }}</span>
                        </td>
                        <td>
                            @if($StartDate > $Deadline)
                                <span class="label label-danger sStart">
                                    {{ date('Y-m-d', $StartDate) }}
                                </span>
                            @else
                                <span class="sStart">
                                    {{ date('Y-m-d', $StartDate) }}
                                </span>
                            @endif
                            <span>~</span>
                            @if($EndDate > $Deadline)
                                <span class="label label-danger  sEnd">
                                    {{ date('Y-m-d', $EndDate) }}
                                <span>
                            @else
                                <span class="sEnd">
                                    {{ date('Y-m-d', $EndDate) }}
                                <span>
                            @endif 
                        </td>
                        <td class="text-center">
                            @if ($ProductData->execute != "0")
                                @if($list->complete == "0")
                                    @if($Now > $Deadline)
                                        <button type="button" data-toggle="tooltip" data-placement="top" title="點擊完成工序" class="btn btn-sm btn-danger bCom {{ $self }}" onclick="Complete('{{ $list->ID }}')"><span class="glyphicon glyphicon-ok"></span></button>
                                    @elseif($Now > $EndDate)
                                        <button type="button" data-toggle="tooltip" data-placement="top" title="點擊完成工序" class="btn btn-sm btn-warning bCom {{ $self }}" onclick="Complete('{{ $list->ID }}')"><span class="glyphicon glyphicon-ok"></span></button>
                                    @else
                                        <button type="button" data-toggle="tooltip" data-placement="top" title="點擊完成工序" class="btn btn-sm btn-success bCom {{ $self }}" onclick="Complete('{{ $list->ID }}')"><span class="glyphicon glyphicon-ok"></span></button>
                                    @endif
                                @else
                                    @php 
                                        $CompleteDate = strtotime($list->completeTime . '-1 day') 
                                    @endphp
                                    
                                    @if($CompleteDate > $Deadline)
                                        <span class="label label-danger sCom">
                                            {{ date('Y-m-d', strtotime($list->completeTime)) }}
                                        </span>
                                    @elseif($CompleteDate > $EndDate)
                                        <span class="label label-warning sCom">
                                            {{ date('Y-m-d', strtotime($list->completeTime)) }}
                                        </span>
                                    @else
                                        <span class="label label-success sCom">
                                            {{ date('Y-m-d', strtotime($list->completeTime)) }}
                                        </span>
                                    @endif
                                @endif
                            @endif
                        </td>
                        <td>{!! $system->replaceBR($list->note) !!}</td>
                        <td>
                            <button type="button" data-toggle="tooltip" data-placement="top" title="刪除" class="btn btn-sm btn-danger {{ $complete }} {{ $self }}" onclick="DoDelete('{{ $ProductData->ID }}', '{{ $list->ID }}')"><span class="glyphicon glyphicon-trash"></span></button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @include('Process.AddProcess')
    @include('Process.EditProcess')
    @include('Process.SetPreparation')
    @include('Process.PicModal')
    @include('System.Service.Notify')
@endsection