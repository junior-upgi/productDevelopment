@extends('layouts.masterpage')
@section('personal', 'active')
@section('content')
    <link rel="stylesheet" type="" href="{{url('/')}}/css/Process/ProcessList.css">
    <script src="{{url('/')}}/js/Process/MyProcess.js?x=2"></script>
    @inject('system', 'App\Presenters\SystemPresenter')
    <table class="table table-bordered">
        <thead>
            <tr>
                <td width=60></td>
                <td width=60 class="text-center">類別</td>
                <td width=140>代號</td>
                <td>名稱</td>
                <td width=50 class="text-center">工時</td>
                <td width=200 class="text-center">工期</td>
                <td width=100 class="text-center"></td>
            </tr>
        </thead>
        <tbody id="tableSort">
            @php 
                $CostCount = 0;
                $i = 1;
                use Carbon\Carbon;
                $Now = strtotime(Carbon::now() . '-1 day');
            @endphp
            @foreach($process as $list)
                @php  
                    $Deadline = strtotime($list->deadline);
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
                <tr id="{{$list->ID}}" class="sTD">
                    <td>
                        <button type="button" data-toggle="tooltip" data-placement="top" title="編輯" class="btn btn-sm btn-default" onclick="EditShow('{{$list->ID}}')"><span class="glyphicon glyphicon-edit"></span></button>
                    </td>
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
                        <button type="button" class="btn btn-sm btn-default" onclick="Complete('{{$list->ID}}')">
                            點擊完成工序
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @include('Process.PicModal')
    @include('Process.EditMyProcess')
@endsection