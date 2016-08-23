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
    public static function getStaff($ERPStaffID = "")
    {
        //$Staff = new TStaff();
        $Staff = new ERPStaffNode();
        //$List = $s->with(['mapping.superivisor', 'mapping.primaryDelegate', 'mapping.secondaryDelegate'])->get();
        //$List = $s->with('superivisor')->get();
        
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
}