<?php
namespace App\Service;

use App\Http\Controllers\Common;
use App\Http\Controllers\ServerData;
use Carbon\Carbon;
//use App\Repositories\mobileMessagingSystem\MobileRepository;
use App\Repositories\ProductDevelopment\ProjectRepository;
use App\Repositories\companyStructure\StaffRepository;
use App\Repositories\upgiSystem\UpgiSystemRepository;

use App\Service\TelegramService;

class ProjectCheckService
{
    public $common;
    public $carbon;
    public $project;
    public $staff;
    //public $mobile;
    public $serverData;
    public $upgi;
    public $telegram;

    public function __construct(
        Common $common,
        Carbon $carbon,
        ServerData $serverData,
        ProjectRepository $project,
        StaffRepository $staff,
        //MobileRepository $mobile,
        UpgiSystemRepository $upgi,
        TelegramService $telegram
    ) {
        $this->common = $common;
        $this->carbon = $carbon;
        $this->serverData = $serverData;
        $this->project = $project;
        $this->staff = $staff;
        //$this->mobile = $mobile;
        $this->upgi = $upgi;
        $this->telegram = $telegram;
    }

    public function sendOverdue()
    {
        /**
         * 1、取得SendOverdue群組成員清單
         * 2、發送逾期清單給SendOverdue群組成員
         */

        $url = "http://upgi.ddns.net/productDevelopment/Mobile/overdueList";
        $message = '產品開發案逾期清單 ' . $url ;
        $this->telegram->sendProductTeam($message);
    }

    public function timeCostReport()
    {
        /**
         * 1、取得目前開始執行的工序
         * 2、回報工作剩於天數給負責人
         */
        $jo = array();
        //$mobile = $this->mobile;
        $server = $this->serverData;
        $processList = $this->project->getStartProcess();
        $now = date('Y-m-d', strtotime($this->carbon->now()));
        foreach ($processList as $list) {
            $endDate = date('Y-m-d', strtotime($list->processEndDate));
            $reciprocal = (strtotime($endDate) - strtotime($now)) / (60*60*24);
            if ($reciprocal >= 0) {
                $erp_id = $list->staffID;
                $url = 'http://upgi.ddns.net/productDevelopment/Mobile/UserSettingCost/' . $list->ID . '/' . $list->staffID;
                $message = '[' . $list->referenceNumber . ']' . $list->referenceName . ' 完工日： ' . $endDate . ' ，剩餘 ' . $reciprocal . ' 日';
                $user = $server->getuserByerpID($erp_id);
                if (isset($user)) {
                    $this->telegram->productDevelopmentBotSendToUser($erp_id, $message);
                } else {
                    $message = '[' . $list->referenceNumber . ']' . $list->referenceName . ' 完工日： ' . $endDate . ' ，剩餘 ' . $reciprocal . ' 日';
                    $message = '**此工序尚未指定負責人** ' . $message;
                    $this->telegram->sendProductTeam($message);
                }
            }
        }
    }

    public function everyDay()
    {
        /**
         * 1、更新每日延誤程序之工期延期至今日
         * 2、通知個人工期已延誤至今日
         * 3、如果最後完成時間已逾期，則通知開發案負責人
         * 4、通知開發案負責人，尚有未開始執行之開發案
         */

        //取得現階段執行且延誤之程序 getDelayProcess()
        
        $processList = $this->project->getDelayProcess();

        foreach ($processList as $list) {
            //通知負責人修正程序工期延至今日
            $this->notifyExtension($list);
            //檢查是否最後完成時間己逾期，並通知開發案負責人
            $this->notifyOverdue($list);
            //發送尚未開始執行之開發案清單至開發案群組
        }
        $this->notYetExecute();
        return true;
    }

    public function notifyExtension($list)
    {
        //$mobile = $this->mobile;
        $server = $this->serverData;
        $startDate = date('Y-m-d', strtotime($list->processStartDate));
        $now = date('Y-m-d', strtotime($this->carbon->now()));
        $newCost = ((strtotime($now) - strtotime($startDate)) / (60*60*24)) + 1;
        $params = array(
            'timeCost' => $newCost,
        );
        $setCost = $this->project->updateProcess($list->ID, $params);
        if ($setCost['success']) {
            $url = 'http://upgi.ddns.net/productDevelopment/Mobile/UserSettingCost/' . $list->ID . '/' . $list->staffID;
            $message = '[' . $list->referenceNumber . ']' . $list->referenceName . ' 已延誤，完成時間延至今日，詳細資訊請點連結：' . $url;
            $erp_id = $list->staffID;
            //$this->telegram->productDevelopmentBotSendToUser($erp_id, $message);
            $user = $server->getuserByerpID($erp_id);
            if (isset($user)) {
                $this->telegram->productDevelopmentBotSendToUser($erp_id, $message);
            } else {
                $message = '[' . $list->referenceNumber . ']' . $list->referenceName . ' 已延誤，完成時間延至今日。';
                $message = '**此工序尚未指定負責人** ' . $message;
                $this->telegram->sendProductTeam($message);
            }
        }
    }
    public function notifyOverdue($list)
    {
        $project = $this->project;
        //$mobile = $this->mobile;
        $server = $this->serverData;
        $overdue = $project->checkOverdue($list->productID);
        if ($overdue) {
            //通知業務
            $erp_id = $list->salesID;
            $url = 'http://upgi.ddns.net/productDevelopment/Mobile/OverdueInfo/' . $list->ID;
            $message = '[' . $list->referenceNumber . ']' . $list->referenceName . ' 已延誤，負責人: ' . $list->name . '，詳細資訊請點連結：' . $url;
            $this->telegram->productDevelopmentBotSendToUser($erp_id, $message);
        }
    }

    public function notYetExecute()
    {
        $obj = $this->project->getNotYetExecuteList();
        if ($obj->get()->count() > 0) {
            $notYetList = $obj->get();
            foreach ($notYetList as $list) {
                $erp_id = $list->salesID;
                $projectNumber = $list->projectNumber;
                $projectName = $list->projectName;
                $productNumber = $list->referenceNumber;
                $productName = $list->referenceName;
                $message = "[$projectNumber]$projectName [$productNumber]$productName 尚未開始執行!";
                $this->telegram->productDevelopmentBotSendToUser($erp_id, $message);
            }
        }
    }
}