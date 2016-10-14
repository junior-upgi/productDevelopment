<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Common;
use App\Http\Controllers\ServerData;
use Illuminate\Http\Request;
use Validator;
use Redirect;

use App\Repositories\companyStructure\StaffRepositories;

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
        return view('Login.reset')
            ->with('check', false)
            ->with('result', false);
    }

    public function checkPersonal(Request $request)
    {
        $input = $request->input();
        $ID = $input['account'];
        $personalID = studly_case($input['personalID']);
        $check = $this->server->checkPersonal($ID, $personalID);
        if (isset($check)) {
            return view('Login.reset')
                ->with('check', true)
                ->with('result', true)
                ->with('account', $check->ID)
                ->with('name', $check->name);
        } else {
            return view('Login.reset')
                ->with('check', false)
                ->with('result', true)
                ->with('msg', '驗證失敗');
        }
    }

    public function setPassword(Request $request)
    {
        $input = $request->input();
        $ID = $input['account'];
        $name = $input['name'];
        $password = $input['password'];
        $passwordConf = $input['passwordConf'];
        if ($password !== $passwordConf) {
            return view('Login.reset')
                ->with('check', true)
                ->with('result', true)
                ->with('account', $ID)
                ->with('name', $name)
                ->with('error', '密碼與確認密碼不一致');
        }
        $user = $this->staff;
        $existDB = $user->getUser($ID);
        if (!$existDB) {
            //新增資料
            $params = array(
                'mobileSystemAccount' => $ID,
                'erpID' => $ID
            );
            $db = $user->insertUser($params);
            if (!$db['success'])  {
                return view('Login.reset')
                    ->with('check', true)
                    ->with('result', true)
                    ->with('account', $ID)
                    ->with('name', $name)
                    ->with('error', $db['msg']);
            }
        }

        $existLDAP = $this->common->searchLDAP($ID);
        if ($existLDAP) {
            $ldap = $this->common->modifyLDAP($ID, $password);
        } else {
            $ldap = $this->common->addLDAP($ID, $password);
        }
        if ($ldap) {
            return view('Login.success');
        }
        return view('Login.reset')
            ->with('check', true)
            ->with('result', true)
            ->with('account', $ID)
            ->with('name', $name)
            ->with('error', '單一登入申請失敗');
    }
    public function testAD()
    {
        return $this->common->testAD();
    }
}