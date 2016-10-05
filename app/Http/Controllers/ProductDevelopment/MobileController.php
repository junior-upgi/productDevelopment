<?php

namespace App\Http\Controllers\ProductDevelopment;

//use Class
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use App\Models\productDevelopment\ProjectProcess;
use App\Models\productDevelopment\ProcessTree;

//use Custom Class
use App\Http\Controllers\Common;
use App\Http\Controllers\ServerData;

//use Service
use App\Service\NotificationService;

//use Repositories
use App\Repositories\ProductDevelopment\ProjectRepositories;

class MobileController extends Controller
{
    public $project;
    public $common;
    public $server;

    public function __construct(
        Common $common,
        ServerData $server,
        ProjectRepositories $project
    ) {
        $this->common = $common;
        $this->server = $server;
        $this->project = $project;
    }
    public function userSettingCost($processID, $staffID)
    {
        $processData = $this->project->getProcessByID($processID);
        return view('Mobile.SettingCost')
            ->with('processData', $processData);
    }
    public function userSaveCost(Request $request)
    {
        return 'true';
    }
    public function auserSaveCost(Request $request)
    {
        $processID = $request->input('ProcessID');
        $oldTimeCost = $this->project->getProcessByID($processID)->timeCost;
        $timeCost = $request->input('TimeCost');
        if ($oldTimeCost != $timeCost) {
            $params = array(
                'timeCost' => $timeCost
            );
            $update = $this->project->updateProcess($processID, $params);
            if ($update['success']) {
                return $update;
            } else {
                return $update;
            }
        } else {
            //沒有變更工時，不改變後續工序時間
            return array(
                'success' => true,
                'msg' => '沒有變更資料。',
            );
        }
    }
    public function overdueInfo($processID)
    {
        $processData = $this->project->getProcessByID($processID);
        $productData = $this->project->getProductByID($processData->productID);
        return view('Mobile.OverdueInfo')
            ->with('processData', $processData)
            ->with('productData', $productData);
    }
}