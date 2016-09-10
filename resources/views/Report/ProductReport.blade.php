@extends('layouts.masterpage')
@section('report', 'active')
@section('report.productExecuteRate', 'active')
@section('content')
    <table class="table table-bordered" style="">
        @foreach($Project as $list)
            @php 
                $DetailList = $Detail
                    ->get();
                $Non = "--";
            @endphp
            @if($DetailList->count() > 0)
                <tr style="font-weight:bolder;">
                    <td colspan="10">
                        專案：[{{$list->referenceNumber}}]{{$list->referenceName}}<br/>
                        客戶名稱：{{$list->clientName}}
                    </td>
                </tr>
                <tr style="background-color:#eee;">
                    <td>產品代號</td>
                    <td>產品名稱</td>
                    <td class="text-center">前置</td>
                    <td class="text-center">設計</td>
                    <td class="text-center">試樣</td>
                    <td class="text-center">量產</td>
                    <td class="text-center">確認</td>
                    <td class="text-center">出貨</td>
                    <td>工期</td>
                    <td>完工期限</td>
                </tr>
                @foreach($DetailList as $d)
                    @if($d->ID === $list->ID)
                        <tr style="vertical-align:middle;">
                            <td style="vertical-align:middle;">{{$d->productNumber}}</td>
                            <td style="vertical-align:middle;">{{$d->productName}}</td>
                            <td style="vertical-align:middle;" class="text-right" width="65">{{isset($d->phase0) ? number_format($d->phase0,1).'%' : $Non}}</td>
                            <td style="vertical-align:middle;" class="text-right" width="65">{{isset($d->phase1) ? number_format($d->phase1,1).'%' : $Non}}</td>
                            <td style="vertical-align:middle;" class="text-right" width="65">{{isset($d->phase2) ? number_format($d->phase2,1).'%' : $Non}}</td>
                            <td style="vertical-align:middle;" class="text-right" width="65">{{isset($d->phase3) ? number_format($d->phase3,1).'%' : $Non}}</td>
                            <td style="vertical-align:middle;" class="text-right" width="65">{{isset($d->phase9) ? number_format($d->phase9,1).'%' : $Non}}</td>
                            <td style="vertical-align:middle;" class="text-right" width="65">{{isset($d->phase10) ? number_format($d->phase10,1).'%' : $Non}}</td>
                            <td style="vertical-align:middle;" width="110">{{date('Y-m-d', strtotime($d->startDate))}}~
                                <br/>{{date('Y-m-d', strtotime($d->endDate))}}</td>
                            <td style="vertical-align:middle;" width="100">{{date('Y-m-d', strtotime($d->deadline))}}</td>
                        </tr>
                    @endif
                @endforeach
            @endif
            <tr style="height:3px;"></tr>
        @endforeach    
    </table>
@endsection