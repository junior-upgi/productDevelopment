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

use App\Repositories\sales\ClientRepository;
use App\Repositories\companyStructure\StaffRepository;
use App\Repositories\UPGWeb\MemberRepository;

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
    /** @var ClientRepository 注入ClientRepository */
    private $clientRepository;
    /** @var StaffRepository 注入StaffRepository */
    private $staffRepository;
    /** @var MemberRepository 注入MemberRepository */
    private $memberRepository;
    /** @var ERPStaff 注入ERPStaff */
    private $erpStaff;
    /** @var ERPStaffNode 注入ERPStaffNode */
    private $erpStaffNode;

    /**
     * 建構式
     *
     * @param MemberRepository $memberRepository
     * @param ClientRepository $clientRepository
     * @param StaffRepository $staffRepository
     * @param ERPStaff $erpStaff
     * @param ERPStaffNode $erpStaffNode
     * @return void
     */
    public function __construct(
        MemberRepository $memberRepository,
        ClientRepository $clientRepository,
        StaffRepository $staffRepository,
        ERPStaff $erpStaff,
        ERPStaffNode $erpStaffNode
    ) {
        $this->clientRepository = $clientRepository;
        $this->staffRepository = $staffRepository;
        $this->memberRepository = $memberRepository;
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
        return $this->staffRepository->getAllNode();
    }
    
    /**
     * 取得廠商清單
     * 
     * @return Client 回傳Client Model
     */
    public function getAllClient()
    {   
        return $this->clientRepository->getAllClient();
    }
    
    /**
     * 由單位ID取得員工清單
     * 
     * @return VStaff 回傳VStaff Model
     */
    public function getStaffByNodeID($nodeID)
    {
        return $this->staffRepository->getStaffByNodeID($nodeID);
    }
    
    /**
     * 由erpID取得使用者資料
     * 
     * @return User 回傳User Model
     */
    public function getUserByerpID($erpID)
    {
        return $this->staffRepository->getUser($erpID);
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
        return $this->memberRepository->checkPersonal($ID, $personalID);
    }
}