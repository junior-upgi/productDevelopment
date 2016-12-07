<?php

namespace App\Http\Controllers\Service;

//use Class
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Controllers\Common;
use App\Http\Controllers\ServerData;
use Illuminate\Support\Facades\Hash;
//use Illuminate\Foundation\Bus\DispatchesJobs;
//use App\Jobs\Sendnotify;

use DB;
use App\Models\productDevelopment\Para;
use App\Models\productDevelopment\Project;
use App\Models\productDevelopment\VProjectList;
use App\Models\productDevelopment\VProcessList;
use App\Models\productDevelopment\ProjectContent;
use App\Models\productDevelopment\ProjectProcess;
use App\Models\companyStructure\VStaff;
use App\Models\companyStructure\Staff;
use App\Models\companyStructure\Node;
use App\Models\sales\Client;
use App\Models\upgiSystem\User;
use App\Models\upgiSystem\UserGroup;
use App\Models\upgiSystem\UserGroupMembership;
use App\Models\mobileMessagingSystem\BroadcastStatus;
use App\Models\mobileMessagingSystem\Message;
use App\Models\mobileMessagingSystem\MessageCategory;
use App\Models\mobileMessagingSystem\SystemCategory;
use App\Models\mobileMessagingSystem\VBroadcastList;
use App\Models;

use App\Service\ProjectCheckService;
use App\Service\NotificationService;
use App\Service\TelegramService;


/*
1、提供開發案系統與推播App傳遞資料
*/
class WebServiceController extends Controller
{
    //use DispatchesJobs;

    public $common;
    public $server;
    public $projectCheck;
    public $notify;
    public $user;
    public $telegram;

    public function __construct(
        Common $common,
        ServerData $server,
        ProjectCheckService $projectCheck,
        NotificationService $notify,
        User $user,
        TelegramService $telegram
    ) {
        $this->common = $common;
        $this->server = $server;
        $this->projectCheck = $projectCheck;
        $this->notify = $notify;
        $this->user = $user;
        $this->telegram = $telegram;
    }

    private function checkUser($account, $password, $type)
    {
        //type=0 DB, type=1 AD
        return $this->common->singleSignOn($account, $password, $type, false);
    }

    /*
    首次登入驗證使用者資訊
    */
    public function userLogin($Account, $Password, $DeviceOS, $DeviceID, $DeviceToken)
    {
        $user = new User();
        $jo = array();
        //$Password = Hash::make($Password);
        //驗證使用者帳密
        
        $CheckUser = $user
            ->where('mobileSystemAccount', $Account);
            //->where('password', $Password);
        /*
        $u = $CheckUser->first();    
        $CheckPassword = Hash::check($Password, $u->password);
        */
        $type = env('MobileSSO', 1);
        $sso = $this->checkUser($Account, $Password, $type);

        if ($sso) {
            //通過驗證
            try {
                //寫入使用者設備資訊 
                DB::beginTransaction();
                $Params = array(
                    'deviceOS' => $DeviceOS,
                    'deviceID' => $DeviceID,
                    'deviceToken' => $DeviceToken,
                );
                $CheckUser->update($Params);
                DB::commit();
                $jo = array(
                    'success' => true,
                    'msg' => '驗證通過，設備訊息已寫入!',
                );
                $this->resetToken($Account, $DeviceOS, $DeviceID);
            } catch (\PDOException $e) {
                DB::rollback();
                $jo = array(
                    'success' => false,
                    'msg' => $e['errorInfo'][2],
                );
            }
        } else {
            //使用者驗證失敗
            $jo = array(
                'success' => false,
                'msg' => '帳號密碼錯誤!'
            );
        }

        return $jo;
    }

    /*
    驗證設備資訊是否已寫入Server
    */
    public function resetToken($account, $os, $device)
    {
        $user = $this->user;
        $check = $user
            ->where('mobileSystemAccount', '<>', $account)
            ->where('deviceOS', $os)
            ->where('deviceID', $device);
        $params = [
            'deviceToken' => null,
            'deviceID' => null,
            'deviceOS' => null,
        ];
        $check->update($params);
    }
    public function checkDevice($DeviceOS, $DeviceID, $DeviceToken)
    {
        $User = new User();
        $jo = array();

        //驗證設備資訊
        $CheckDevice = $User
            ->where('deviceOS', $DeviceOS)
            ->where('deviceID', $DeviceID)
            ->where('deviceToken', $DeviceToken);

        if ($CheckDevice->first()) {
            $jo = array(
                'success' => true,
                'msg' => '通過驗證!'
            );
        } else {
            $jo = array(
                'success' => false,
                'msg' => '設備資料不存在!'
            );
        }

        return $jo;
    }
    /*
    插入推播訊息動作時間

    */
    public function messageTime($time)
    {   
        $json = json_decode($time);
        $MessageTime = new BroadcastStatus();
        $jo = array();
        $Now = Carbon::now();;
        $Params = array();
        DB::beginTransaction();
        try {
            foreach($json as $list) {
                $update = $MessageTime->where('ID', $list->broadcastID);
                $t  = $update->first();
                if ($t) {
                    switch ($list->action) {
                        case 'sent':
                        case 'retracted':
                        case 'received':
                        case 'acknowledged':
                            $Params = array(
                                $list->action => $Now
                            );
                            break;
                    }
                    if (count($Params) > 0) {
                        //寫入動作時間 
                        $update->update($Params);
                    }
                }
            }
            DB::commit();
            $jo = array(
                'success' => true,
                'msg' => '寫入成功',
            );
        } catch (\PDOException $e) {
            DB::rollback();
            $jo = array(
                'success' => false,
                'msg' => $e['errorInfo'][2],
            );
        }
        return $jo;
    }

    public function sendMessage(Request $request)
    {
        $getData = $request->json()->all();
        $result = $this->telegram->productDevelopmentBotSendToUser($getData['erpID'], $getData['message']);
        if ($result) {
            return ['success' => true];
        }
    }
}