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

class StaffController extends Controller
{
    public function staffList(Request $request)
    {
        $Search = $request->input('Search');
        $StaffList = ServerData::getStaffDetail();
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

    public function getStaffData($StaffID)
    {
        $StaffData = ServerData::getStaffDetail($StaffID)->first();
        $Node = new ERPNode();
        $NodeList = $Node->orderBy('nodeID')->get();
        $SuperivisorList = array();
        $PrimaryDelegateList = array();
        $SecondaryDelegateList = array();
        if ($StaffData->mapping['superivisor'] != null) {
            $SuperivisorList = ServerData::getStaffList($StaffData->mapping->superivisor->nodeID)->get();
        }
        if ($StaffData->mapping['primaryDelegate'] != null) {
            $PrimaryDelegateList = ServerData::getStaffList($StaffData->mapping->primaryDelegate->nodeID)->get();
        }
        if ($StaffData->mapping['secondaryDelegate'] != null) {
            $SecondaryDelegateList = ServerData::getStaffList($StaffData->mapping->secondaryDelegate->nodeID)->get();
        }
        $jo = array(
            'success' => true,
            'ID' => $StaffData->ID,
            'nodeName' => $StaffData->nodeName,
            'name' => $StaffData->name,
            'position' => $StaffData->position,
            'NodeList' => $NodeList,
            'SuperivisorID' => $StaffData->mapping['superivisor']['ID'],
            'PrimaryDelegateID' => $StaffData->mapping['primaryDelegate']['ID'],
            'SecondaryDelegateID' => $StaffData->mapping['secondaryDelegate']['ID'],
            'SuList' => $SuperivisorList,
            'PrList' => $PrimaryDelegateList,
            'SeList' => $SecondaryDelegateList,
        );
        return $jo;
    }

    public function updateStaff(Request $request)
    {
        $StaffID = $request->input('ID');
        $SuperivisorID = $request->input('SuperivisorID');
        $PrimaryDelegateID = $request->input('PrimaryDelegateID');
        $SecondaryDelegateID = $request->input('SecondaryDelegateID');
        $jo =  array();
        try {
            $Staff = new Relationship();
            $Params = array(
                'StaffID' => $StaffID,
                'SuperivisorID' => $SuperivisorID,
                'PrimaryDelegateID' => $PrimaryDelegateID,
                'SecondaryDelegateID' => $SecondaryDelegateID,
            );
            DB::beginTransaction();
            /*
            $Staff = $Staff->firstOrCreate(array('staffID' => $StaffID));
            $Staff->staffID = $StaffID;
            $Staff->superivisorID = $SuperivisorID;
            $Staff->primaryDelegateID = $PrimaryDelegateID;
            $Staff->secondaryDelegateID = $SecondaryDelegateID;
            $Staff->save();
            */
            if ($Staff->where('staffID', $StaffID)->count() > 0) {
                $Staff->where('staffID', $StaffID)->update($Params);
            } else {
                $Staff->insert($Params);
            }

            DB::commit();
            $jo = array(
                'success' => true,
                'msg' => '員工資料更新成功',
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

    public function getStaffList($NodeID)
    {
        $List = ServerData::getStaffList($NodeID)->get();
        return $List;
    }

    public function moveData()
    {
        $ERPStaff = new ERPStaff();
        $ERPStaff = $ERPStaff->all();
        $ERPNode = new ERPNode();
        $ERPNode = $ERPNode->all();
        $ERPClient = new ERPClient;
        $ERPClient = $ERPClient->all();
        $Staff = new Staff();
        $Staff = $Staff->where('ID', '<>', '');
        $Node = new Node();
        $Node = $Node->where('ID', '<>', '');
        $Client = new Client();
        $Client = $Client->where('ID', '<>', '');
        
        try{
            DB::beginTransaction();
            $Staff->delete();
            $Node->delete();
            $Client->delete();
            foreach($ERPStaff as $list) {
                $Params = array(
                    'ID' => $list->ID,
                    'nodeID' => $list->nodeID,
                    'name' => $list->name,
                    'position' => $list->position
                );
                $Staff->insert($Params);
            }
            foreach($ERPNode as $list) {
                $Params = array(
                    'ID' => $list->nodeID,
                    'parentNodeID' => $list->parentNodeID,
                    'nodeName' => $list->nodeName,
                );
                $Node->insert($Params);
            }
            foreach($ERPClient as $list) {
                $Params = array(
                    'ID' => $list->ID,
                    'area' => $list->area,
                    'parentID' => $list->parentID,
                    'name' => $list->name,
                    'sname' => $list->sname,
                );
                $Client->insert($Params);
            }
            DB::commit();
            $jo = array(
                'success' => true,
                'msg' => '資料遷移成功',
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


    public function importStaff()
    {
        return '尚未完工';
    }
}

