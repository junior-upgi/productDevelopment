<?php
namespace App\Repositories\mobileMessagingSystem;

use DB;
use App\Http\Controllers\Common;
use Carbon\Carbon;

use App\Models\companyStructure\Relationship;
use App\Models\companyStructure\vStaffRelationship;
use App\Models\companyStructure\Staff;
use App\Models\companyStructure\VStaff;
use App\Models\companyStructure\Node;
use App\Models\UPGWeb\ERPNode;
use App\Models\UPGWeb\ERPStaff;
use App\Models\UPGWeb\ERPStaffNode;
use App\Models\mobileMessagingSystem\BroadcastStatus;
use App\Models\mobileMessagingSystem\Message;
use App\Models\mobileMessagingSystem\MessageCategory;
use App\Models\mobileMessagingSystem\SystemCategory;
use App\Models\mobileMessagingSystem\VBroadcastList;

class MobileRepositories
{
    public $common;
    public $relationship;
    public $vStaffRelationship;
    public $staff;
    public $vStaff;
    public $node;
    public $erpNode;
    public $erpStaff;
    public $erpStaffNode;
    public $broadcastStatus;
    public $message;
    public $messageCategory;
    public $systemcategory;
    public $vBroadcastList;

    public function __construct(
        Common $common,
        Relationship $relationship,
        VStaffRelationship $vStaffRelationship,
        Staff $staff,
        VStaff $vStaff,
        Node $node,
        ERPNode $erpNode,
        ERPStaff $erpStaff,
        ERPStaffNode $erpStaffNode,
        BroadcastStatus $broadcastStatus,
        Message $message,
        MessageCategory $messageCategory,
        SystemCategory $systemcategory,
        VBroadcastList $vBroadcastList
    ) {
        $this->common = $common;
        $this->relationship = $relationship;
        $this->vStaffRelationship = $vStaffRelationship;
        $this->staff = $staff;
        $this->vStaff = $vStaff;
        $this->node = $node;
        $this->erpNode = $erpNode;
        $this->erpStaff = $erpStaff;
        $this->erpStaffNode = $erpStaffNode;
        $this->broadcastStatus = $broadcastStatus;
        $this->message = $message;
        $this->messageCategory = $messageCategory;
        $this->systemcategory = $systemcategory;
        $this->vBroadcastList = $vBroadcastList;
    }

    public function insertNotify(
        $title, $content, $mid, $sid, $uid,
        $recipient, $url, $audioFile, 
        $projectID = '', $productID = '', $processID = ''
    ) {
        $messageID = $this->common->getNewGUID();
        $broadcastID = $this->common->getNewGUID(); 
        $messageParams = array(
            'ID' => $messageID,
            'messageCategoryID' => $mid,
            'systemCategoryID' => $sid,
            'manualTopic' => $title,
            'content' => $content,
            'userID' => $uid,
            'projectID' => $projectID,
            'projectContentID' => $productID,
            'projectProcessID' => $processID,
        );
        try {
            $this->message->getConnection()->beginTransaction();
            $this->broadcastStatus->getConnection()->beginTransaction();
            $this->message->insert($messageParams);
            $recipient = (array)$recipient;
            for($i = 0; $i < count($recipient); $i++) {
                $primary = ($i == 0) ? '1' : '0';
                $broadcastParams = array(
                    'ID' => $broadcastID,
                    'messageID' => $messageID,
                    'recipientID' => $recipient[$i],
                    'primaryRecipient' => $primary,
                    'url' => $url,
                    'audioFile' => $audioFile,
                    'notificationStatus' => '0',
                );
                $this->broadcastStatus->insert($broadcastParams);
            }
            $this->message->getConnection()->commit();
            $this->broadcastStatus->getConnection()->commit();
            return array(
                'success' => true,
                'msg' => '完成訊息寫入',
                'broadcastID' => $broadcastID,
            );
        } catch (\PDOException $e) {
            $this->message->getConnection()->rollback();
            $this->broadcastStatus->getConnection()->rollback();
            return array(
                'success' => false,
                'msg' => $e['errorInfo'][2],
                'broadcastID' => '',
            );
        }
    }
    private function insertData($table, $params, $primaryKey = 'ID')
    {
        return $this->common->insertData($table, $params, $primaryKey);
    }
    public function updateData($table, $params, $id, $primaryKey = 'ID')
    {
        return $this->common->updateData($table, $params, $id, $primaryKey);
    }
    public function deleteData($table, $id, $primaryKey = 'ID')
    {
        return $this->common->deleteData($table, $id, $primaryKey);
    } 
}