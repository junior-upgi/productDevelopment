@extends('layouts.masterpage')
@section('report', 'active')
@section('report.projectExecuteRate', 'active')
@section('content')
    <table class="table table-bordered table-striped" style="">
        <thead>
            <tr>
                <td width="80">專案代號</td>
                <td width="120">專案名稱</td>
                <td>客戶名稱</td>
                <td class="text-center" width="65">前置</td>
                <td class="text-center" width="65">設計</td>
                <td class="text-center" width="65">試樣</td>
                <td class="text-center" width="65">量產</td>
                <td class="text-center" width="65">確認</td>
                <td class="text-center" width="65">出貨</td>
                <td width="100">工期</td>
                <td width="100">交貨期限</td>
            </tr>
        </thead>
        {{--*/ 
            $Non = "--";
        /*--}}
        <tbody>
            @foreach($Project as $d)
                <tr style="vertical-align:middle;">
                    <td style="vertical-align:middle;">{{$d->projectNumber}}</td>
                    <td style="vertical-align:middle;">{{$d->projectName}}</td>
                    <td style="vertical-align:middle;">{{$d->clientName}}</td>
                    <td style="vertical-align:middle;" class="text-right">{{isset($d->phase0) ? number_format($d->phase0,1).'%' : $Non}}</td>
                    <td style="vertical-align:middle;" class="text-right">{{isset($d->phase1) ? number_format($d->phase1,1).'%' : $Non}}</td>
                    <td style="vertical-align:middle;" class="text-right">{{isset($d->phase2) ? number_format($d->phase2,1).'%' : $Non}}</td>
                    <td style="vertical-align:middle;" class="text-right">{{isset($d->phase3) ? number_format($d->phase3,1).'%' : $Non}}</td>
                    <td style="vertical-align:middle;" class="text-right">{{isset($d->phase9) ? number_format($d->phase9,1).'%' : $Non}}</td>
                    <td style="vertical-align:middle;" class="text-right">{{isset($d->phase10) ? number_format($d->phase10,1).'%' : $Non}}</td>
                    <td style="vertical-align:middle;" width="110">{{date('Y-m-d', strtotime($d->startDate))}}~
                        <br/>{{date('Y-m-d', strtotime($d->endDate))}}</td>
                    <td style="vertical-align:middle;" width="100">{{date('Y-m-d', strtotime($d->projectDeadline))}}</td>
                </tr>
            @endforeach
        </tbody>                  
    </table>
@endsection