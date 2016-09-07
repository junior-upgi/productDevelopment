<?php

namespace App\Http\Controllers\Service;

//use Class
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Controllers\Common;
use Illuminate\Support\Facades\Hash;

//use DB
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

use DB;
/*
1、提供開發案系統與推播App傳遞資料
*/
class WebServiceController extends Controller
{
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
        $u = $CheckUser->first();    
        $CheckPassword = Hash::check($Password, $u->password);
        if ($CheckPassword) {
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
            } catch (\PDOException $e) {
                DB::rollback();
                $jo = array(
                    'success' => false,
                    'msg' => $e,
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
    test = 83e0b733-62a9-11e6-a882-1cb72cdefcf9
    */
    public function messageTime($BroadcastID, $Action)
    {   
        $MessageTime = new BroadcastStatus();
        $jo = array();
        $Now = Carbon::now();
        $Params = array();
        $MessageTime = $MessageTime->where('ID', $BroadcastID);
        //檢查訊息是否存在
        if ($MessageTime->first()) {
            switch ($Action) {
                case 'sent':
                case 'retracted':
                case 'received':
                case 'acknowledged':
                    $Params = array(
                        $Action => $Now
                    );
                    break;
            }
            if ($Params) {
                try {
                    //寫入動作時間 
                    DB::beginTransaction();
                    $MessageTime->update($Params);
                    DB::commit();
                    $jo = array(
                        'success' => true,
                        'msg' => $Action . ' Time:' . $Now,
                    );
                } catch (\PDOException $e) {
                    DB::rollback();
                    $jo = array(
                        'success' => false,
                        'msg' => $e,
                    );
                }
            } else {
                //Action 參數不正確
                $jo = array(
                    'success' => false,
                    'msg' => 'Action參數不正確(Action=' . $Action .')',
                );
            }
        } else {
            //broadcastID不存在
            $jo = array(
                'success' => false,
                'msg' => 'broadcastID不存在!!'
            );
        }

        return $jo;
    }
    public function testMessage($Account, $Title, $Content, $Url, $AudioFile)
    {
        $BroadcastID = Common::getNewGUID();
        $MessageID = Common::getNewGUID();
        $MessageCategoryID = 999;
        $User = new User();
        $User = $User->where('mobileSystemAccount', $Account)->first();
        $Message = new Message();
        $BroadcastStatus = new BroadcastStatus();
        if (!$User) {
            $jo = array(
                'success' => false,
                'msg' => '找不到帳號資訊!',
            );
            return $jo;
        }
        try {
            //寫入測䛫訊息
            DB::beginTransaction();
            $Params = array(
                'ID' => $MessageID,
                'messageCategoryID' => 999,
                'systemCategoryID' => 0,
                'manualTopic' => $Title,
                'content' => $Content,
            );
            $Message->insert($Params);

            $Params = array(
                'ID' => $BroadcastID,
                'messageID' => $MessageID,
                'recipientID' => $User->ID,
                'primaryRecipient' => 0,
                'url' => $Url,
                'audioFile' => $AudioFile,
            );
            $BroadcastStatus->insert($Params);

            DB::commit();
            $jo = array(
                'success' => true,
                'msg' => '推播訊息已寫入!',
            );
        } catch (\PDOException $e) {
            DB::rollback();
            $jo = array(
                'success' => false,
                'msg' => $e,
            );
        }
        return $jo;
    }
}