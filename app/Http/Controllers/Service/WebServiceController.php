<?php

namespace App\Http\Controllers\Service;

//use Class
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Controllers\Common;

//use DB
use App\Models\productDevelopment\para;
use App\Models\productDevelopment\Project;
use App\Models\productDevelopment\vProjectList;
use App\Models\productDevelopment\vProcessList;
use App\Models\productDevelopment\ProjectContent;
use App\Models\productDevelopment\ProjectProcess;
use App\Models\companyStructure\vStaff;
use App\Models\companyStructure\Staff;
use App\Models\companyStructure\Node;
use App\Models\sales\Client;
use App\Models\upgiSystem\user;
use App\Models\upgiSystem\userGroup;
use App\Models\upgiSystem\userGroupMembership;
use App\Models\mobileMessagingSystem\broadcastStatus;
use App\Models\mobileMessagingSystem\message;
use App\Models\mobileMessagingSystem\messageCategory;
use App\Models\mobileMessagingSystem\systemCategory;
use App\Models\mobileMessagingSystem\vBroadcastList;
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
    public function UserLogin($Account, $Password, $DeviceOS, $DeviceID, $DeviceToken)
    {
        $user = new User();
        $jo = array();

        //驗證使用者帳密
        $CheckUser = $user
            ->where('mobileSystemAccount', $Account)
            ->where('password', $Password);     
        if($CheckUser->first())
        {
            //通過驗證
            try{
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
            }
            catch (\PDOException $e)
            {
                DB::rollback();
                $jo = array(
                    'success' => false,
                    'msg' => $e,
                );
            }
        }
        else
        {
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
    public function CheckDevice($DeviceOS, $DeviceID, $DeviceToken)
    {
        $User = new User();
        $jo = array();

        //驗證設備資訊
        $CheckDevice = $User
            ->where('deviceOS', $DeviceOS)
            ->where('deviceID', $DeviceID)
            ->where('deviceToken', $DeviceToken);
        if($CheckDevice->first())
        {
            $jo = array(
                'success' => true,
                'msg' => '通過驗證!'
            );
        }
        else
        {
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
    public function MessageTime($BroadcastID, $Action)
    {   
        $MessageTime = new broadcastStatus();
        $jo = array();
        $Now = Carbon::now();
        $Params = array();
        $MessageTime = $MessageTime->where('ID', $BroadcastID);
        //檢查訊息是否存在
        if($MessageTime->first())
        {
            switch($Action)
            {
                case 'sent':
                case 'retracted':
                case 'received':
                case 'acknowledged':
                    $Params = array(
                        $Action => $Now
                    );
                    break;
            }
            if($Params)
            {
                try{
                    //寫入動作時間 
                    DB::beginTransaction();
                    $MessageTime->update($Params);
                    DB::commit();
                    $jo = array(
                        'success' => true,
                        'msg' => $Action . ' Time:' . $Now,
                    );
                }
                catch (\PDOException $e)
                {
                    DB::rollback();
                    $jo = array(
                        'success' => false,
                        'msg' => $e,
                    );
                }
            }
            else
            {
                //Action 參數不正確
                $jo = array(
                    'success' => false,
                    'msg' => 'Action參數不正確(Action=' . $Action .')',
                );
            }
        }
        else
        {
            //broadcastID不存在
            $jo = array(
                'success' => false,
                'msg' => 'broadcastID不存在!!'
            );
        }
        return $jo;
    }
}