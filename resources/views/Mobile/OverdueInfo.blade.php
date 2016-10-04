@extends('layouts.mobileLayout')
@section('content')
	@inject('mobile', 'App\Presenters\MobilePresenter')
	@php 
		$pc = $processData;
        $pd = $productData;
	@endphp
	@if($processData)
        <div class="panel panel-danger">
            <div class="panel-heading">
                <h4>{{ "[$pc->projectNumber] $pc->projectName" }}</h4>
            </div>
            <input type="hidden" id="ProcessID" name="ProcessID" value="{{ $pc->ID }}">
            <div class="panel-body">
                <div class="form-group from-group-sm">
                    <label for="" class="control-label col-xs-4">產品代號</label>
                    <div class="col-xs-8">
                        <p class="">{{ $pc->productNumber }}</p>
                    </div>
                </div>
                <div class="form-group from-group-sm">
                    <label for="" class="control-label col-xs-4">產品名稱</label>
                    <div class="col-xs-8">
                        <p class="">{{ $pc->productName }}</p>
                    </div>
                </div>
                <div class="form-group from-group-sm">
                    <label for="" class="control-label col-xs-4">優先等級</label>
                    <div class="col-xs-8">
                        <p class="">{{ $mobile->para('priorityLevel', $pc->priorityLevel) }}</p>
                    </div>
                </div>
                <div class="form-group from-group-sm">
                    <label for="" class="control-label col-xs-4">預計完成時間</label>
                    <div class="col-xs-8">
                        <p class="">{{ $mobile->getDate($pc->deadline) }}</p>
                    </div>
                </div>
                <div class="form-group from-group-sm">
                    <label for="" class="control-label col-xs-4">預計完成時間</label>
                    <div class="col-xs-8">
                        <p class="">{{ $mobile->getDate($pd->endDate) }}</p>
                    </div>
                </div>
                <div class="form-group from-group-sm">
                    <label for="" class="control-label col-xs-4">交貨期限</label>
                    <div class="col-xs-8">
                        <p class="">{{ $mobile->getDate($pd->deadline) }}</p>
                    </div>
                </div>
                <h4>延誤程序</h4>
                <div class="form-group from-group-sm">
                    <label for="" class="control-label col-xs-4">程序代號</label>
                    <div class="col-xs-8">
                        <p class="">{{ $pc->referenceNumber }}</p>
                    </div>
                </div>
                <div class="form-group from-group-sm">
                    <label for="" class="control-label col-xs-4">程序名稱</label>
                    <div class="col-xs-8">
                        <p class="">{{ $pc->referenceName }}</p>
                    </div>
                </div>
                <div class="form-group from-group-sm">
                    <label for="" class="control-label col-xs-4">程序類別</label>
                    <div class="col-xs-8">
                        <p class="">{{ $pc->PhaseName }}</p>
                    </div>
                </div>
                <div class="form-group from-group-sm">
                    <label for="" class="control-label col-xs-4">負責人</label>
                    <div class="col-xs-8">
                        <p class="">{{ $pc->name }}</p>
                    </div>
                </div>
                <div class="form-group from-group-sm">
                    <label for="" class="control-label col-xs-4">開始時間</label>
                    <div class="col-xs-8">
                        <p class="">{{ $mobile->getDate($pc->processStartDate) }}</p>
                    </div>
                </div>
                <div class="form-group from-group-sm">
                    <label for="" class="control-label col-xs-4">工時：</label>
                    <div class="col-xs-4">
                        <input type="number" class="form-control input-sm text-right"
                            min="{{ $pc->timeCost }}" id="TimeCost" name="TimeCost" value="{{ $pc->timeCost }}">
                    </div>
                    <span>天</span>
                </div>
            </div>
        </div>
	@else
		{!! $mobile->error() !!}
	@endif
@endsection