<?php

namespace App\Http\Controllers\ProductDevelopment;

//use Class
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Controllers\Common;

//use DB
use App\Models\productDevelopment\para;
use App\Models\productDevelopment\project;
use App\Models\productDevelopment\vProjectList;
use App\Models\productDevelopment\vProcessList;
use App\Models\productDevelopment\projectContent;
use App\Models\productDevelopment\projectProcess;
use App\Models\productDevelopment\processTree;
use App\Models\companyStructure\vStaff;
use App\Models\companyStructure\staff;
use App\Models\companyStructure\node;
use App\Models\sales\client;
//use App\Models;

use DB;

class ProcessController extends Controller
{
    public function ProcessList($ProductID)
    {
        $ProjectContent = new projectContent();
        $ProductData = $ProjectContent
            ->where('ID', $ProductID)
            ->get()
            ->first();

        $Process = new vProcessList();
        $ProcessList = $Process
            ->where('projectContentID', $ProductID)
            ->orderBy('seq', 'asc')->orderBy('created')
            ->get();

        $oNode = new node();
        $NodeList = $oNode
            ->orderBy('id')
            ->get();

        $Phase = new para();
        $PhaseList = $Phase
            ->where('paracode', 'ProcessPhaseID')
            ->orderBy('paracodeno')
            ->get();

        return view('Process.ProcessList')
            ->with('ProductData', $ProductData)
            ->with('ProcessList', $ProcessList)
            ->with('NodeList', $NodeList)
            ->with('PhaseList', $PhaseList);
    }

    public function InsertProcess(Request $request)
    { 
        $ProjectContentID = $request->input('ProductID');
        $ProcessNumber = $request->input('ProcessNumber');
        $ProcessName = $request->input('ProcessName');
        $PhaseID = $request->input('PhaseID');
        $TimeCost = $request->input('TimeCost');
        $StaffID = $request->input('StaffID');

        try {
            DB::beginTransaction();
            $ProcessID = Common::GetNewGUID();
            $Params = array(
                'ID' => $ProcessID,
                'projectContentID' => $ProjectContentID,
                'referenceName' => $ProcessName,
                'referenceNumber' => $ProcessNumber,
                'projectProcessPhaseID' => $PhaseID,
                'timeCost' => $TimeCost,
                'staffID' => $StaffID,
            );
            $ProjectProcess = new projectProcess();
            $ProjectProcess->insert($Params);

            $MaxIndex = new processTree();
            $MaxIndex = ($MaxIndex
                ->where('projectContentID', $ProjectContentID)
                ->max('seq')) + 1;
            
            $Params = array(
                'projectContentID' => $ProjectContentID,
                'projectProcessID' => $ProcessID,
                'treeLevel' => 0,
                'seq' => $MaxIndex,
            );
            $ProcessTree = new processTree();
            $ProcessTree->insert($Params);
            
            
            DB::commit();

            $Phase = new para();
            $PhaseName = $Phase
                ->where('paracode', 'ProcessPhaseID')
                ->where('paracodeno', $PhaseID)
                ->first();
            
            $vStaff = new vStaff();
            $StaffData = $vStaff
                ->where('id', $StaffID)
                ->first();
            
            $jo = array(
                'success' => true,
                'msg' => '新增開發產品成功!',
                'ProcessID' => $ProcessID,
                'PhaseName' => $PhaseName->paracodename,
                'ProcessName' => $ProcessName,
                'ProcessNumber' => $ProcessNumber,
                'TimeCost' => $TimeCost,
                'NodeName' => $StaffData->reference,
                'StaffName' => $StaffData->name,
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

    public function GetProcessData($ProcessID)
    {
        $Process = new vProcessList();
        $ProcessData = $Process
            ->where('ID', $ProcessID)
            ->first();
        $StaffList = new staff();
        $StaffList = $StaffList
            ->where('nodeID', $ProcessData->nodeID)
            ->get();
        $jo = array();
        if($ProcessData && $StaffList)
        {
            $jo = array(
                'success' => true,
                'ID' => $ProcessData->ID,
                'ProcessNumber' => $ProcessData->referenceNumber,
                'ProcessName' => $ProcessData->referenceName,
                'PhaseID' => $ProcessData->projectProcessPhaseID,
                'TimeCost' => $ProcessData->timeCost,
                'StaffID' => $ProcessData->staffID,
                'NodeID' => $ProcessData->nodeID,
                'StaffList' => $StaffList,
            );
        }
        else
        {
            $jo = array(
                'success' => false,
                'msg' => '找不到程序資訊!',
            );
        }
        return $jo;
    }

    public function SaveProcessSort(Request $request)
    {
        $Data = $request->json()->all();
        try
        {
            DB::beginTransaction();
            
            foreach($Data as $list)
            {
                $ProcessTree = new processTree();
                $ProcessTree = $ProcessTree
                    ->where('projectProcessID', $list['pid']);
                $Params = array(
                    'seq' => $list['index'],
                );
                $ProcessTree->update($Params);
            }
            DB::commit();
            $jo = array(
                'success' => true,
                'msg' => '儲存排序成功',
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

    public function UpdateProcess()
    {

    }
}