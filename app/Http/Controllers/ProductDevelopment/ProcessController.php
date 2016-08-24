<?php

namespace App\Http\Controllers\ProductDevelopment;

//use Class
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Controllers\Common;
use App\Http\Controllers\ServerData;

use DB;
use App\Models;
use App\Models\productDevelopment\Para;
use App\Models\productDevelopment\Project;
use App\Models\productDevelopment\VProjectList;
use App\Models\productDevelopment\VProcessList;
use App\Models\productDevelopment\ProjectContent;
use App\Models\productDevelopment\ProjectProcess;
use App\Models\productDevelopment\ProcessTree;
use App\Models\companyStructure\VStaff;
use App\Models\companyStructure\Staff;
use App\Models\companyStructure\Node;
use App\Models\sales\Client;
//use App\Models;

class ProcessController extends Controller
{
    public function processList($ProductID)
    {
        $ProjectContent = new ProjectContent();
        $ProductData = $ProjectContent
            ->where('ID', $ProductID)
            ->first();

        $Process = new VProcessList();
        $ProcessList = $Process
            ->where('projectContentID', $ProductID)
            ->orderBy('seq', 'asc')->orderBy('created')
            ->paginate(15);

        $NodeList = ServerData::getNodeAll();

        $PhaseList = ServerData::getPhase();

        return view('Process.ProcessList')
            ->with('ProductData', $ProductData)
            ->with('ProcessList', $ProcessList)
            ->with('NodeList', $NodeList)
            ->with('PhaseList', $PhaseList);
    }

    public function insertProcess(Request $request)
    { 
        $ProjectContentID = $request->input('ProductID');
        $ProcessNumber = $request->input('ProcessNumber');
        $ProcessName = $request->input('ProcessName');
        $PhaseID = $request->input('PhaseID');
        $TimeCost = $request->input('TimeCost');
        $StaffID = $request->input('StaffID');

        try {
            DB::beginTransaction();
            $ProcessID = Common::getNewGUID();
            $Params = array(
                'ID' => $ProcessID,
                'projectContentID' => $ProjectContentID,
                'referenceName' => $ProcessName,
                'referenceNumber' => $ProcessNumber,
                'projectProcessPhaseID' => $PhaseID,
                'timeCost' => $TimeCost,
                'staffID' => $StaffID,
            );
            $ProjectProcess = new ProjectProcess();
            $ProjectProcess->insert($Params);

            $MaxIndex = new ProcessTree();
            $MaxIndex = ($MaxIndex
                ->where('projectContentID', $ProjectContentID)
                ->max('seq')) + 1;
            
            $Params = array(
                'projectContentID' => $ProjectContentID,
                'projectProcessID' => $ProcessID,
                'treeLevel' => 0,
                'seq' => $MaxIndex,
            );
            $ProcessTree = new ProcessTree();
            $ProcessTree->insert($Params);
            
            DB::commit();
            
            $jo = array(
                'success' => true,
                'msg' => '新增程序成功!',
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

    public function getProcessData($ProcessID)
    {
        $Process = new VProcessList();
        $ProcessData = $Process
            ->where('ID', $ProcessID)
            ->first();
        $StaffList = new Staff();
        $StaffList = $StaffList
            ->where('nodeID', $ProcessData->nodeID)
            ->get();
        $jo = array();
        if ($ProcessData && $StaffList) {
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
        } else {
            $jo = array(
                'success' => false,
                'msg' => '找不到程序資訊!',
            );
        }

        return $jo;
    }

    public function saveProcessSort(Request $request)
    {
        $Data = $request->json()->all();
        try
        {
            DB::beginTransaction();
            
            foreach($Data as $list)
            {
                $ProcessTree = new ProcessTree();
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

    public function updateProcess(Request $request)
    {
        $ProjectContentID = $request->input('ProductID');
        $ProjectProcessID = $request->input('ProcessID');
        $ProcessNumber = $request->input('ProcessNumber');
        $ProcessName = $request->input('ProcessName');
        $PhaseID = $request->input('PhaseID');
        $TimeCost = $request->input('TimeCost');
        $StaffID = $request->input('StaffID');

        try {
            DB::beginTransaction();
            $Params = array(
                'referenceName' => $ProcessName,
                'referenceNumber' => $ProcessNumber,
                'projectProcessPhaseID' => $PhaseID,
                'timeCost' => $TimeCost,
                'staffID' => $StaffID,
            );
            $ProjectProcess = new ProjectProcess();
            $ProjectProcess = $ProjectProcess
                ->where('ID', $ProjectProcessID);
            $ProjectProcess->update($Params);

            DB::commit();
            
            $jo = array(
                'success' => true,
                'msg' => '更新程序成功!',
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
    
    public function processComplete($ProcessID)
    {
        $jo = array();
        try {
            DB::beginTransaction();
            $Process = new ProjectProcess();
            $Process = $Process
                ->where('ID', $ProcessID);
            $Params = array(
                'complete' => '1',
                'completeTime' => Carbon::now(),
            );
            $Process->update($Params);

            DB::commit();
            
            $jo = array(
                'success' => true,
                'msg' => '完成程序!',
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
}