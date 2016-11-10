@extends('layouts.fullScreen')
@section('report', 'active')
@section('report.meetingReport', 'active')
@section('content')
@inject('mobile', 'App\Presenters\MobilePresenter')
@inject('system', 'App\Presenters\SystemPresenter')
<script src="{{url('/')}}/js/Report/MeetingReport.js?x=3"></script>
    <h1 class="text-center">產品開發專案執行現況</h1>
    <table class="table table-bordered">
        <thead>
            <tr>
                <td width="80">專案代號</td>
                <td width="120">專案名稱</td>
                <td>客戶名稱</td>
                <td>業務</td>
                <td>產品代號</td>
                <td>產品名稱</td>
                <td>交貨期限</td>
                <td>開發執行時間</td>
            </tr>
        </thead>        
        <tbody>
            @php
                $now = Carbon\Carbon::now();
                $today = $mobile->getDate($now);
            @endphp
            @foreach($product as $list)
                @php
                    $trType = 'default';
                    if (strtotime($list->deadline) < strtotime($today)) {
                        $trType = 'danger';
                    } elseif (strtotime($list->nowEndDate) < strtotime($today)) {
                        $trType = 'warring';
                    }
                @endphp
                <tr class="{{ $trType }}">
                    <td>{{ $list->projectNumber }}</td>
                    <td>
                        <a href="#{{ $list->ID }}" data-toggle="modal" data-target="#{{ $list->ID }}" >
                            {{ $list->projectName }}
                        </a>
                    </td>
                    <td>{{ str_limit($list->clientName, 30) }}</td>
                    <td>{{ $list->salesName }}</td>
                    <td>{{ $list->referenceNumber }}</td>
                    <td>
                        {{ $list->referenceName }}
                        {!! $system->getProductPic($list->ID) !!}
                    </td>
                    <td>{{ $mobile->getDate($list->deadline) }}</td>
                    <td>{{ $mobile->getDate($list->startDate) . ' ~ ' . $mobile->getDate($list->endDate) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @foreach($product as $list)
        <div id="{{ $list->ID }}" class="modal fade bs-example-modal-lg" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg" aria-hidden="true">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">目前進行的程序</h4>
                    </div>
                    <div class="modal-body">
                        <table class="table table-bordered table-condensed panel-body">
                            <thead>
                                <tr>
                                    <td width="45">類別</td>
                                    <td width="140">程序代號</td>
                                    <td width="200">程序名稱</td>
                                    <td width="80">負責人</td>
                                    <td width="100">工期</td>
                                    <td>備註</td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($process as $d)
                                    @if($d->productID == $list->ID)
                                        <tr>
                                            <td>{{ $d->PhaseName }}</td>
                                            <td>{{ $d->referenceNumber }}</td>
                                            <td>{{ $d->referenceName }}</td>
                                            <td>{{ $d->name }}</td>
                                            <td>{{ $mobile->getDate($d->processStartDate) . ' ~ ' }}<br/>
                                                {{ $mobile->getDate($d->processEndDate) }}</td>
                                            <td>{!! nl2br($d->note) !!}</td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    @include('Process.PicModal')
@endsection