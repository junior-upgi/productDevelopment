<?php
/**
 * 系統共用方法
 *
 * @version 1.0.0
 * @author spark it@upgi.com.tw
 * @date 16/10/14
 * @since 1.0.0 spark: 於此版本開始編寫註解
 */
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Auth;
use App\Models\upgiSystem\User;
use App\Models\upgiSystem\File;

/**
 * Class Common
 *
 * @package App\Http\Controllers
 */
class Common
{
    /** @var User 注入User Model */
    private $user;
    /** @var File 注入File Model */
    private $file;

    /**
     * Common 建構式
     *
     * @param User $user
     * @param File $file
     * @return void
     */
    public function __construct(
        User $user,
        File $file
    ) {
        $this->user = $user;
        $this->file = $file;
    }

    /**
     * 產生GUID
     *
     * @return string 回傳GUID
     */
    public static function getNewGUID()
    {
        $charid = strtoupper(md5(uniqid(mt_rand(), true)));
        $hyphen = chr(45);// "-"
        $uuid = ""
        .substr($charid, 0, 8).$hyphen
        .substr($charid, 8, 4).$hyphen
        .substr($charid,12, 4).$hyphen
        .substr($charid,16, 4).$hyphen
        .substr($charid,20,12);
        
        return $uuid;
    }
    public function params($input, $addIgnore)
    {
        $ignore = array_merge(['_token', 'type', 'id'], $addIgnore);
        $input = array_except($input, $ignore);
        $params = array();
        $countInput = count($input);
        list($key, $value) = array_divide($input);
        for ($i = 0; $i < $countInput; $i++) {
            $big5 = $value[$i];
            $params[$key[$i]] = $big5;
        }
        return $params;
    }
    public function where($table, $where = null)
    {
        $obj = $table->where(function ($q) use ($where) {
            if (isset($where)) {
                foreach ($where as $w) {
                    $key = $w['key'];
                    $op = (!isset($w['op'])) ? '=' : $w['op'];
                    $value = $w['value'];
                    $or = (!isset($w['or'])) ? false : $w['or'];
                    if ($or) {
                        $q->orWhere($key, $op, $value);
                    } else {
                        $q->where($key, $op, $value);
                    }
                }
            }
        });
        return $obj;
    }

    /**
     * 對Model進行insert的方法
     * 
     * @param Model $table Model物件
     * @param array $params 參數與值
     * @return array 回傳結果
     * @throw Exception 例外
     */
    public function insert($table, $params)
    {
        try {
            $table->getConnection()->beginTransaction();
            $table->insert($params);
            $table->getConnection()->commit();
            return array(
                'success' => true,
                'msg' => 'success!',
            );
        } catch (\Exception $e) {
            $table->getConnection()->rollback();
            return array(
                'success' => false,
                'msg' => $e['errorInfo'][2],
            );
        }
    }

    /**
     * 對Model進行update的方法
     * 
     * @param Model $table Model物件
     * @param array $params 參數與值
     * @return array 回傳結果
     * @throw Exception 例外
     */
    public function update($table, $params)
    {
        try {
            $table->getConnection()->beginTransaction();
            $table->update($params);
            $table->getConnection()->commit();
            return array(
                'success' => true,
                'msg' => 'success!',
            );
        } catch (\Exception $e) {
            $table->getConnection()->rollback();
            return array(
                'success' => false,
                'msg' => $e['errorInfo'][2],
            );
        }
    }

    /**
     * 對Model進行delete的方法
     * 
     * @param Model $table Model物件
     * @param string $id 刪除的鍵值
     * @return array 回傳結果
     * @throw Exception 例外
     */
    public function delete($table)
    {
        try {
            $table->getConnection()->beginTransaction();
            $table->delete();
            $table->getConnection()->commit();
            return array(
                'success' => true,
                'msg' => 'success!',
            );
        } catch (\Exception $e) {
            $table->getConnection()->rollback();
            return array(
                'success' => false,
                'msg' => $e['errorInfo'][2],
            );
        }
    }

    /**
     * 系統單一登入認證
     * 提供DB與LDAP兩種認證方式
     * 
     * @param string $account 帳號
     * @param string $password 密碼
     * @param string $type 認證方式
     * @param bool $login LDAP認證後是否直接登入
     * @return bool 回傳結果
     */
    public function singleSignOn($account, $password, $type, $login=true)
    {
        switch ($type) {
            case 'DB':
                return $this->upgiDB($account, $password);
                break;
            case 'LDAP':
                return $this->upgiLDAP($account, $password, $login);
                break;
            default:
                return false;
        }
        return false;
    }

    /**
     * 以DB方式驗證並登入
     * 
     * @param string $account 帳號
     * @param string $password 密碼
     * @return bool 回傳結果
     */
    public function upgiDB($account, $password)
    {
        return Auth::attempt([
                'mobileSystemAccount' => $account,
                'password' => $password,
            ], true);
    }

    /**
     * 驗證LDAP帳號密碼
     * 如果$login=true則進行驗䛠後登入
     * 
     * @param string $account 帳號
     * @param string $password 密碼
     * @param bool $login 是否驗證後登入
     * @return bool 回傳驗證結果
     * @throw Exception 各項例外狀況
     */
    public function upgiLDAP($account, $password, $login=true)
    {
        try {
            // 取得LDAP連結
            $ldapconn = $this->connLDAP();
            if ($ldapconn) {
                // 認證帳號密碼
                $ldapbind = $this->checkLDAP($account, $password, $ldapconn);
                if ($ldapbind) {
                    if ($login) {
                        // 進行登入
                        $signin = $this->userLogin($account);
                        if ($signin) {
                            return true; //登入成功
                        }
                        return false; //登入失敗
                    }
                    return true; //驗證通過
                }
                return false; //驗證失敗
            }
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * 以使用者帳號進行登入動作
     * 
     * @param string $account 帳號
     * @return bool 回傳登入結果
     */
    public function userLogin($account)
    {
        $auth = $this->user->where('mobileSystemAccount', $account)->first();
        if ($auth) {
            Auth::loginUsingId($auth->ID, true);
            return true;
        } else {
            return false;
        }
    }

    /**
     * 建立LDAP連結
     * 
     * @return ldap_connect 連結結果
     */
    public function connLDAP()
    {
        $ldaphost = env('LDAP_HOST', '192.168.168.86');
        $ldapport = env('LDAP_PORT', '389');
        $ldapconn = ldap_connect($ldaphost, $ldapport) or die("con't connect LDAP");
        ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
        return $ldapconn;
    }

    /**
     * 以LDAP Admin帳號取得連結
     * 
     * @return ldap_connect 連結結果
     */
    public function LDAPAdmin($conn=null)
    {
        $conn = $this->getLDAPConn($conn);
        $adminac = env('LDAP_ADMIN', '');
        $adminpw = env('LDAP_PW', '');
        ldap_bind($conn, "cn=$adminac,dc=upgi,dc=ddns,dc=net", $adminpw);
        return $conn;
    }

    /**
     * 驗證LDAP帳號密碼
     * 
     * @param string $account 帳號
     * @param string $password 密碼
     * @param ldap_connect $conn LDAP連結物件
     * @return bool 回傳驗證結果
     * @throw Exception 各項例外狀況
     */
    public function checkLDAP($account, $password, $conn = null)
    {
        $conn = $this->getLDAPConn($conn);
        try {
            return ldap_bind($conn, "uid=$account,ou=user,dc=upgi,dc=ddns,dc=net", $password);
        } catch (\Exception $e) {
            return false;
        }
        return false;
    }

    /**
     * 搜尋LDAP帳號是否存在
     * 
     * @param string $account 帳號
     * @param ldap_connect $conn LDAP連結物件 
     * @return bool 回傳結果
     */
    public function searchLDAP($account, $conn = null)
    {
        $conn = $this->getLDAPConn($conn);
        $result = ldap_search($conn,"ou=user,dc=upgi,dc=ddns,dc=net","uid=$account");
        $data = ldap_get_entries($conn,$result);
        if ($data['count'] > 0) return true;
        return false;
    }

    /**
     * 薪增LDAP帳號
     * 
     * @param string $uid 帳號
     * @param string $password 密碼
     * @param ldap_connect $conn LDAP物件
     * @return bool 回傳結果
     */
    function addLDAP($uid, $password, $conn = null)
    {
        $bind = $this->LDAPAdmin($conn);
        $set = array();
        $set['objectClass'][0] = 'simpleSecurityObject';
        $set['objectClass'][1] = 'top';
        $set['objectClass'][2] = 'account';
        $hash = $this->hashPassword($password);
        $set['userPassword'] = $hash;
        $add = ldap_add($bind, "uid=$uid,ou=user,dc=upgi,dc=ddns,dc=net", $set);
        return $add;
    }

    /**
     * 修改LDAP密碼
     * 
     * @param string $uid 帳號
     * @param string @password 密碼
     * @param ldap_connect $conn LDAP物件
     * @return bool 回傳結果
     */
    function modifyLDAP($uid, $password, $conn = null)
    {
        $bind = $this->LDAPAdmin($conn);
        $hash = $this->hashPassword($password);
        $set = array('userPassword' => $hash);
        $modify = ldap_modify($bind, "uid=$uid,ou=user,dc=upgi,dc=ddns,dc=net", $set);
        return $modify;
    }

    /**
     * 將密碼以md5格式加密
     * 
     * @param string $password 密碼
     * @return string md5加密密碼
     */
    private function hashPassword($password)
    {
        $hash = '{md5}' . base64_encode(pack('H*', md5($password)));
        return $hash;
    }

    /**
     * 判斷是否ldap連結為null
     * 如果是，則取得ldap連結
     * 
     * @param ldap_connect $conn ldap連結
     * @return ldap_connect ldap連結
     */
    public function getLDAPConn($conn)
    {
        if (is_null($conn)) {
            $conn = $this->connLDAP();
            return $conn;
        }
        return $conn;
    }

    /**
     * 取得檔案base64編碼
     * 
     * @param string $id 檔案id
     * @return string base64編碼
     */
    public function getFile($id)
    {
        $file = $this->file;
        return $file->getFileCode($id);
    }

    /**
     * 取得檔案資訊
     * 
     * @param string $id 檔案id
     * @return File Module
     */
    public function getFileInfo($id)
    {
        $file = $this->file;
        return $file->getFile($id);
    }
    
    /**
     * 將檔案進行base64轉碼存入資料庫中，並回傳對應id
     * 
     * @param object $data 檔案物件
     * @return string 檔案id
     */
    public function saveFile($data)
    {
        $name = $data->getClientOriginalName();
        $fe = $data->getClientOriginalExtension();
        $type = $data->getMimeType();
        $file = $this->file;
        $id = $this->getNewGUID();
        $result = $file->saveFile($data, $type, $name, $fe, $id);
        if ($result) {
            return $id;
        } else {
            return null;
        }
    }
}