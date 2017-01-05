<?php
namespace App\Service;

//use Custom Class
use App\Http\Controllers\Common;
use App\Http\Controllers\ServerData;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Auth;
use App\Jobs\SendNotify;

//use Repositories
//use App\Repositories\mobileMessagingSystem\MobileRepository;
use App\Repositories\ProductDevelopment\ProjectRepository;
use App\Repositories\upgiSystem\UpgiSystemRepository;

use App\Service\TelegramService;

class NotificationService
{
    use DispatchesJobs;

    public $serverData;
    //public $mobile;
    public $project;
    public $upgi;
    public $telegram;

    public function __construct(
        ServerData $serverData,
        //MobileRepository $mobileRepositories,
        ProjectRepository $projectRepository,
        upgiSystemRepository $upgi,
        TelegramService $telegram
    ) {
        $this->serverData = $serverData;
        //$this->mobile = $mobileRepositories;
        $this->project = $projectRepository;
        $this->upgi = $upgi;
        $this->telegram = $telegram;
    }
    public function sendNewProduct($id, $groupID)
    {
        $user = Auth::user();
        $product = $this->project->getProductByID($id);
        $message = '新增[' . $product->projectName . '][' . $product->referenceName . ']產品開發，請同仁上系統新增產品開發工序';
        //$this->telegram->sendProductTeam($message);
        $token = $this->telegram->getBotToken('productDevelopmentBot');
        $this->telegram->botSendMessage($token, $groupID, $message, true);
    }

    public function productExecute($productID)
    {
        $project = $this->project->getNonCompleteProcessList($productID)->first();
        $message = '[' . $project->projectName . ']' . ' [' . $project->productName . ']' . ' 開始執行開發';
        $this->telegram->sendProductTeam($message, true);
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
                    $this->sendProcessMessage($process);
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

    public function sendProcessMessage($process)
    {
        $processNumber = $process->referenceNumber;
        $processName = $process->referenceName;
        $phaseName = $process->PhaseName;
        $startDate = date('Y-m-d', strtotime($process->processStartDate));
        $cost = $process->timeCost;
        $message = '[' . $phaseName . '][' . $processName . ']' . 
            ',開始時間' . $startDate . ',工時：' . $cost . ' 天';
        $erp_id = $process->staffID;
        $this->telegram->productDevelopmentBotSendToUser($erp_id, $message, true);
    }

    public function processComplete($processID)
    {
        $process = $this->project->getProcessByID($processID);
        $productID = $process->productID;
        $url = 'http://upgi.ddns.net/productDevelopment/Process/ProcessList/' . $productID;
        $message = '[' . $process->projectName .'][' . $process->productName . '][' . $process->PhaseName . '][' .
            $process->referenceName . ']' . $process->name . ' 已完成，詳細內容請點擊連結：' . $url;
        $erp_id = $process->salesID;
        $this->telegram->productDevelopmentBotSendToUser($erp_id, $message, true);
    }
}