<?php

namespace App\Http\Controllers\ProductDevelopment;

//use Class
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

//use Custom Class
use App\Http\Controllers\Common;
use App\Http\Controllers\ServerData;

//use Repositories
use App\Repositories\ProductDevelopment\ProjectRepositories;

class ReportController extends Controller
{
    public $common;
    public $serverData;
    public $projectRepositories;

    public function __construct(
        Common $common,
        ServerData $serverData,
        ProjectRepositories $projectRepositories
    ) {
        $this->common = $common;
        $this->serverData = $serverData;
        $this->projectRepositories = $projectRepositories;
    }

    public function projectExecuteRate()
    {
        return view('Report.ProjectReport')
            ->with('Project', $this->projectRepositories->getProjectReport());
    }
    public function productExecuteRate()
    {
        return view('Report.ProductReport')
            ->with('Project', $this->projectRepositories->getProjectExecuteList())
            ->with('Detail', $this->projectRepositories->getProductReport());
    }
}