<?php

namespace App\Http\Controllers\SystemManagement;

//use Class
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Controllers\Common;
use App\Http\Controllers\ServerData;
use Illuminate\Support\Str;

use DB;
use App\Models;
use App\Models\companyStructure\VStaff;
use App\Models\companyStructure\Staff;
use App\Models\companyStructure\Node;
use App\Models\sales\Client;
use App\Models\upgiSystem\User;
use App\Models\upgiSystem\UserGroup;
use App\Models\upgiSystem\UserGroupMembership;
use App\Models\UPGWeb\erpClient;
use App\Models\UPGWeb\erpNode;
use App\Models\UPGWeb\erpStaff;
use App\Models\UPGWeb\ERPStaffNode;
use App\Models\UPGWeb\Test;

class StaffController extends Controller
{
    public function StaffList(Request $request)
    {
        $Search = $request->input('Search');
        $StaffList = ServerData::getStaff();
        $s = new ERPStaffNode();
        $Search = iconv("UTF-8", "BIG-5", $Search);
        
        if ($Search != "") {
            $StaffList = $StaffList
                ->where('nodeID', 'like', "%{$Search}%")
                ->orWhere('name', 'like', "%{$Search}%")
                ->orWhere('nodeName', 'LIKE', "%{$Search}%")
                ->orWhere('position', 'LIKE', "%{$Search}%");
        }

        return view('Staff.StaffList')->with('StaffList', $StaffList->paginate(15));
    }

    public function ImportStaff()
    {

    }
}

