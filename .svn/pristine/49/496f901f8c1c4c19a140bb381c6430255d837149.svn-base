<?php
namespace App\Service;

use App\Http\Controllers\Common;
use Carbon\Carbon;
use App\Repositories\mobileMessagingSystem\MobileRepositories;
use App\Repositories\ProductDevelopment\ProjectRepositories;
use App\Repositories\companyStructure\StaffRepositories;

class ProjectCheckService
{
    public $common;
    public $carbon;
    public $project;
    public $staff;
    public $mobile;
    public $serverData;

    public function __construct(
        Common $common,
        Carbon $carbon,
        ServerData $serverData,
        ProjectRepositories $project,
        StaffRepositories $staff,
        MobileRepositories $mobile
    ) {
        $this->common = $common;
        $this->carbon = $carbon;
        $this->serverData = $serverData;
        $this->project = $project;
        $this->staff = $staff;
        $this->mobile = $mobile;
    }

    public function delayProject()
    {

    }

    public function delayProduct()
    {

    }

    public function delayProcess()
    {
        $processList = $project->getDelayProcess();
        $mobile = $this->mobile;
        $server = $this->serverData;
        $jo = array();
        foreach ($processList as $list) {
            $title = "[$list->referenceNumber]$list->referenceName 已延誤";
            $content = "開始時間:$startDate,工時:$cost 天";
            $staff = $server->getUserByerpID($list->staffID);
            $url="";
            $projectID = $list->projectID;
            $productID = $list->productID;
            $processID = $list->processID;
            $result = $mobile->insertNotify($title, $content, 1, 0, '', $staff->ID, $url, $projectID, $productID, $processID);
            $log = array(
                'title' => $title,
                'content' => $content,
                'staff' => $staff->erpID,
                'time' => $this->carbon->now(),
                'success' => $result['success'],
                'msg' => $result['msg'],
            );
            array_push($jo, $log);
        }
        return $jo;
    }
}