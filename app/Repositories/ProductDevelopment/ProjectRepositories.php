<?php
namespace App\Repositories\ProductDevelopment;

use DB;
use App\Http\Controllers\Common;
use Carbon\Carbon;

use App\Models\productDevelopment\Para;
use App\Models\productDevelopment\Project;
use App\Models\productDevelopment\ProjectContent;
use App\Models\productDevelopment\ProjectProcess;
use App\Models\productDevelopment\ProcessTree;
use App\Models\productDevelopment\VPreparation;
use App\Models\productDevelopment\VProjectList;
use App\Models\productDevelopment\VProductList;
use App\Models\productDevelopment\VProcessList;
use App\Models\productDevelopment\VProjectReport;
use App\Models\productDevelopment\VProductReport;
use App\Models\productDevelopment\VShowProject;
use App\Models\productDevelopment\VShowProduct;
use App\Models\productDevelopment\VShowProcess;

class ProjectRepositories
{
    public $common;
    public $carbon;
    public $para;
    public $project;
    public $projectContent;
    public $projectProcess;
    public $processTree;
    public $vPreparation;
    public $vProjectList;
    public $vProcessList;
    public $vProductList;
    public $vProjectReport;
    public $vProductReport;
    public $vShowProject;
    public $vShowProduct;
    public $vShowProcess;

    public function __construct(
        Common $common,
        Carbon $carbon,
        Para $para,
        Project $project,
        ProjectContent $projectContent,
        ProjectProcess $projectProcess,
        ProcessTree $processTree,
        VPreparation $vPreparation,
        VProjectList $vProjectList,
        VProcessList $vProcessList,
        VProductList $vProductList,
        VProjectReport $vProjectReport,
        VProductReport $vProductReport,
        VShowProject $vShowProject,
        VShowProduct $vShowProduct,
        VShowProcess $vShowProcess
    ) {
        $this->common = $common;
        $this->carbon = $carbon;
        $this->para = $para;
        $this->project = $project;
        $this->projectContent = $projectContent;
        $this->projectProcess = $projectProcess;
        $this->processTree = $processTree;
        $this->vPreparation = $vPreparation;
        $this->vProjectList = $vProjectList;
        $this->vProcessList = $vProcessList;
        $this->vProductList = $vProductList;
        $this->vProjectReport = $vProjectReport;
        $this->vProductReport = $vProductReport;
        $this->vShowProject = $vShowProject;
        $this->vShowProduct = $vShowProduct;
        $this->vShowProcess = $vShowProcess;
    }

    public function getNonCompleteProject($padding = 0)
    {
        $list = $this->vProjectList
            ->where('completeStatus', '<>', '2')
            ->orderBy('completeStatus')
            ->orderBy('created', 'desc');
            
        if ($padding > 0) return $list->paginate($padding);

        return $list->get();
    }
    public function getProjectExecuteList()
    {
        return $this->vProjectList
            ->where('productExecute', '>', 0)
            ->orderBy('startDate')
            ->orderBy('endDate')
            ->orderBy('referenceNumber')
            ->get();
    }
    public function getProductExecuteList()
    {
        $list = $this->vProductList
            ->where('execute', 1)
            ->orderBy('deadline')
            ->orderBy('nowEndDate')
            ->get();
        return $list;
    }
    public function getProductList($projectID, $padding = 0)
    {
        $list = $this->vProductList
            ->where('projectID',$projectID)
            //->orderBy('productStatus')
            //->orderBy('priorityLevel')
            ->orderBy('execute', 'desc')
            ->orderBy('deadline')
            ->orderBy('referenceNumber');
            if ($padding > 0) return $list->paginate($padding);

            return $list->get();
    }
    public function getProcessList($productID)
    {
        $list = $this->vProcessList
            ->where('projectContentID', $productID)
            ->orderBy('sequentialIndex', 'asc')
            ->get();
        return $list;
    }
    public function getNonCompleteProcessList($productID)
    {
        $list = $this->vProcessList
            ->where('projectContentID', $productID)
            ->where('complete', '0')
            ->orderBy('sequentialIndex', 'asc')
            ->get();
        return $list;
    }
    public function getProjectByID($id)
    {
        return $this->vProjectList->where('ID', $id)->first();
    }
    public function getProductByID($id)
    {
        return $this->vProductList->where('ID', $id)->first();
    }
    public function getProcessByID($id)
    {
        return $this->vProcessList->where('ID', $id)->first();
    }
    public function getProjectContent($projectID)
    {
        return $this->projectContent->where('projectID', $projectID)->get();
    }
    public function getProjectID($productID)
    {
        return $this->getProductByID($productID)->projectID;
    }
    public function getProductID($processID)
    {
        return $this->getProcessByID($processID)->projectContentID;
    }
    public function showProjectExecute()
    {
        return $this->vShowProject
            ->where('productExecute', '>', 0)
            ->orderBy('completeStatus')
            ->orderBy('startDate')
            ->get();
    }
    public function saveProcessSort($sort)
    {
        try
        {
            $this->projectProcess->getConnection()->beginTransaction();
            foreach ($sort as $list)
            {
                $process = $this->projectProcess->where('ID', $list['pid']);
                $params = array('sequentialIndex' => $list['index']);
                $process->update($params);
            }
            $this->projectProcess->getConnection()->commit();
            $jo = array(
                'success' => true,
                'msg' => '儲存排序成功',
            );
        }
        catch (\PDOException $e)
        {
            $this->projectProcess->getConnection()->rollback();
            $jo = array(
                'success' => false,
                'msg' => $e['errorInfo'][2],
            );
        }
        return $jo;
    }
    public function insertData($table, $params)
    {
        $result = $this->common->insert($table, $params);
        return $result;
    }
    public function updateData($table, $params, $id, $primaryKey = 'ID')
    {
        $table = $table->where($primaryKey, $id);
        $result = $this->common->update($table, $params);
        return $result;
    }
    public function deleteData($table, $id, $primaryKey = 'ID')
    {
        $table = $table->where($primaryKey, $id);
        $result = $this->common->delete($table);
        return $result;
    }
    public function setProductExecute($productID)
    {
        $executeStatus = $this->getProductByID($productID)->execute;
        if ($executeStatus == '0') {
            $type = '執行產品開發';
            $params = array('execute' => '1');
            $result = $this->updateData($this->projectContent, $params, $productID);
        } elseif ($executeStatus == '1') {
            $type = '取消執行產品開發';
            $params = array('execute' => '0');
            $result = $this->updateData($this->projectContent, $params, $productID);
        } else {
            return array(
                'success' => true,
                'msg' => '資料異常',
            );
        }

        if (!$result['success']) {
            return array('success' => false, 'msg' => $type . '失敗');
        }

        $toNotify = ($executeStatus == '0') ? true : false;

        return array('success' => true, 'msg' => $type . '成功', 'toNotify' => $toNotify);
    }
    public function getMaxSeqIndex($productID)
    {
        return $this->projectProcess->where('projectContentID', $productID)->max('sequentialIndex');
    }
    public function getParaList($paracode)
    {
        return $this->para->where('paracode', $paracode)->get();
    }
    public function getParaValue($type, $value)
    {
        return $this->para
            ->where('paracode', $type)
            ->where('paracodeno', $value)
            ->first()->paracodename;
    }
    public function updateProcess($processID, $params)
    {
        try {
            $this->projectProcess->getConnection()->beginTransaction();
            $process = $this->projectProcess->where('ID', $processID);

            $process->update($params);

            $this->updateChildsDate($processID);

            $this->projectProcess->getConnection()->commit();
            
            $jo = array(
                'success' => true,
                'msg' => '更新程序成功!',
            );
        } catch (\PDOException $e) {
            $this->projectProcess->getConnection()->rollback();
            $jo = array(
                'success' => false,
                'msg' => $e['errorInfo'][2],
            );
        }

        return $jo;
    }
    public function setPreparation($productID, $processID, $select)
    {
        try {
            $data = json_decode($select);
            $this->processTree->getConnection()->beginTransaction();
            $processTree = $this->processTree
                ->where('projectContentID', $productID)
                ->where('projectProcessID', $processID)->forceDelete();
            if (count($data) == 0) {
                array_push($data, '00000000-0000-0000-0000-000000000000');
            }
            foreach ($data as $list) {
                $params = array(
                    'projectContentID' => $productID,
                    'projectProcessID' => $processID,
                    'parentProcessID' => $list,
                );
                $this->processTree->insert($params);
            }
            //調整流程開始時間
            $this->updateStartDate($processID);
            $this->processTree->getConnection()->commit();
            $jo = array(
                'success' => true,
                'msg' => '設定前置流程成功!',
            );
         } catch (\PDOException $e) {
            DB::rollback();
            $jo = array(
                'success' => false,
                'msg' => $e['errorInfo'][2],
            );
        }
        return $jo;
    }
    public function setProcessComplete($processID, $params)
    {
        try {
            $this->projectProcess->getConnection()->beginTransaction();
            $this->projectProcess->where('ID', $processID)->update($params);
            $parent = $this->processTree->where('parentProcessID', $processID)->get();
            foreach ($parent as $list) {
                $subProcess = $this->projectProcess->where('ID', $list->projectProcessID)->first();
                $now = date('Y-m-d', strtotime(carbon::now()));
                $subProcessID = $list->projectProcessID;
                $subStartDate = $subProcess->processStartDate;
                $subTimeCost = $subProcess->timeCost;
                $subEndDate = strtotime($subProcess->processStartDate . '+' . ($subProcess->timeCost - 1) . ' day');
                $newTimeCost = (($subEndDate - strtotime($now)) / 86400);
                $setDate = strtotime($now) + 86400;
                $loop = $this->getParentList($subProcessID);
                if (count($loop) > 0)
                {
                    $maxDate = array();
                    $maxComplete = array();
                    foreach ($loop as $parList) {
                        $search = $this->projectProcess->where('ID', $parList->parentProcessID)->first();
                        array_push($maxDate, strtotime($search->processStartDate . '+' . ($search->timeCost) . ' day')); 
                        if ($search->complete === '1') {
                            array_push($maxComplete, strtotime(date('Y-m-d', strtotime($search->completeTime))));
                        }
                    }
                    
                    //11/10 測試修正，max($maxDate) & max($maxComplete) + 86400
                    if (count($maxComplete) === 0 && count($maxDate) > 0) {
                        $setDate = max($maxDate) + 86400;
                    } elseif (count($maxComplete) > 0 && count($maxDate) === 0) {
                        $setDate = max($maxComplete) + 86400;
                    } elseif (count($maxComplete) > 0 && count($maxDate) > 0) {
                        $setDate = max($maxComplete) +86400;
                        if ((count($maxComplete) < count($maxDate)) && (max($maxComplete) < max($maxDate))) {
                            $setDate = max($maxDate) + 86400;
                        }
                    }
                    if ($subEndDate > $setDate) {
                        $newTimeCost = (($subEndDate - $setDate) / 86400);
                    } else {
                        $newTimeCost = $subTimeCost / 86400;
                    }
                }
                $subParams = array(
                    'timeCost' => $newTimeCost,
                    'processStartDate' => date('Y-m-d', $setDate),
                );
                $this->projectProcess->where('ID', $subProcessID)->update($subParams);
            }
            $this->projectProcess->getConnection()->commit();
            $jo = array(
                'success' => true,
                'msg' => '設定前置流程成功!',
            );
         } catch (\PDOException $e) {
            $this->projectProcess->getConnection()->rollback();
            $jo = array(
                'success' => false,
                'msg' => $e['errorInfo'][2],
            );
        }
        return $jo;
    }
    public function getParentList($processID)
    {
        return $this->processTree
            ->where('projectProcessID', $processID)
            ->where('parentProcessID', '<>', '00000000-0000-0000-0000-000000000000')
            ->get();
    }
    public function getChildrenList($processID)
    {
        return $this->processTree
            ->where('parentProcessID', $processID)
            ->get();
    }
    public function deleteProcess($processID)
    {
        try {
            $this->projectProcess->getConnection()->beginTransaction();
            $process = $this->projectProcess->where('ID', $processID);
            $a = $process->first();
            $productID = $this->getProductID($processID);
            $process->delete();
            $this->processTree->where('projectProcessID', $processID)->delete();

            $sort = $this->processTree
                ->where('projectContentID', $productID)
                ->where('projectProcessID', '<>', $processID)
                ->orderBy('seq')
                ->get();
            //reset sort
            for ($i = 0; $i < $sort->count(); $i++) {
                $newTree = $this->processTree->where('projectProcessID', $sort[$i]->projectProcessID);
                $params = array('seq' => $i+1);
                $newTree->update($params);
            }

            $this->projectProcess->getConnection()->commit();
            $jo = array(
                'success' => true,
                'msg' => '刪除程序成功!',
            );
        } catch (\PDOException $e) {
            $this->projectProcess->getConnection()->rollback();
            $jo = array(
                'success' => false,
                'msg' => $e['errorInfo'][2],
            );
        }
        
        return $jo;
    }
    public function getPreparationList($productID, $processID)
    {
        return $this->vProcessList
            ->where('projectContentID', $productID)
            ->where('ID', '<>', $processID)
            ->orderBy('sequentialIndex')
            ->get();
    }
    public function getPreparationSelectList($processID)
    {
        return $this->processTree
            ->where('projectProcessID', $processID)
            ->select('parentProcessID')
            ->get(); 
    }
    public function updateStartDate($processID)
    {
        $mainProcess = $this->projectProcess;
        $processTree = $this->processTree;
        $loop = $this->getParentList($processID);
        if (count($loop) > 0) {
            $maxDate = array();
            foreach ($loop as $list) {
                $search = $this->projectProcess->where('ID', $list->parentProcessID)->first();
                array_push($maxDate, strtotime($search->processStartDate . '+' . ($search->timeCost) . ' day')); 
            }
            $update = $mainProcess->where('ID', $processID);
            $params = array('processStartDate' => date('Y-m-d H:i:s', max($maxDate)));
            $update->update($params);
            /*
            if (strtotime($update->first()->processStartDate) < max($maxDate)) {
                $params = array('processStartDate' => date('Y-m-d H:i:s', max($maxDate)));
                $update->update($params);
            } else {
                $params = array('processStartDate' => date('Y-m-d H:i:s', max($maxDate)));
                $update->update($params);
            }
            */
            $callTree = $processTree->where('parentProcessID', $update->first()->ID)->get();
            foreach ($callTree as $list) {
                $this->updateStartDate($list->projectProcessID);
            }
        }
    }
    public function updateChildsDate($processID)
    {
        $parent = $this->processTree->where('parentProcessID', $processID)->get();
        foreach ($parent as $list) {
            $this->updateStartDate($list->projectProcessID);
        }
    }
    public function getProjectReport()
    {
        return $this->vProjectReport
            ->orderBy('deadline')
            ->orderBy('startDate')
            ->orderBy('endDate')
            ->orderBy('projectNumber')
            ->get();
    }
    public function getProductReport()
    {
        return $this->vProductReport
            ->orderBy('deadline')
            ->orderBy('startDate')
            ->orderBy('endDate');
    }
    public function getDelayProcess()
    {
        $date = $this->carbon->now();
        $now = date('Y-m-d', strtotime($date));
        $delayList = $this->vProcessList
            ->where('execute', '1')
            ->where('complete', '0' )
            ->where('processEndDate', '<', $now)
            ->get();
        return $delayList;
    }
    public function getDelayProduct()
    {   
        $date = $this->carbon->now();
        $now = date('Y-m-d', strtotime($date));
        $list = $this->vProductList
            ->where('execute', '1')
            ->where('productStatus', '<>', '2')
            ->where('deadline', '<', $now)
            ->orderBy('deadline')
            ->get();
        return $list;
    }
    public function getProductPic($id)
    {
        $pic = $this->getProductByID($id)->contentImg;
        if (!isset($pic)) {
            $pic = $this->projectProcess
            ->where('projectContentID', $id)
            ->where(function ($q)
            {
                $q->where('processImg', '<>', null)
                ->orWhere('processImg', '<>', '');
            })
            ->orderBy('sequentialIndex', 'desc')
            ->first();
            if (isset($pic)) return $pic->processImg;
        }
        return $pic;
    }
    public function checkOverdue($productID)
    {
        $process = $this->vProcessList
            ->where('productID', $productID)
            ->orderBy('processEndDate', 'desc')
            ->first();
        if (date('Y-m-d', strtotime($process->processEndDate)) > date('Y-m-d', strtotime($process->deadline))) {
            return true;
        }
        return false;
    }
    public function getPersonalProcess($userID)
    {
        $process = $this->vProcessList
            ->where('staffID', $userID)
            ->where('execute', '1')
            ->where('complete', '0')
            ->orderBy('processStartDate')
            ->orderBy('processEndDate')
            ->orderBy('timeCost')
            ->get();
        return $process;
    }
    public function getStartProcess()
    {
        $now = date('Y-m-d', strtotime($this->carbon->now()));
        $process = $this->vProcessList
            ->where('execute', 1)
            ->where('processStartDate', '<=', $now)
            //->where('processEndDate', '>=', $now)
            ->where('complete', 0)
            ->orderBy('processStartDate')
            ->get();
        return $process;
    }
}