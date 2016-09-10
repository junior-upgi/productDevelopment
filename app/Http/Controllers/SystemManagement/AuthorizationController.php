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
use App\Models\upgiSystem\Side;
use App\Models\upgiSystem\SideRule;
use App\Models\upgiSystem\System;

class AuthorizationController extends Controller
{
    public function sideList(Request $request)
    {
        $setSystem = $request->input('setSystem');
        $System = new System();
        $System = $System->get();
        $Side = new Side();

        return view('System.Side.SideList')
            ->with('SideList', $Side->getBySystem($setSystem[0])->paginate(20))
            ->with('SystemList', $System)
            ->with('setSystem', $setSystem[0]);
    }

    public function insertSide(Request $request)
    {
        try {
            DB::beginTransaction();
            $SideID = Common::getNewGUID();
            $SideName = $request->input('sideName');
            $SystemID = $request->input('systemID');
            $ParentID = $request->input('parentID');
            $Route = $request->input('route');
            $Yield = $request->input('yield');
            $Side = new Side();
            $Params = array(
                'ID' => $SideID,
                'sideName' => $SideName,
                'system' => $SystemID,
                'parentID' => $ParentID,
                'route' => $Route,
                'yield' => $Yield,
                'seq' => ($Side->maxSeq($SystemID) + 1),
            );
            $Side->insert($Params);
            DB::commit();
            
            $jo = array(
                'success' => true,
                'msg' => '新增功能成功!',
            );
        } catch (\PDOException $e) {
            DB::rollback();
            $jo = array(
                'success' => false,
                'msg' => $e,
            );
        }

        return $jo;
    }

    public function getParentList($SystemID, $SideID = '')
    {
        $Parent = new Side();
        if ($SideID === '') {
            $Parent = $Parent->where('system', $SystemID)->orderBy('seq')->get();
        } else {
            $Parent = $Parent->where('ID', '<>', $SideID)->where('system', $SystemID)->orderBy('seq')->get();
        }
        return $Parent;
    }
    
    public function getSideData($SideID)
    {
        $Side = new Side();
        $Side = $Side->where('ID', $SideID)->with(['getSystem', 'getParent'])->first();
        if ($Side) {
            $jo = array(
                'success' => true,
                'SideData' => $Side,
            );
        } else {
            $jo = array(
                'success' => false,
            );
        }
        return $jo;
    }

    public function updateSide(Request $request)
    {
        try {
            DB::beginTransaction();
            $SideID = $request->input('sideID');
            $SideName = $request->input('sideName');
            $SystemID = $request->input('systemID');
            $ParentID = $request->input('parentID');
            $Route = $request->input('route');
            $Yield = $request->input('yield');
            $Side = new Side();
            $Side = $Side->where('ID', $SideID);
            $Params = array(
                'ID' => $SideID,
                'sideName' => $SideName,
                'system' => $SystemID,
                'parentID' => $ParentID,
                'route' => $Route,
                'yield' => $Yield,
            );
            $Side->update($Params);
            DB::commit();
            
            $jo = array(
                'success' => true,
                'msg' => '新增功能成功!',
            );
        } catch (\PDOException $e) {
            DB::rollback();
            $jo = array(
                'success' => false,
                'msg' => $e,
            );
        }

        return $jo;
    }
    public function deleteSide($SideID)
    {
        $Side = new Side();
        try {
            DB::beginTransaction();
            $Side = $Side->where('ID', $SideID);
            $Side->delete();
            DB::commit();
            $jo = array(
                'success' => true,
                'msg' => '刪除開發案成功!',
            );
        } catch (\PDOException $e) {
            DB::rollback();
            $jo = array(
                'success' => false,
                'msg' => $e,
            );
        }

        return $jo;
    }

    public function getToolBar($System)
    {
        
    }
}