<?php
namespace App\Service;

//use Custom Class
use App\Http\Controllers\Common;
use App\Http\Controllers\ServerData;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Auth;
use App\Jobs\SendNotify;

//use Repositories
use App\Repositories\mobileMessagingSystem\MobileRepositories;
use App\Repositories\ProductDevelopment\ProjectRepositories;
use App\Repositories\upgiSystem\UpgiSystemRepository;

class NotificationService
{
    use DispatchesJobs;

    public $serverData;
    public $mobile;
    public $project;
    public $upgi;

    public function __construct(
        ServerData $serverData,
        MobileRepositories $mobileRepositories,
        ProjectRepositories $projectRepositories,
        upgiSystemRepository $upgi
    ) {
        $this->serverData = $serverData;
        $this->mobile = $mobileRepositories;
        $this->project = $projectRepositories;
        $this->upgi = $upgi;
    }

    public function sendNewProduct($id)
    {
        $user = Auth::user();
        define("GENERAL", 3);
        define("PRODUCTDEVELOPMENT", 0);
        $product = $this->project->getProductByID($id);
        $where = [];
        $params = ['key' => 'groupName', 'value' => "DevelopmentTeam"];
        array_push($where, $params);
        $groupList = $this->upgi->getList('vUserGroupList', $where)->get();
        $title = '新開發案通知';
        $content = "新增[$product->projectNumber][$product->referenceNumber]產品開發，請同仁上系統新增產品開發工序。";
        $messageID = constant("GENERAL");
        $systemID = constant("PRODUCTDEVELOPMENT");
        $url = ''; /** :TODO: 後續完成RWD頁面*/
        foreach ($groupList as $g) {
            $message = array(
                'title' => $title,
                'content' => $content,
                'messageID' => $messageID,
                'systemID' => $systemID,
                'uid' => $user->ID,
                'recipientID' => $g->ID,
                'url' => $url,
                'audioFile' => null,
                'projectID' => $g->projectID,
                'productID' => $g->ID,
                'processID' => null,
            );
            $this->dispatch(new SendNotify($message));
        }
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
        $productName = $list->first()->productName;
        //$salesID = $list->first()->salesID;
        //$salesName = $server->getUserByerpID($salesID)->name;
        $salesID = $server->getUserByerpID($list->first()->salesID)->ID;
        //to sales
        $title = "開始執行開發";
        $content = "[$projectNumber]$projectName [$productNumber]$productName 開始執行開發";
        $url = '';
        $audioFile = '';
        //$sendSales = $mobile->insertNotify($title, $content, 3, 0, $uid, $salesID, $url, $audioFile, $projectID, $productID);
        $message = array(
            'title' => $title,
            'content' => $content,
            'messageID' => 3,
            'systemID' => 0,
            'uid' => $uid,
            'recipientID' => $salesID,
            'url' => $url,
            'audioFile' => $audioFile,
            'projectID' => $projectID,
            'productID' => $productID,
            'processID' => '',
        );
        $this->dispatch(new SendNotify($message));
        //to all process staff
        $sendStaff = array();
        foreach($list as $process) {
            $result = $this->sendProcessMessage($process, $title, 3, 0, $uid, $url, $audioFile);
            array_push($sendStaff, array(
                'processID' => $process->ID,
                'success' => $result['success'],
                'msg' => $result['msg'],
            ));
        }
    }

    public function changeTimeCost($processID, $send = array())
    {
        try {
            $project = $this->project;
            $children = $project->getChildrenList($processID);
            foreach ($children as $list) {
                $thisID = $list->projectProcessID;
                $process = $project->getProcessByID($thisID);
                $title = '工序時間變更通知';
                $url = '';
                $audioFile = '';
                if (!array_search($thisID, $send, true)) {
                    $this->sendProcessMessage($process, $title, 3, 0, $process->staffID, $url, $audioFile);
                    array_push($send, $thisID);
                }
                $this->changeTimeCost($thisID,$send);
            }
            return array(
                'success' => true,
                'msg' => '通知發送成功!',
            );
        } catch (\Exception $e) {
            return array(
                'success' => false,
                'msg' => $e['errorInfo'][2],
            );
        }        
    }
    public function sendProcessMessage($process, $title, $messageID, $systemID, $uid, $url, $audioFile)
    {
        $server = $this->serverData;
        $processNumber = $process->referenceNumber;
        $processName = $process->referenceName;
        $phaseName = $process->PhaseName;
        $startDate = date('Y-m-d', strtotime($process->processStartDate));
        $cost = $process->timeCost;
        $content = "[$phaseName][$processNumber]$processName,開始時間$startDate,工時：$cost 天";
        $recipientID = $server->getuserByerpID($process->staffID)->ID;
        //return $mobile->insertNotify($title, $content, 3, 0, $uid, $recipientID, $url, $audioFile, $projectID, $productID, $processID);
        $message = array(
            'title' => $title,
            'content' => $content,
            'messageID' => $messageID,
            'systemID' => $systemID,
            'uid' => $uid,
            'recipientID' => $recipientID,
            'url' => $url,
            'audioFile' => $audioFile,
            'projectID' => $process->projectID,
            'productID' => $process->productID,
            'processID' => $process->processID,
        );
        $this->dispatch(new SendNotify($message));
    }
    public function sendMessageBase($getData) 
    {
        try {
            $user = Auth::user();
            $uid = $user->ID;
            $sender = $user->staff()->first();
            foreach ($getData as $list) {
                $recipientID = $this->serverData->getUserByerpID($list['recipientID'])->ID;
                $title = $list['title'];
                $message = array(
                    'title' => "[$sender->nodeName]$sender->name: $title",
                    'content' => $list['content'],
                    'messageID' => $list['messageID'],
                    'systemID' => $list['systemID'],
                    'uid' => $sender,
                    'recipientID' => $recipientID,
                    'url' => $list['url'],
                    'audioFile' => $list['audioFile'],
                    'projectID' => '',
                    'productID' => '',
                    'processID' => '',
                );
                $this->dispatch(new SendNotify($message));
            }
            return array(
                'success' => true,
                'msg' => '訊息送出成功!',
            );
        } catch (\Exception $e) {
            return array(
                'success' => false,
                'msg' => $e['errorInfo'][2],
            );
        }
    }
}