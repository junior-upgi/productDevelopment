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
use App\Models\companyStructure\Relationship;
use App\Models\sales\Client;
use App\Models\upgiSystem\User;
use App\Models\upgiSystem\UserGroup;
use App\Models\upgiSystem\UserGroupMembership;
use App\Models\UPGWeb\erpClient;
use App\Models\UPGWeb\erpNode;
use App\Models\UPGWeb\erpStaff;
use App\Models\UPGWeb\ERPStaffNode;

class UserController extends Controller
{
    public function sideList()
    {

    }


}