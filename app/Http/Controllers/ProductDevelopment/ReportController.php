<?php

namespace App\Http\Controllers\ProductDevelopment;

//use Class
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Controllers\Common;
use App\Http\Controllers\ServerData;

//use DB
use DB;
use App\Models;
use App\Models\productDevelopment\VProjectReport;
use App\Models\productDevelopment\VProductReport;
use App\Models\productDevelopment\VProjectList;
use App\Models\productDevelopment\VShowProduct;

class ReportController extends Controller
{
    public function projectExecuteRate()
    {
        $Project = new VProjectReport();
        $Project = $Project
            ->orderBy('deadline')
            ->orderBy('startDate')
            ->orderBy('endDate')
            ->orderBy('projectNumber')
            ->get();
        return view('Report.ProjectReport')
            ->with('Project', $Project);
    }
    public function productExecuteRate()
    {
        $Project = new VProjectList();
        $Project = $Project
            ->where('productExecute', '>', 0)
            ->orderBy('startDate')
            ->orderBy('endDate')
            ->orderBy('referenceNumber')
            ->get();

        $ProductDetail = new VProductReport();
        $ProductDetail = $ProductDetail
            ->orderBy('deadline')
            ->orderBy('startDate')
            ->orderBy('endDate');

        return view('Report.ProductReport')
            ->with('Project', $Project)
            ->with('Detail', $ProductDetail);
    }
}