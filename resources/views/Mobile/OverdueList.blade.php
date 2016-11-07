@extends('layouts.mobileLayout')
@section('content')
    <h3>開發案逾期清單</h3>
    <div class="list-group">
        @foreach($overdueList as $list)
            @php
                if (strtotime($list->nowEndDate > $list->endDate)) {
                    $class = 'list-group-item list-group-item-danger';
                } else {
                    $class = 'list-group-item list-group-item-warning';
                }
            @endphp
            <a class="{{ $class }}">
                <h4>{{ "[$list->projectNumber]$list->projectName" }}</h4>
                <p>
                    {{ "產品: [$list->referenceNumber]$list->referenceName" }}<br/>
                    {{ "顧客: $list->clientName" }}<br/>
                    {{ "業務: $list->salesName" }}<br/>
                    {{ "預計完成日: " . date('Y-m-d', strtotime($list->endDate)) }}
                </p>
            </a>
        @endforeach
    </div>
@endsection