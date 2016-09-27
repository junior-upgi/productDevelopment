<?php
namespace App\Service;

use App\Http\Controllers\Common;
use App\Http\Controllers\ServerData;
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
        $productList = $this->project->getOverdueProduct();
    }

    public function delayProcess()
    {
        $processList = $this->project->getDelayProcess();
        $mobile = $this->mobile;
        $server = $this->serverData;
        $now = date('Y-m-d', strtotime($this->carbon->now()));
        $jo = array();
        foreach ($processList as $list) {
            $title = "[$list->referenceNumber]$list->referenceName 已延誤";
            $delayDays = (strtotime($now) - strtotime($list->processStartDate)) / (60*60*24);
            $content = "[$list->referenceNumber]$list->referenceName 已延誤 $delayDays 天";
            $staff = $server->getUserByerpID($list->staffID);
            $url = route('userSettingCost', ['processID' => $list->ID, 'staffID' => $list->staffID]);
            $audioFile="";
            $projectID = $list->projectID;
            $productID = $list->productID;
            $processID = $list->processID;
            $result = $mobile->insertNotify($title, $content, 1, 0, '', $staff->ID, $url, $audioFile, $projectID, $productID, $processID);
            $log = array(
                'success' => $result['success'],
                'type' => 'delayProcess',
                'time' => $this->carbon->now(),
                'broadcastID' => $result['broadcastID'],
                'projectID' => $projectID,
                'productID' => $productID,
                'processID' => $processID,
            );
            array_push($jo, $log);
        }
        return $jo;
    }
}