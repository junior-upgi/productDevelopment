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

    public function everyDay()
    {
        /*
        1、更新每日延誤程序之工期延期至今日
        2、通知個人工期已延誤至今日
        3、如果最後完成時間已逾期，則通知業務
        */

        //取得現階段執行且延誤之程序 getDelayProcess()
        
        $processList = $this->project->getDelayProcess();
        
        $jo = array();

        foreach ($processList as $list) {
            //通知負責人修正程序工期延至今日
            $extension = $this->notifyExtension($list, $jo);
            $jo = $extension;
            //檢查是否最後完成時間己逾期
            $overdue = $this->notifyOverdue($list, $jo);
            $jo = $overdue;
        }
        return $jo;
    }
    public function notifyExtension($list, $jo)
    {
        $mobile = $this->mobile;
        $server = $this->serverData;
        $startDate = date('Y-m-d', strtotime($list->processStartDate));
        $now = date('Y-m-d', strtotime($this->carbon->now()));
        $newCost = ((strtotime($now) - strtotime($startDate)) / (60*60*24)) + 1;
        $params = array(
            'timeCost' => $newCost,
        );
        $setCost = $this->project->updateProcess($list->ID, $params);
        if (!$setCost['success']) {
            array_push($jo, $this->setLog(false, 'up cost error', $setCost['msg'], '', $list->projectID, $list->productID, $list->ID));
            return $jo;
        }
        //通知個人工期建誤
        $title = "[$list->referenceNumber]$list->referenceName 已延誤";
        $content = "[$list->referenceNumber]$list->referenceName 已延誤，完成時間延至今日";
        $staff = $server->getUserByerpID($list->staffID);
        //$url = route('userSettingCost', ['processID' => $list->ID, 'staffID' => $list->staffID]);
        $url = "http://upgi.ddns.net/productDevelopment/Mobile/UserSettingCost/$list->ID/$list->staffID";
        $audioFile="";
        $projectID = $list->projectID;
        $productID = $list->productID;
        $processID = $list->ID;
        $result = $mobile->insertNotify($title, $content, 1, 0, '', $staff->ID, $url, $audioFile, $projectID, $productID, $processID);
        array_push($jo, $this->setLog($result['success'], 'notify staff', $result['msg'], $result['broadcastID'], $projectID, $productID, $processID));
        return $jo;
    }
    public function notifyOverdue($list, $jo)
    {
        $project = $this->project;
        $mobile = $this->mobile;
        $server = $this->serverData;
        $overdue = $project->checkOverdue($list->productID);
        if ($overdue) {
            //通知業務
            $sales = $server->getUserByerpID($list->salesID); 
            $title = "[$list->projectNumber]$list->projectName [$list->productNumber]$list->projectName 逾期警訊"; 
            $content = "[$list->referenceNumber]$list->referenceName 已延誤，負責人: $list->name";
            //$url = route('overdueInfo', ['processID' => $list->ID]);
            $url = "http://upgi.ddns.net/productDevelopment/Mobile/OverdueInfo/$list->ID";
            $audioFile="";
            $projectID = $list->projectID;
            $productID = $list->productID;
            $processID = $list->ID;
            $result = $mobile->insertNotify($title, $content, 1, 0, '', $sales->ID, $url, $audioFile, $projectID, $productID, $processID);
            array_push($jo, $this->setLog($result['success'], 'notify sales', $result['msg'], $result['broadcastID'], $projectID, $productID, $processID));
        }
        return $jo;
    }
    public function setLog($success, $type, $msg, $broadcastID, $projectID, $productID, $processID)
    {
        $log = array(
            'success' => $success,
            'type' => $type,
            'msg' => $msg,
            'time' => $this->carbon->now(),
            'broadcastID' => $broadcastID,
            'projectID' => $projectID,
            'productID' => $productID,
            'processID' => $processID,
        );
        return $log;
    }
}