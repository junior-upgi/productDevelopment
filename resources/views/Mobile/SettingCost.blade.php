@extends('layouts.mobileLayout')
@section('content')
	<script src="{{url('/')}}/js/Mobile/SettingCost.js?x=2"></script>
	@inject('mobile', 'App\Presenters\MobilePresenter')
	@php 
		$pc = $processData
	@endphp
	@if($processData)
	<div class="row">
		<form id="SetCostForm" class="form-horizontal col-xs-12 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3" action role="form" method="POST">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h4>{{ "[$pc->projectNumber] $pc->projectName" }}</h4>
				</div>
				<input type="hidden" name="_token" value="{{{ csrf_token() }}}">
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
						<label for="" class="control-label col-xs-4">開始時間</label>
						<div class="col-xs-8">
							<p class="">{{ $mobile->getDate($pc->processStartDate) }}</p>
						</div>
					</div>
					<div class="form-group from-group-sm">
						<label for="" class="control-label col-xs-4">工時</label>
						<div class="col-xs-4">
							<input type="number" class="form-control input-sm text-right"
								min="{{ $pc->timeCost }}" id="TimeCost" name="TimeCost" value="{{ $pc->timeCost }}">
						</div>
						<span>天</span>
					</div>
				</div>
				<div class="panel-footer">
					<button type="submit" class="btn btn-primary btn-sm" data-loading-text="資料送出中..." autocomplete="off" 
						id="BtnSave" name="BtnSave" onclick="DoSave()">儲存</button>
				</div>
			</div>
		</form>
	</div>
		
	@else
		{!! $mobile->error() !!}
	@endif
@endsection