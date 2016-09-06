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
use App\Models\productDevelopment\VPreparation;
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
            ->orderBy('sequentialIndex', 'asc')
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
        $StartDate = $request->input('ProcessStartDate');

        try {
            DB::beginTransaction();
            $ProcessID = Common::getNewGUID();

            $MaxIndex = new ProjectProcess();
            $MaxIndex = ($MaxIndex
                ->where('projectContentID', $ProjectContentID)
                ->max('sequentialIndex')) + 1;

            $Params = array(
                'ID' => $ProcessID,
                'projectContentID' => $ProjectContentID,
                'referenceName' => $ProcessName,
                'referenceNumber' => $ProcessNumber,
                'projectProcessPhaseID' => $PhaseID,
                'timeCost' => $TimeCost,
                'staffID' => iconv("UTF-8", "BIG-5", $StaffID),
                'sequentialIndex' => $MaxIndex,
                'processStartDate' => $StartDate,
                //'created_at' => Carbon::now(),
            );
            $ProjectProcess = new ProjectProcess();
            $ProjectProcess->insert($Params);

            
            $Params = array(
                'projectContentID' => $ProjectContentID,
                'projectProcessID' => $ProcessID,
                'treeLevel' => 0,
                //'seq' => $MaxIndex,
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
                'ProcessStartDate' => date('Y-m-d', strtotime($ProcessData->processStartDate)),
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
                $Process = new ProjectProcess();
                $Process = $Process
                    ->where('ID', $list['pid']);
                $Params = array(
                    'sequentialIndex' => $list['index'],
                );
                $Process->update($Params);
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
        $StartDate = $request->input('ProcessStartDate');
        $StaffID = $request->input('StaffID');

        try {
            DB::beginTransaction();
            $Params = array(
                'referenceName' => $ProcessName,
                'referenceNumber' => $ProcessNumber,
                'projectProcessPhaseID' => $PhaseID,
                'timeCost' => $TimeCost,
                'staffID' => $StaffID,
                'processStartDate' => $StartDate,
            );
            $ProjectProcess = new ProjectProcess();
            $ProjectProcess = $ProjectProcess
                ->where('ID', $ProjectProcessID);
            $ProjectProcess->update($Params);

            $this->UpdateChildsDate($ProjectProcessID);

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

    public function deleteProcess($ProcessID)
    {
        $Process = new ProjectProcess();
        try {
            DB::beginTransaction();
            $Process = $Process->where('ID', $ProcessID);
            $ProductID = $Process->first()->projectContentID;
            $Process->delete();
            $ProcessTree = new ProcessTree();
            $DelTree = $ProcessTree->where('projectProcessID', $ProcessID);
            $DelTree->delete();

            $sort = $ProcessTree
                ->where('projectContentID', $ProductID)
                ->where('projectProcessID', '<>', $ProcessID)
                ->orderBy('seq')
                ->get();
            //reset sort
            for ($i = 0; $i < $sort->count(); $i++) {
                $ProcessTree = new ProcessTree();
                $ProcessTree = $ProcessTree
                    ->where('projectProcessID', $sort[$i]->projectProcessID);
                $Params = array(
                    'seq' => $i+1,
                );
                $ProcessTree->update($Params);
            }

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
    public function getPreparationList($ProductID,$ProcessID)
    {
        try {
            $PreparationList = new VProcessList();
            $PreparationList = $PreparationList
                ->where('projectContentID', $ProductID)
                ->where('ID', '<>', $ProcessID)
                ->orderBy('sequentialIndex')
                ->get();
            $SelectList = new processTree();
            $SelectList = $SelectList
                ->where('projectProcessID', $ProcessID)
                ->select('parentProcessID')
                ->get(); 
            $s = array();
            foreach ($SelectList as $list) {
                $cc = (string)$list->parentProcessID;
                array_push($s, $cc);
            };
            $jo =  array(
                'success' => true,
                'PreparationList' => $PreparationList,
                'SelectList' => $s,
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
    public function setPreparation($ProductID, $ProcessID, $ChSelect)
    {
        try {
            $Data = json_decode($ChSelect);
            DB::beginTransaction();
            $ProcessTree = new ProcessTree();
            $ProcessTree = $ProcessTree
                ->where('projectContentID', $ProductID)
                ->where('projectProcessID', $ProcessID);
            $ProcessTree->forceDelete();
            if (count($Data) == 0) {
                array_push($Data, '00000000-0000-0000-0000-000000000000');
            }

            foreach($Data as $list) {
                $InsProcessTree = new ProcessTree();
                $Params = array(
                    'projectContentID' => $ProductID,
                    'projectProcessID' => $ProcessID,
                    'parentProcessID' => $list,
                );
                $InsProcessTree->insert($Params);
            }
            //調整流程開始時間
            $this->UpdateStartDate($ProcessID);

            DB::commit();
            

            $jo = array(
                'success' => true,
                'msg' => '設定前置流程成功!',
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
    public function UpdateStartDate($ProcessID)
    {
        $MainProcess = new ProjectProcess();
        $ProcessTree = new ProcessTree();
        $Loop = $ProcessTree
            ->where('projectProcessID', $ProcessID)
            ->where('parentProcessID', '<>', '00000000-0000-0000-0000-000000000000')
            ->get();
        if (count($Loop) > 0) {
            $MaxDate = array();
            foreach($Loop as $list) {
                $Search = new ProjectProcess();
                $Search = $Search->where('ID', $list->parentProcessID)->first();
                array_push($MaxDate, strtotime($Search->processStartDate . '+' . ($Search->timeCost) . ' day')); 
            }
            $Update = $MainProcess->where('ID', $ProcessID);
            $MaxDate = max($MaxDate);
            if (strtotime($Update->first()->processStartDate) < $MaxDate) {
                $Params = array(
                    'processStartDate' => date('Y-m-d H:i:s', $MaxDate),
                );
                $Update->update($Params);
            }
            $CallTree = $ProcessTree->where('parentProcessID', $Update->first()->ID)->get();
            foreach ($CallTree as $list) {
                $this->UpdateStartDate($list->projectProcessID);
            }
        }
    }
    public function UpdateChildsDate($ProcessID)
    {
        $Parent = new ProcessTree();
        $Parent = $Parent->where('parentProcessID', $ProcessID)->get();
        foreach ($Parent as $list) {
            $this->UpdateStartDate($list->projectProcessID);
        }
    }
}