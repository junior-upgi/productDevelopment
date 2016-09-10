@extends('layouts.masterpage')
@section('content')
    <script src="{{url('/')}}/js/Project/ProjectList.js?x=3"></script>
    <link rel="stylesheet" type="" href="{{url('/')}}/css/Project/ShowProject.css">
    <!--專案進度顯示-->
    @foreach($Project as $list)
        <div class="panel panel-default">
            <div class="panel-heading" role="tab">
                <h4 class="panel-title">
                    <a href="#{{$list->projectID}}" data-toggle="collapse" aria-expanded="false" aria-controls="collapseOne">
                        {{$list->projectNumber}}-{{$list->projectName}}
                        [總工時：{{$list->projectTotalCost}}]
                        <div style="float:right;">進度：{{number_format($list->projectCompletePercent,1)}}%</div>
                    </a>
                </h4>
            </div>
            <div class="progress" style="margin-bottom:0px;">
                <div class="progress-bar" role="progressbar" aria-valuenow="{{$list->projectCompletePercent}}"
                aria-valuemin="0" aria-valuemax="100" style="width:{{number_format($list->projectCompletePercent,1)}}%;" >
                    {{number_format($list->projectCompletePercent,1)}}%
                </div>
            </div>
            <div class="panel-collapse collapse" role="tabpanel" id="{{$list->projectID}}">
                <div class="panel-body">
                    @php
                        $ProductList = $Product
                            ->where('ProjectID', $list->projectID)
                            ->orderBy('productStatus')
                            ->orderBy('priorityLevel')
                            ->orderBy('startDate','desc')
                            ->get();  
                    @endphp
                    @foreach($ProductList as $c)
                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab">
                                <h4 class="panel-title">
                                    <a href="#{{$c->productID}}" data-toggle="collapse" aria-expanded="false" aria-controls="collapseOne">
                                        {{$c->productNumber}}-{{$c->productName}}
                                        [總工時：{{$c->productTotalCost}}]
                                        <div style="float:right;">進度：{{number_format($c->productCompletePercent,1)}}%</div>
                                    </a>
                                </h4>
                            </div>
                            <div class="progress" style="margin-bottom:0px;">
                                <div class="progress-bar" role="progressbar" aria-valuenow="{{$c->productCompletePercent}}"
                                aria-valuemin="0" aria-valuemax="100" style="width:{{number_format($c->productCompletePercent,1)}}%;" >
                                    {{number_format($c->productCompletePercent,1)}}%
                                </div>
                            </div>
                            <div class="panel-collapse collapse" role="tabpanel" id="{{$c->productID}}">
                                <div class="panel-body">
                                    @php
                                        $ProcessList = $Process
                                            ->where('ProductID', $c->productID)
                                            ->orderBy('PhaseID')
                                            ->get();  
                                    @endphp
                                    @foreach($ProcessList as $p)
                                        <div class="panel panel-default">
                                            <div class="panel-heading" role="tab">
                                                <h4 class="panel-title">
                                                    <a href="#{{$p->productID . $p->phaseID}}" data-toggle="collapse" aria-expanded="false" aria-controls="collapseOne">
                                                        {{$p->phaseName}}
                                                        [總工時：{{$p->phaseTotalCost}}]
                                                        <div style="float:right;">進度：{{number_format($p->phaseCompletePercent,1)}}%</div>
                                                    </a>
                                                </h4>
                                            </div>
                                            <div class="progress" style="margin-bottom:0px;">
                                                <div class="progress-bar" role="progressbar" aria-valuenow="{{$p->phaseCompletePercent}}"
                                                aria-valuemin="0" aria-valuemax="100" style="width:{{number_format($p->phaseCompletePercent,1)}}%;" >
                                                    {{number_format($p->phaseCompletePercent,1)}}%
                                                </div>
                                            </div>
                                            <div class="panel-collapse collapse" role="tabpanel" id="{{$p->productID . $p->phaseID}}">
                                                <div class="panel-body">
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endforeach
@endsection