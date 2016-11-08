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
use App\Service\ProjectCheckService;

//use Repositories
use App\Repositories\ProductDevelopment\ProjectRepositories;
use App\Repositories\upgiSystem\UpgiSystemRepository;

class MobileController extends Controller
{
    public $project;
    public $common;
    public $server;
    public $upgi;
    public $check;

    public function __construct(
        Common $common,
        ServerData $server,
        ProjectRepositories $project,
        UpgiSystemRepository $upgi,
        ProjectCheckService $check
    ) {
        $this->common = $common;
        $this->server = $server;
        $this->project = $project;
        $this->upgi = $upgi;
        $this->check = $check;
    }
    public function userSettingCost($processID, $staffID)
    {
        $processData = $this->project->getProcessByID($processID);
        if (isset($processData)) {
            return view('Mobile.SettingCost')
                ->with('processData', $processData);
        } else {
            $title = '資料已刪除';
            $content = '工序資訊已刪除!';
            return view('errors.mobileError')
                ->with('title', $title)
                ->with('content', $content);
        }
    }
    public function auserSaveCost(Request $request)
    {
        return 'true';
    }
    public function userSaveCost(Request $request)
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
        if (isset($processData)) {
            $productData = $this->project->getProductByID($processData->productID);
        } else {
            $title = '資料已刪除';
            $content = '工序資訊已刪除!';
            return view('errors.mobileError')
                ->with('title', $title)
                ->with('content', $content);
        }
        
        return view('Mobile.OverdueInfo')
            ->with('processData', $processData)
            ->with('productData', $productData);
    }

    public function overdueList($id)
    {
        $where = [];
        $params = ['key' => 'groupName', 'value' => "SendOverdue"];
        $user = ['key' => 'ID', 'value' => $id];
        array_push($where, $params);
        array_push($where, $user);
        $groupUser = $this->upgi->getList('vUserGroupList', $where)->get();
        if (isset($groupUser)) {
            $overdueList = $this->project->getDelayProduct();
            return view('Mobile.OverdueList')
                ->with('overdueList', $overdueList);
        } else {
            $title = '';
            $content = '您目前沒有權限瀏覽此頁面!';
            return view('errors.mobileError')
                ->with('title', $title)
                ->with('content', $content);
        }
    }
}