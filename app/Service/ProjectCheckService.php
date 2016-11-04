<?php
namespace App\Service;

use App\Http\Controllers\Common;
use App\Http\Controllers\ServerData;
use Carbon\Carbon;
use App\Repositories\mobileMessagingSystem\MobileRepositories;
use App\Repositories\ProductDevelopment\ProjectRepositories;
use App\Repositories\companyStructure\StaffRepositories;
use App\Repositories\upgiSystem\UpgiSystemRepository;

class ProjectCheckService
{
    public $common;
    public $carbon;
    public $project;
    public $staff;
    public $mobile;
    public $serverData;
    public $upgi;

    public function __construct(
        Common $common,
        Carbon $carbon,
        ServerData $serverData,
        ProjectRepositories $project,
        StaffRepositories $staff,
        MobileRepositories $mobile,
        UpgiSystemRepository $upgi
    ) {
        $this->common = $common;
        $this->carbon = $carbon;
        $this->serverData = $serverData;
        $this->project = $project;
        $this->staff = $staff;
        $this->mobile = $mobile;
        $this->upgi = $upgi;
    }

    public function delayProject()
    {

    }

    public function delayProduct()
    {
        
    }

    public function sendOverdue()
    {
        /**
         * 1、取得SendOverdue群組成員清單
         * 2、發送逾期清單給SendOverdue群組成員
         */
        $jo = [];
        $upgi = $this->upgi;
        $where = [];
        $now = date('Y-m-d', strtotime($this->carbon->now()));
        $params = ['key' => 'groupName', 'value' => "SendOverdue"];
        array_push($where, $params);
        $groupList = $upgi->getList('vUserGroupList', $where);
        foreach ($groupList as $g) {
            $title = "產品開發案逾期清單";
            $content = "截至今日 $now 產品開發案逾期清單";
            $staff = $g->ID;
            $url = ""; //***********
            $audioFile = "";
            $projectID = $list->projectID;
            $productID = $list->productID;
            $processID = $list->ID;
            define("SCHEDULE_ALERT", 1);
            define("PRODUCTDEVELOPMENT", 0);
            $result = $mobile->insertNotify($title, $content, constant("SCHEDULE_ALERT"), constant("PRODUCTDEVELOPMENT"), '', $staff->ID, $url, $audioFile, $projectID, $productID, $processID);
            array_push($jo, $this->setLog($result['success'], 'notify staff', $result['msg'], $result['broadcastID'], $projectID, $productID, $processID));
        }
        return $jo;
    }

    public function timeCostReport()
    {
        /**
         * 1、取得目前開始執行的工序
         * 2、回報工作剩於天數給負責人
         */
        $jo = array();
        $mobile = $this->mobile;
        $server = $this->serverData;
        $processList = $this->project->getStartProcess();
        $now = date('Y-m-d', strtotime($this->carbon->now()));
        foreach ($processList as $list) {
            $endDate = date('Y-m-d', strtotime($list->processEndDate));
            $reciprocal = (strtotime($endDate) - strtotime($now)) / (60*60*24);
            if ($reciprocal >= 0) {
                $title = "工序完工期限通知";
                $content = "[$list->referenceNumber]$list->referenceName 完工日： $endDate ，剩餘 $reciprocal 日";
                $staff = $server->getUserByerpID($list->staffID);
                $url = "http://upgi.ddns.net/productDevelopment/Mobile/UserSettingCost/$list->ID/$list->staffID";
                $audioFile = "";
                $projectID = $list->projectID;
                $productID = $list->productID;
                $processID = $list->ID;
                define("SCHEDULE_ALERT", 1);
                define("PRODUCTDEVELOPMENT", 0);
                $result = $mobile->insertNotify($title, $content, constant("SCHEDULE_ALERT"), constant("PRODUCTDEVELOPMENT"), '', $staff->ID, $url, $audioFile, $projectID, $productID, $processID);
                array_push($jo, $this->setLog($result['success'], 'notify staff', $result['msg'], $result['broadcastID'], $projectID, $productID, $processID));
            }
        }
        return $jo;
    }

    public function everyDay()
    {
        /**
         * 1、更新每日延誤程序之工期延期至今日
         * 2、通知個人工期已延誤至今日
         * 3、如果最後完成時間已逾期，則通知業務
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
            array_push($jo, $this->setLog(false, 'update cost error', $setCost['msg'], '', $list->projectID, $list->productID, $list->ID));
            return $jo;
        }
        //通知個人工期延誤
        $title = "專案開發工序已延誤";
        $content = "[$list->referenceNumber]$list->referenceName 已延誤，完成時間延至今日";
        $staff = $server->getUserByerpID($list->staffID);
        //$url = route('userSettingCost', ['processID' => $list->ID, 'staffID' => $list->staffID]);
        $url = "http://upgi.ddns.net/productDevelopment/Mobile/UserSettingCost/$list->ID/$list->staffID";
        $audioFile = "alarm.mp3";
        $projectID = $list->projectID;
        $productID = $list->productID;
        $processID = $list->ID;
        define("SCHEDULE_ALERT", 1);
        define("PRODUCTDEVELOPMENT", 0);
        $result = $mobile->insertNotify($title, $content, constant("SCHEDULE_ALERT"), constant("PRODUCTDEVELOPMENT"), '', $staff->ID, $url, $audioFile, $projectID, $productID, $processID);
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
            $title = "專案開發逾期警訊"; 
            $content = "[$list->referenceNumber]$list->referenceName 已延誤，負責人: $list->name";
            //$url = route('overdueInfo', ['processID' => $list->ID]);
            $url = "http://upgi.ddns.net/productDevelopment/Mobile/OverdueInfo/$list->ID";
            $audioFile = "alarm.mp3";
            $projectID = $list->projectID;
            $productID = $list->productID;
            $processID = $list->ID;
            define("SCHEDULE_ALERT", 1);
            define("PRODUCTDEVELOPMENT", 0);
            $result = $mobile->insertNotify($title, $content, constant("SCHEDULE_ALERT"), constant("PRODUCTDEVELOPMENT"), '', $sales->ID, $url, $audioFile, $projectID, $productID, $processID);
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