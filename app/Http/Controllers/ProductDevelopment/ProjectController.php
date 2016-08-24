<?php

namespace App\Http\Controllers\ProductDevelopment;

//use Class
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Controllers\Common;
use App\Http\Controllers\ServerData;

//use DB
use DB;
use App\Models;
use App\Models\productDevelopment\Project;
use App\Models\productDevelopment\VProjectList;
use App\Models\productDevelopment\ProjectContent;
use App\Models\companyStructure\VStaff;
use App\Models\companyStructure\Staff;
use App\Models\companyStructure\Node;
use App\Models\sales\Client;

class ProjectController extends Controller
{
    public function projectList()
    {
        $oProject = new VProjectList();
        $ProjectList = $oProject
            ->orderBy('created', 'desc')
            ->paginate(15);

        return view('Project.ProjectList')
            ->with('ProjectList', $ProjectList);
    }

    public function addProject()
    {
        $ClientList = ServerData::getClientAll();

        $NodeList = ServerData::getNodeAll();

        return view('Project.AddProject')
            ->with('ClientList', $ClientList)
            ->with('NodeList', $NodeList);
    }

    public function insertProject(Request $request)
    {
        $ProjectNumber = $request->input('referenceNumber');
        $ProjectName = $request->input('ProjectName');
        $ClientID = $request->input('ClientID');
        $SalesID = $request->input('SalesID');

        //$aa = new UPGICommon();
        try {
            DB::beginTransaction();

            $oProject = new Project();
            $oProject->ID = Common::getNewGUID();
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
        } catch (\PDOException $e) {
            DB::rollback();
            $jo = array(
                'success' => false,
                'msg' => $e,
            );
        }

        return $jo;
    }
    
    public function editProject($ProjectID)
    {
        $oProject = new VProjectList();
        $ProjectData = $oProject
            ->where('ID','=',$ProjectID)
            ->first();
        
        $oProjectContent = new ProjectContent();
        $ProjectContent = $oProjectContent
            ->where('projectID','=',$ProjectID)
            ->get();
        
        $ClientList = ServerData::getClientAll();

        $NodeList = ServerData::getNodeAll();

        $oStaff = new Staff();
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
    
    public function updateProject(Request $request)
    {
        $params = array();
        $ProjectID = $request->input('ProjectID');
        $params['referenceNumber'] = $request->input('referenceNumber');
        $params['referenceName'] = $request->input('ProjectName');
        $params['clientID'] = $request->input('ClientID');
        $params['salesID'] = $request->input('SalesID');

        try {
            DB::beginTransaction();

            $oProject = new Project();
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
        } catch (\PDOException $e) {
            DB::rollback();
            $jo = array(
                'success' => false,
                'msg' => $e,
            );
        }
        
        return $jo;
    }
    
    public function getStaffByNodeID($NodeID)
    {
        $Staff = new Staff();
        $StaffList = $Staff->where('nodeID', $NodeID)->get();
        return $StaffList;
    }

    public function deleteProject($ProjectID)
    {
        $Project = new Project();
        try {
            DB::beginTransaction();

            $Project->where('ID', $ProjectID)->delete();
            
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

    public function phpinfo()
    {
        phpinfo();
    }
}