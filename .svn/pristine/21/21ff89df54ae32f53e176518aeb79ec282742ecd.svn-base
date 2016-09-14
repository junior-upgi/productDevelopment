<?php

namespace App\Http\Controllers\ProductDevelopment;

//use Class
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use App\Models\productDevelopment\ProjectProcess;
use App\Models\productDevelopment\ProcessTree;

//use Custom Class
use App\Http\Controllers\Common;
use App\Http\Controllers\ServerData;

//use Service
use App\Service\NotificationService;

//use Repositories
use App\Repositories\ProductDevelopment\ProjectRepositories;

class ProcessController extends Controller
{
    public $common;
    public $serverData;
    public $notification;
    public $projectRepositories;

    public function __construct(
        Common $common,
        ServerData $serverData,
        NotificationService $notification,
        ProjectRepositories $projectRepositories
    ) {
        $this->common = $common;
        $this->serverData = $serverData;
        $this->notification = $notification;
        $this->projectRepositories = $projectRepositories;
    }

    //
    public function processList($productID)
    {
        return view('Process.ProcessList')
            ->with('ProductData', $this->projectRepositories->getProductByID($productID))
            ->with('ProcessList', $this->projectRepositories->getProcessList($productID))
            ->with('NodeList', $this->serverData->getAllNode())
            ->with('PhaseList', $this->projectRepositories->getParaList('ProcessPhaseID'));
    }
    //暫時無解!!
    public function insertProcess(Request $request)
    { 
        try {
            $processID = $this->common->getNewGUID();;
            DB::beginTransaction();
            $params = array(
                'ID' => $processID,
                'projectContentID' => $request->input('ProductID'),
                'referenceName' => $request->input('ProcessNumber'),
                'referenceNumber' => $request->input('ProcessName'),
                'projectProcessPhaseID' => $request->input('PhaseID'),
                'timeCost' => $request->input('TimeCost'),
                'staffID' => iconv("UTF-8", "BIG-5", $request->input('StaffID')),
                'sequentialIndex' => $this->projectRepositories->getMaxSeqIndex($request->input('ProductID')) + 1,
                'processStartDate' => $request->input('ProcessStartDate'),
                //'created_at' => Carbon::now(),
            );
            $ProjectProcess = new ProjectProcess();
            $ProjectProcess->insert($params);

            
            $Params = array(
                'projectContentID' => $request->input('ProductID'),
                'projectProcessID' => $processID,
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
    //
    public function getProcessData($processID)
    {
        $processData = $this->projectRepositories->getProcessByID($processID);
        $staffList = $this->serverData->getStaffByNodeID($processData->nodeID);
        $jo = array();
        if ($processData && $staffList) {
            $jo = array(
                'success' => true,
                'ID' => $processData->ID,
                'ProcessNumber' => $processData->referenceNumber,
                'ProcessName' => $processData->referenceName,
                'PhaseID' => $processData->projectProcessPhaseID,
                'TimeCost' => $processData->timeCost,
                'StaffID' => $processData->staffID,
                'NodeID' => $processData->nodeID,
                'StaffList' => $staffList,
                'ProcessStartDate' => date('Y-m-d', strtotime($processData->processStartDate)),
            );
        } else {
            $jo = array(
                'success' => false,
                'msg' => '找不到程序資訊!',
            );
        }

        return $jo;
    }
    //
    public function saveProcessSort(Request $request)
    {
        $sort = $request->json()->all();
        return $this->projectRepositories->saveProcessSort($sort);
    }
    //
    public function updateProcess(Request $request)
    {
        $processID = $request->input('ProcessID');
        $processName = $request->input('StaffID');
        $params = array(
            'referenceName' => $request->input('ProcessName'),
            'referenceNumber' => $request->input('ProcessNumber'),
            'projectProcessPhaseID' => $request->input('PhaseID'),
            'timeCost' => $request->input('TimeCost'),
            'staffID' => $request->input('StaffID'),
            'processStartDate' => $request->input('ProcessStartDate'),
        );
        return $this->projectRepositories->updateProcess($processID, $params);
    }
    //
    public function processComplete($processID)
    {
        $completeStatus = $this->projectRepositories->getProcessByID($processID)->complete;
        if ($completeStatus === '1') {
            $params = array('complete' => '0');
            $result = $this->projectRepositories->updateData($this->projectRepositories->projectProcess, $params, $processID);
        } elseif ($completeStatus === '0') {
            $now = date('Y-m-d H:i:s', strtotime(carbon::now()));
            $params = array(
                'complete' => '1',
                'completeTime' => $now,
            );
            $result = $this->projectRepositories->setProcessComplete($processID, $params);
        } else {
            return $jo = array('success' => false, 'msg' => '資料異常!');
        }

        if ($result['success'] && $completeStatus === '0') {
            return array('success' => true, 'msg' => '完成程序!');
        } elseif ($result['success'] && $completeStatus === '1') {
            return array('success' => true, 'msg' => '取消完成程序!');
        } else {
            return $result;
        }
    }
    //
    public function deleteProcess($processID)
    {
        return $this->projectRepositories->deleteProcess($processID);
    }
    //
    public function getPreparationList($productID, $processID)
    {
        $preparationList = $this->projectRepositories->getPreparationList($productID, $processID);
        $selectList = $this->projectRepositories->getPreparationSelectList($processID);
        $s = array();
        foreach ($selectList as $list) {
            $cc = (string)$list->parentProcessID;
            array_push($s, $cc);
        };
        return array(
            'success' => true,
            'PreparationList' => $preparationList,
            'SelectList' => $s,
        );
    }
    //
    public function setPreparation($productID, $processID, $select)
    {
        return $this->projectRepositories->setPreparation($productID, $processID, $select);
    }
}