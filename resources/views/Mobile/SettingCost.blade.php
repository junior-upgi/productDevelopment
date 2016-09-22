@extends('layouts.mobileLayout')
@section('content')
	<script src="{{url('/')}}/js/Mobile/SettingCost.js?x=2"></script>
	@inject('mobile', 'App\Presenters\MobilePresenter')
	@php 
		$pc = $processData
	@endphp
	@if($processData)
		<form class="form-horizontal" role="form" method="POST" style="margin-top:15px;">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h4>{{ "[$pc->projectNumber] $pc->projectName" }}</h4>
				</div>
				<input type="hidden" id="processID" name="processID" value="{{ $pc->ID }}">
				<div class="panel-body">
					<div class="form-group from-group-sm">
						<label for="" class="control-label col-xs-3">產品代號</label>
						<div class="col-xs-9">
							<p class="">{{ $pc->productNumber }}</p>
						</div>
					</div>
					<div class="form-group from-group-sm">
						<label for="" class="control-label col-xs-3">產品名稱</label>
						<div class="col-xs-9">
							<p class="">{{ $pc->productName }}</p>
						</div>
					</div>
					<div class="form-group from-group-sm">
						<label for="" class="control-label col-xs-3">優先等級</label>
						<div class="col-xs-9">
							<p class="">{{ $mobile->para('priorityLevel', $pc->priorityLevel) }}</p>
						</div>
					</div>
					<div class="form-group from-group-sm">
						<label for="" class="control-label col-xs-3">程序代號</label>
						<div class="col-xs-9">
							<p class="">{{ $pc->referenceNumber }}</p>
						</div>
					</div>
					<div class="form-group from-group-sm">
						<label for="" class="control-label col-xs-3">程序名稱</label>
						<div class="col-xs-9">
							<p class="">{{ $pc->referenceName }}</p>
						</div>
					</div>
					<div class="form-group from-group-sm">
						<label for="" class="control-label col-xs-3">程序類別</label>
						<div class="col-xs-9">
							<p class="">{{ $pc->PhaseName }}</p>
						</div>
					</div>
					<div class="form-group from-group-sm">
						<label for="" class="control-label col-xs-3">開始時間</label>
						<div class="col-xs-9">
							<p class="">{{ $mobile->getDate($pc->processStartDate) }}</p>
						</div>
					</div>
					<div class="form-group from-group-sm">
						<label for="" class="control-label col-xs-3">工時：</label>
						<div class="col-xs-4">
							<input type="number" class="form-control input-sm text-right"
								min="{{ $pc->timeCost }}" id="timeCost" name="timeCost" value="{{ $pc->timeCost }}">
						</div>
						<p class="form-control">天</p>
					</div>
				</div>
				<div class="panel-footer">
					<button type="submit" class="btn btn-primary btn-sm" data-loading-text="資料送出中..." autocomplete="off" 
						id="BtnAdd" name="BtnAdd" onclick="DoInsert()">儲存</button>
				</div>
			</div>
		</form>
	@else
		{!! $mobile->error() !!}
	@endif
@endsection