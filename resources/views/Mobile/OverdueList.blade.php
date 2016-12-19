@extends('layouts.mobileLayout')
@section('content')
    <div class="text-center">
        <h3>開發案逾期清單</h3>
    </div>
    @if (count($overdueList) > 0)
        <div class="list-group">
            @foreach ($overdueList as $list)
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
                        {{ "完工期限: " . date('Y-m-d', strtotime($list->deadline)) }}
                    </p>
                </a>
            @endforeach
        </div>
    @else
        <div class="alert alert-info" role="alert">
            目前沒有任何逾期的開發案!!
        </div>
    @endif
@endsection