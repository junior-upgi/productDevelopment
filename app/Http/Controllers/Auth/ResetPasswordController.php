<?php
/**
 * 使用者申請與重設密碼
 *
 * @version 1.0.0
 * @author spark it@upgi.com.tw
 * @date 16/10/14
 * @since 1.0.0 spark: 於此版本開始編寫註解
 */
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Common;
use App\Http\Controllers\ServerData;
use Illuminate\Http\Request;
use Validator;
use Redirect;

use App\Repositories\companyStructure\StaffRepository;

/**
 * Class ResetpasswordController
 *
 * @package App\Http\Controllers\Auth
 */
class ResetPasswordController
{
    /** @var Common 注入共用元件 */
    private $common;
    /** @var ServerData 注入ServerData */
    private $server;
    /** @var StaffRepository 注入StaffRepository */
    private $staff;

    /**
     * 建構式
     *
     * @param Common $common
     * @param ServerData $server
     * @param StaffRepository $staff
     * @return void
     */
    public function __construct(
        Common $common,
        ServerData $server,
        StaffRepository $staff
    ) {
        $this->common = $common;
        $this->server = $server;
        $this->staff = $staff;
    }
    
    /**
     * 回傳重設密碼頁面
     *
     * @return view 重設密碼頁面
     */
    public function resetPassword()
    {
        return view('Login.reset')
            ->with('check', false)
            ->with('result', false);
    }

    /**
     * 驗證個人基本資料
     * 
     * @param Request $request request內容
     * @return view 回傳頁面
     */
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

    /**
     * 對Model進行insert的方法
     * 
     * @param Request $request request內容
     * @return view 回傳頁面
     */
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
}