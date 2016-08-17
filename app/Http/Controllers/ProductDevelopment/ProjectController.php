<?php

namespace App\Http\Controllers\ProductDevelopment;

//use Class
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Controllers\Common;

//use DB
use DB;
use App\Models;
use App\Models\productDevelopment\project;
use App\Models\productDevelopment\vProjectList;
use App\Models\productDevelopment\projectContent;
use App\Models\companyStructure\vStaff;
use App\Models\companyStructure\staff;
use App\Models\companyStructure\node;
use App\Models\sales\client;

class ProjectController extends Controller
{
    public function ProjectList()
    {
        $oProject = new vProjectList();
        $ProjectList = $oProject::all();
        return view('Project.ProjectList')
            ->with('ProjectList', $ProjectList);
    }

    public function AddProject()
    {
        $oClient = new client();
        $ClientList = $oClient
            ->orderBy('reference')
            ->get();

        $oNode = new node();
        $NodeList = $oNode
            ->orderBy('id')
            ->get();

        return view('Project.AddProject')
            ->with('ClientList', $ClientList)
            ->with('NodeList', $NodeList);
    }

    public function InsertProject(Request $request)
    {
        $ProjectNumber = $request->input('referenceNumber');
        $ProjectName = $request->input('ProjectName');
        $ClientID = $request->input('ClientID');
        $SalesID = $request->input('SalesID');

        //$aa = new UPGICommon();
        try {
            DB::beginTransaction();

            $oProject = new project();
            $oProject->ID = Common::GetNewGUID();
            $oProject->referenceName = $ProjectName;
            $oProject->referenceNumber = $ProjectNumber;
            $oProject->clientID = $ClientID;
            $oProject->salesID = $SalesID;
            //$oProject->created = Carbon::now();
            $oProject->save();
            
            DB::commit();
            $jo = array(
                'success' => true,
                'msg' => '新增開發案成功!',
            );
        } 
        catch (\PDOException $e)
        {
            DB::rollback();
            $jo = array(
                'success' => false,
                'msg' => $e,
            );
        }
        return $jo;
    }

    public function GetStaffByNodeID($NodeID)
    {
        $oStaff = new staff();
        $StaffData = $oStaff
            ->where('nodeID','=',$NodeID)
            ->get();
        return $StaffData;
    }

    public function EditProject($ProjectID)
    {
        $oProject = new vProjectList();
        $ProjectData = $oProject
            ->where('id','=',$ProjectID)
            ->get()
            ->first();
        
        $oProjectContent = new projectContent();
        $ProjectContent = $oProjectContent
            ->where('projectID','=',$ProjectID)
            ->get();

        $oClient = new client();
        $ClientList = $oClient
            ->orderBy('reference')
            ->get();

        $oNode = new node();
        $NodeList = $oNode
            ->orderBy('id')
            ->get();

        $oStaff = new staff();
        $StaffList = $oStaff
            ->where('nodeID','=',$ProjectData->nodeID)
            ->get();

        return view('Project.EditProject')
            ->with('ProjectData', $ProjectData)
            ->with('ProjectContent', $ProjectContent)
            ->with('ClientList', $ClientList)
            ->with('NodeList', $NodeList)
            ->with('StaffList', $StaffList);
    }
    
    public function UpdateProject(Request $request)
    {
        $params = array();
        $ProjectID = $request->input('ProjectID');
        $params['referenceNumber'] = $request->input('referenceNumber');
        $params['referenceName'] = $request->input('ProjectName');
        $params['clientID'] = $request->input('ClientID');
        $params['salesID'] = $request->input('SalesID');

        try {
            DB::beginTransaction();

            $oProject = new project();
            $oProject = $oProject
                ->where('ID',$ProjectID);
            
            if($oProject->count() < 1)
            {
                $jo = array(
                    'success' => false,
                    'msg' => '找不到該開發案資訊!',
                );
                return $jo;
            }
            $oProject->update($params);
            
            DB::commit();
            $jo = array(
                'success' => true,
                'msg' => '更新開發案成功!',
            );
        } 
        catch (\PDOException $e)
        {
            DB::rollback();
            $jo = array(
                'success' => false,
                'msg' => $e,
            );
        }
        return $jo;
    }
    
    public function phpinfo()
    {
        phpinfo();
    }
}