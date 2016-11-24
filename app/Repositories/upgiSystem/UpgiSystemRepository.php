<?php
/**
 * upgiSystem相關資料邏輯處理
 *
 * @version 1.0.0
 * @author spark it@upgi.com.tw
 * @date 16/10/27
 * @since 1.0.0 spark: 於此版本開始編寫註解
 */
namespace App\Repositories\upgiSystem;

use App\Http\Controllers\Common;
use App\Models\upgiSystem\User;
use App\Models\upgiSystem\UserGroup;
use App\Models\upgiSystem\UserGroupMembership;
use App\Models\upgiSystem\VUserGroupList;

/**
 * Class upgiSystemRepositories
 *
 * @package App\Http\Repositories
 */
class UpgiSystemRepository
{
    /** @var Common 注入Common */
    public $common;
    /** @var User 注入User */
    public $user;
    /** @var UserGroup 注入UserGroup */
    public $userGroup;
    /** @var UserGroupMembership 注入UserGroupMembership */
    public $membership;
    /** @var vUserGroup 注入vUserGroup */
    public $vUser;

    /**
     * 建構式
     *
     * @param Client $client
     * @return void
     */
    public function __construct(
        Common $common,
        User $user,
        UserGroup $group,
        UserGroupMembership $membership,
        VUserGroupList $vUser
    ) {
        $this->common = $common;
        $this->user = $user;
        $this->group = $group;
        $this->membership = $membership;
        $this->vUser = $vUser;
    }

    /**
     * 取得清單
     * 
     * @return Module 回傳Model
     */
    public function getList($table, $where = null)
    {
        $table = $this->getTable($table);
        $obj = $this->common->where($table, $where);
        return $obj;
    }

    public function save($table, $input, $addIgnore = array(), $pk = 'ID')
    {   
        $table = $this->getTable($table);
        $id = $input['id'];
        $type = $input['type'];
        if ($type == 'add') {
            $params = $this->common->params($input, $addIgnore);
            $tran = $this->insert($table, $params);
        } else if ($type == 'edit') {
            $params = $this->common->params($input, $addIgnore);
            $tran = $this->update($table, $id, $params, $pk);
        } else if ($type == 'delete') {
            $tran = $this->delete($table, $id, $pk);
        }
        return $tran;
    }

    public function insert($table, $params)
    {
        $obj = $this->common->insert($table, $params);
        return $obj;
    }

    public function update($table, $id, $params, $pk)
    {
        $table = $table->where($pk, $id);
        $obj = $this->common->update($table, $params);
        return $obj;
    }

    public function delete($table, $id, $pk)
    {
        $table = $table->where($pk, $id);
        $a = $table->get();
        $obj = $this->common->delete($table);
        return $obj;
    }

    public function getTable($table)
    {
        switch ($table) {
            case 'group':
                return $this->group;
                break;
            case 'user':
                return $this->user;
                break;
            case 'membership':
                return $this->membership;
                break;
            case 'vUserGroupList':
                return $this->vUser;
                break;
            default:
                return null;
        }
    }
}
