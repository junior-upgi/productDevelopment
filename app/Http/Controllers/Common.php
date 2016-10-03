<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use DB;
use Auth;
use App\Models\upgiSystem\User;
use App\Models\upgiSystem\File;

class Common
{
    public $DB;
    public $user;
    public $file;

    public function __construct(
        DB $DB,
        User $user,
        File $file
    ) {
        $this->DB = $DB;
        $this->user = $user;
        $this->file = $file;
    }
    public function transaction()
    {
        $this->DB->beginTransaction();
    }
    public function commit()
    {
        $this->DB->commit();
    }
    public function rollback()
    {
        $this->DB->rollback();
    }
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

    public function insertData($table, $params, $primaryKey = 'ID')
    {
        try {
            $table->getConnection()->beginTransaction();
            /*
            if (is_array($table) && is_array($params) && is_array($primaryKey)) {
                //雙寫入
                //******************** 未完成
                if (count($table) === count($params) && count($table) ===  count($primaryKey)) {
                    for ($i = 0 ; $i < count($table) ; $i++) {
                        if ($i == 0) {
                            $newID = $this->common->getNewGUID();
                        } else {
                            $newID = $this->common->getNewGUID();
                        }
                        $t = $table[i];
                        $p = $params[i];
                        $k = $primaryKey[i];
                        
                    }
                } else {
                    return array(
                        'success' => false,
                        'msg' => '資料設定錯誤',
                    );
                }
            } elseif (is_array($table) || is_array($params) || is_array($primaryKey)) {
                //資料設定錯誤
                return array(
                    'success' => false,
                    'msg' => '資料設定錯誤',
                );
            } else {
                //單寫入
                $newID = $this->common->getNewGUID();
                $params[$primaryKey] = $newID;
                $t = $this->getTable($table);
                $t->insert($params);
            }
            */
            $newID = $this->getNewGUID();
            $params[$primaryKey] = $newID;
            $table->insert($params);
            $table->getConnection()->commit();
            //$this->commit();
            return array(
                'success' => true,
                'msg' => '新增成功',
            );
        } catch (\PDOException $e) {
            $table->getConnection()->rollback();
            $this->rollback();
            return array(
                'success' => false,
                'msg' => $e['errorInfo'][2],
            );
        }
    }
    public function updateData($table, $params, $id, $primaryKey = 'ID')
    {
        try {
            $table->getConnection()->beginTransaction();
            $table->where($primaryKey, $id)->update($params);
            $table->getConnection()->commit();
            return array(
                'success' => true,
                'msg' => '編輯成功',
            );
        } catch (\PDOException $e) {
            $table->getConnection()->rollback();
            return array(
                'success' => false,
                'msg' => $e['errorInfo'][2],
            );
        }
    }
    public function deleteData($table, $id, $primaryKey = 'ID')
    {
        try {
            $table->getConnection()->beginTransaction();
            $table->where($primaryKey, $id)->delete();
            $table->getConnection()->commit();
            return array(
                'success' => true,
                'msg' => '刪除成功',
            );
        } catch (\PDOException $e) {
            $table->getConnection()->rollback();
            return array(
                'success' => false,
                'msg' => $e['errorInfo'][2],
            );
        }
    }
    public function singleSignOn($account, $password, $type)
    {
        switch ($type) {
            case 0:
                return $this->upgiDB($account, $password);
                break;
            case 1:
                return $this->upgiLDAP($account, $password);
                break;
            default:
                return false;
        }
        return false;
    }
    public function upgiDB($account, $password)
    {
        return Auth::attempt([
                'mobileSystemAccount' => $account,
                'password' => $password,
            ], true);
    }
    public function upgiLDAP($account, $password)
    {
        $ldapconn = $this->connLDAP();
        if ($ldapconn) {
            try {
                $ldapbind = $this->checkLDAP($account, $password);
            } catch (\Exception $e) {
                return false;
            }
            //return $ldapbind;
            if ($ldapbind && $this->userLogin($account)) {
                return true;
            } else {
                return false;
            }
        }
    }
    public function userLogin($account)
    {
        $auth = $this->user->where('mobileSystemAccount', $account)->first();
        if ($auth) {
            Auth::loginUsingId($auth->ID);
            return true;
        } else {
            return false;
        }
    }
    public function connLDAP()
    {
        $ldaphost = "192.168.168.86";  // your ldap servers
        $ldapport = 389;                 // your ldap server's port number 
        // Connecting to LDAP
        $ldapconn = ldap_connect($ldaphost, $ldapport) or die("con't connect LDAP");
        ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
        return $ldapconn;
    }
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
    //新增LDAP資料
    function addLDAP($uid, $password, $conn = null)
    {
        $conn = $this->getLDAPConn($conn);
        $adminac = env('LDAP_ADMIN', '');
        $adminpw = env('LDAP_PW', '');
        ldap_bind($conn, $adminac, $adminpw);
        $set = array('userPassword' => $password);
        return ldap_add($this->_ldapconn, "uid=".$uid.",ou=user,dc=upgi,dc=ddns,dc=net", $password);
    }

    //修改LDAP資料
    function modifyLDAP($uid, $password, $conn = null)
    {
        $conn = $this->getLDAPConn($conn);
        $adminac = env('LDAP_ADMIN', '');
        $adminpw = env('LDAP_PW', '');
        ldap_bind($conn, $adminac, $adminpw);
        $set = array('userPassword' => $password);
        return ldap_modify($conn, "uid=".$uid.",ou=user,dc=upgi,dc=ddns,dc=net", $password);
    }

    public function getLDAPConn($conn)
    {
        if (is_null($conn)) $conn = $this->connLDAP();
    }

    public function getFile($id)
    {
        $file = $this->file;
        return $file->getFileCode($id);
    }
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