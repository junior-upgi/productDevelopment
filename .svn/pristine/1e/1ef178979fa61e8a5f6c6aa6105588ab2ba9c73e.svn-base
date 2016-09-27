<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Common;
use App\Http\Controllers\ServerData;

use App\Repositories\StaffRpositories;

class ResetPasswordController
{
    public $common;
    public $server;
    public $staff;

    public function __construct(
        Common $common,
        ServerData $server,
        StaffRepositories $staff
    ) {
        $this->common = $common;
        $this->server = $server;
        $this->staff = $staff;
    }
    
    public function resetPassword()
    {
        return view();
    }

    public function checkPersonal($ID, $personalID)
    {
        $check = $server->checkPersonal($ID, $personalID);
        if ($check) {
            return array(
                'success' => true,
                'msg' => '驗證成功'
            );
        } else {
            return array(
                'success' => false,
                'msg' => '驗證失敗'
            );
        }
    }

    public function setPassword($ID, $password)
    {
        $user = $this->staff;
        $existDB = $user->getUser($ID);
        $existLDAP = $this->common->checkLDAP($ID, '');
        if (!$existDB) {
            //新增資料
            $params = array(
                'mobileSystemAccount' => $ID,
                'erpID' => $ID
            );
            $db = $user->insertUser($params);
        }
        if 

        if ($existLDAP) {
            $ldap = $this->common->addLDAP($ID, $password);
        } else {
            $ldap = $this->common->modifyLDAP($ID, $password);
        }
        if (($db['success'] || !isset($db)) && $ldap) {
            return true;
        }
        return false;
    }
}