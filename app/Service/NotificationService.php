<?php
namespace App\Service;

//use Custom Class
use App\Http\Controllers\Common;
use App\Http\Controllers\ServerData;

//use Repositories
use App\Repositories\mobileMessagingSystem\MobileRepositories;
use App\Repositories\ProductDevelopment\ProjectRepositories;

class NotificationService
{
    public $serverData;
    public $mobile;
    public $project;

    public function __construct(
        ServerData $serverData,
        MobileRepositories $mobileRepositories,
        ProjectRepositories $projectRepositories
    ) {
        $this->serverData = $serverData;
        $this->mobile = $mobileRepositories;
        $this->project = $projectRepositories;
    }

    public function productExecute($productID,$uid='')
    {
        $project = $this->project;
        $mobile = $this->mobile;
        $server = $this->serverData;

        $list = $project->getNonCompleteProcessList($productID);
        $projectID = $list->first()->projectID;
        $projectNumber = $list->first()->projectNumber;
        $projectName = $list->first()->projectName;
        $productID = $list->first()->productID;
        $productNumber = $list->first()->productNumber;
        $productName = $list->first()->ProductName;
        //$salesID = $list->first()->salesID;
        //$salesName = $server->getUserByerpID($salesID)->name;
        $salesID = $server->getUserByerpID($list->first()->salesID)->ID;
        //to sales
        $title = "開始執行開發";
        $content = "[$projectNumber]$projectName _ [$productNumber]$productName 開始執行開發";
        $url = '';
        $audioFile = '';
        $sendSales = $mobile->insertNotify($title, $content, 3, 0, $uid, $salesID, $url, $audioFile, $projectID, $productID);
        //to all process staff
        $sendStaff = array();
        foreach($list as $p) {
            $processNumber = $p->referenceNumber;
            $processName = $p->referenceName;
            $phaseName = $p->PhaseName;
            $startDate = date('Y-m-d', strtotime($p->processStartDate));
            $cost = $p->timeCost;
            $title = '工作排程通知';
            $content = "[$phaseName][$processNumber]$processName,開始時間:$startDate,工時:$cost 天";
            $recipientID = $server->getuserByerpID($p->staffID)->ID;
            $a = $mobile->insertNotify($title, $content, 3, 0, $uid, $recipientID, $url, $audioFile, $projectID, $productID, $p->ID);
            array_push($sendStaff, array(
                'processID' => $p->ID,
                'success' => $a['success'],
                'msg' => $a['msg'],
            ));
        }
    }

}