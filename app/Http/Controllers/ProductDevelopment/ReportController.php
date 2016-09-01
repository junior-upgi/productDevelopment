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
use App\Models\productDevelopment\VProjectList;
use App\Models\productDevelopment\VShowProduct;

class ReportController extends Controller
{
    public function projectExecuteRate(){
        $Project = new VProjectList();
        $Project = $Project
            ->where('productExecute', '>', 0)
            ->get();

        $ProjectDetail = new VProjectReport();
        //$ProjectDetail = $ProjectDetail->get();

        $Product = new VShowProduct();

        return view('Report.ProjectReport')
            ->with('Project', $Project)
            ->with('Detail', $ProjectDetail)
            ->with('Product', $Product);
    }
}