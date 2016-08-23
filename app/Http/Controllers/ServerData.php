<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use DB;
use App\Models\companyStructure\TStaff;
use App\Models\companyStructure\Staff;
use App\Models\UPGWeb\ERPNode;
use App\Models\UPGWeb\ERPStaff;
use App\Models\UPGWeb\ERPStaffNode;

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
}