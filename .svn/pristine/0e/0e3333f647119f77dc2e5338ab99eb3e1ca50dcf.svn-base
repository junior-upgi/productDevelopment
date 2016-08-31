<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

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
use App\Models\sales\Client;

class ServerData extends Controller
{
    public static function getStaffDetail($ERPStaffID = "")
    {
        $Staff = new ERPStaffNode();
        
        if ($ERPStaffID === "") {
            $List = $Staff
                ->with(['mapping.superivisor', 'mapping.primaryDelegate', 'mapping.secondaryDelegate'])
                ->orderBy('ID');
                //->paginate(15);
                //->get();
        } else {
            $List = $Staff
                ->with(['mapping.superivisor', 'mapping.primaryDelegate', 'mapping.secondaryDelegate'])
                ->where('ID', $ERPStaffID)
                ->orderBy('ID');
                //->get();
        }
        
        return $List;
    }
    public static function getStaffList($NodeID = "")
    {
        $StaffList = new ERPStaffNode();
        if ($NodeID === "") {
            $List = $StaffList->orderBy('ID');
        } else {
            $List = $StaffList
                ->where('nodeID', $NodeID)
                ->orderBy('ID');
        }
        return $List;
    }
    public static function getNodeAll()
    {
        $Node = new Node();
        $List = $Node->orderBy('ID')->get();
        return $List;
    }
    public static function getClientAll()
    {   
        $Client = new Client();
        $List = $Client->orderBy('name')->get();
        return $List;
    }
    public static function getPriorityLevel()
    {
        $para = new Para();
        $priorityLevelList = $para
            ->where('paracode','priorityLevel')
            ->get();
        return $priorityLevelList;
    }
    public static function getPhase()
    {
        $Phase = new Para();
        $PhaseList = $Phase
            ->where('paracode', 'ProcessPhaseID')
            ->orderBy('paracodeno')
            ->get();
        return $PhaseList;
    }
}