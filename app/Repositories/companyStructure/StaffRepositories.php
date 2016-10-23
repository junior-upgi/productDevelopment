<?php
/**
 * staff相關資料邏輯處理
 *
 * @version 1.0.0
 * @author spark it@upgi.com.tw
 * @date 16/10/19
 * @since 1.0.0 spark: 於此版本開始編寫註解
 */
namespace App\Repositories\companyStructure;

use App\Http\Controllers\Common;

use App\Models\companyStructure\Relationship;
use App\Models\companyStructure\VStaffRelationship;
use App\Models\companyStructure\Staff;
use App\Models\companyStructure\VStaff;
use App\Models\companyStructure\Node;
use App\Models\upgiSystem\VUserGroupList;
use App\Models\upgiSystem\User;

/**
 * Class StaffRepositories
 *
 * @package App\Http\Controllers
 */
class StaffRepositories
{
    /** @var Common 注入Common */
    private $common;
    /** @var Relationship 注入Relationship */
    private $relationship;
    /** @var VStaffRelationship 注入VStaffRelationship */
    private $vStaffRelationship;
    /** @var Staff 注入Staff */
    private $staff;
    /** @var VStaff 注入VStaff */
    private $vStaff;
    /** @var Node 注入Node */
    private $node;
    /** @var User 注入User */
    private $user;
    /** @var VUserGroupList 注入VUserGroupList */
    private $vUserGroupList;

    /**
     * 建構式
     *
     * @param Common $common
     * @param Relationship $relationship
     * @param VStaffRelationship $vStaffRelationship
     * @param Staff $staff
     * @param VStaff $vStaff
     * @param Node $node
     * @param User $user
     * @param VUserGroupList $vUserGroupList
     * @return void
     */
    public function __construct(
        Common $common,
        Relationship $relationship,
        VStaffRelationship $vStaffRelationship,
        Staff $staff,
        VStaff $vStaff,
        Node $node,
        User $user,
        VUserGroupList $vUserGroupList
    ) {
        $this->common = $common;
        $this->relationship = $relationship;
        $this->vStaffRelationship = $vStaffRelationship;
        $this->staff = $staff;
        $this->vStaff = $vStaff;
        $this->node = $node;
        $this->user = $user;
        $this->vUserGroupList = $vUserGroupList;
    }

    /**
     * 取得所有單位清單
     * 
     * @return Node 回傳Node Model
     */
    public function getAllNode()
    {
        return $this->node->orderBy('ID')->get();
    }

    /**
     * 由單位ID取得所有員工清單
     * 
     * @return VStaff 回傳VStaff Model
     */
    public function getStaffByNodeID($nodeID)
    {
        return $this->vStaff->where('nodeID', $nodeID)->orderBy('ID')->get();
    }

    /**
     * 取得使用者資料
     * 
     * @param string $id 員工編號
     * @return User 回傳User Model
     */
    public function getUser($id)
    {
        return $this->user->where('mobileSystemAccount', $id)->first();
    }

    /**
     * 新增使用者
     * 
     * @param array $params 新增資料
     * @return array 回傳新增結果
     */
    public function insertUser($params)
    {
        $table = $this->user;
        return $this->common->insertData($table, $params);
    }
}