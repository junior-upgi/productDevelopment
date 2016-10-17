@extends('layouts.fullScreen')
@section('report', 'active')
@section('report.meetingReport', 'active')
@section('content')
    <h1 class="text-center">開發專案執行現況</h1>
    <table class="table table-bordered table-striped" style="width:100%;">
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
    </table>
@endsection