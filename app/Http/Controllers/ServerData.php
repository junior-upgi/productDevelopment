<?php
/**
 * 系統資料共用方法
 *
 * @version 1.0.0
 * @author spark it@upgi.com.tw
 * @date 16/10/19
 * @since 1.0.0 spark: 於此版本開始編寫註解
 */
namespace App\Http\Controllers;

use App\Repositories\sales\ClientRepositories;
use App\Repositories\companyStructure\StaffRepositories;
use App\Repositories\UPGWeb\MemberRepositories;

use DB;
use App\Models\companyStructure\Relationship;
use App\Models\companyStructure\vStaffRelationship;
use App\Models\companyStructure\Staff;
use App\Models\companyStructure\VStaff;
use App\Models\companyStructure\Node;
use App\Models\UPGWeb\ERPNode;
use App\Models\UPGWeb\ERPStaff;
use App\Models\UPGWeb\ERPStaffNode;
use App\Models\productDevelopment\Project;
use App\Models\productDevelopment\VProjectList;
use App\Models\productDevelopment\ProjectContent;
use App\Models\productDevelopment\Para;

/**
 * Class ServerData
 *
 * @package App\Http\Controllers
 */
class ServerData
{
    /** @var ClientRepositories 注入ClientRepositories */
    private $clientRepositories;
    /** @var StaffRepositories 注入StaffRepositories */
    private $staffRepositories;
    /** @var MemberRepositories 注入MemberRepositories */
    private $memberRepositories;
    /** @var ERPStaff 注入ERPStaff */
    private $erpStaff;
    /** @var ERPStaffNode 注入ERPStaffNode */
    private $erpStaffNode;

    /**
     * 建構式
     *
     * @param MemberRepositories $memberRepositories
     * @param ClientRepositories $clientRepositories
     * @param StaffRepositories $staffRepositories
     * @param ERPStaff $erpStaff
     * @param ERPStaffNode $erpStaffNode
     * @return void
     */
    public function __construct(
        MemberRepositories $memberRepositories,
        ClientRepositories $clientRepositories,
        StaffRepositories $staffRepositories,
        ERPStaff $erpStaff,
        ERPStaffNode $erpStaffNode
    ) {
        $this->clientRepositories = $clientRepositories;
        $this->staffRepositories = $staffRepositories;
        $this->memberRepositories = $memberRepositories;
        $this->erpStaff = $erpStaff;
        $this->erpStaffNode = $erpStaffNode;
    }

    /**
     * 取得員工詳細資料，結合整合superivisor，primaryDelegate，secondaryDelegate
     * 
     * @param string $ERPStaffID 員工erp編號
     * @return ERPStaff 回傳查詢結果
     */
    public function getStaffDetail($ERPStaffID = "")
    {
        $Staff = $this->erpStaffNode;
        if ($ERPStaffID == "") {
            $List = $Staff
                ->with(['mapping.superivisor', 'mapping.primaryDelegate', 'mapping.secondaryDelegate'])
                ->orderBy('ID');
        } else {
            $List = $Staff
                ->with(['mapping.superivisor', 'mapping.primaryDelegate', 'mapping.secondaryDelegate'])
                ->where('ID', $ERPStaffID)
                ->orderBy('ID');
        }
        return $List;
    }

    /**
     * 取得員工清單
     * 
     * @param string $NodeID 單位erp編號
     * @return ERPStaffNode 回傳查詢結果
     */
    public function getStaffList($NodeID = "")
    {
        $StaffList = $this->erpStaffNode;
        if ($NodeID === "") {
            $List = $StaffList->orderBy('ID');
        } else {
            $List = $StaffList
                ->where('nodeID', $NodeID)
                ->orderBy('ID');
        }
        return $List;
    }
    
    /**
     * 取得單位清單
     * 
     * @return array 回傳結果
     */
    public function getAllNode()
    {
        return $this->staffRepositories->getAllNode();
    }
    
    /**
     * 取得廠商清單
     * 
     * @return Client 回傳Client Model
     */
    public function getAllClient()
    {   
        return $this->clientRepositories->getAllClient();
    }
    
    /**
     * 由單位ID取得員工清單
     * 
     * @return VStaff 回傳VStaff Model
     */
    public function getStaffByNodeID($nodeID)
    {
        return $this->staffRepositories->getStaffByNodeID($nodeID);
    }
    
    /**
     * 由erpID取得使用者資料
     * 
     * @return User 回傳User Model
     */
    public function getUserByerpID($erpID)
    {
        return $this->staffRepositories->getUser($erpID);
    }

    /**
     * 驗證員工個人資訊
     * 
     * @param string $ID 員工編號
     * @param string $personalID 身份證號
     * @return bool 回傳結果
     */
    public function checkPersonal($ID, $personalID)
    {
        return $this->memberRepositories->checkPersonal($ID, $personalID);
    }
}