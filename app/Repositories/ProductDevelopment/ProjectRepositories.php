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
            DB::beginTransaction();
            foreach($sort as $list)
            {
                $process = $this->projectProcess->where('ID', $list['pid']);
                $params = array('sequentialIndex' => $list['index']);
                $process->update($params);
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
                'msg' => $e['errorInfo'][2],
            );
        }
        return $jo;
    }
    public function insertData($table, $params, $primaryKey = 'ID')
    {
        try {
            DB::beginTransaction();
            /*
            if (is_array($table) && is_array($params) && is_array($primaryKey)) {
                //雙寫入
                //******************** 未完成
                if (count($table) === count($params) && count($table) ===  count($primaryKey)) {
                    for ($i = 0 ; $i < count($table) ; $i++) {
                        if ($i == 0) {
                            $newID = $this->common->getNewGUID();
                        } else {
                            $newID = $this->common->getNewGUID();
                        }
                        $t = $table[i];
                        $p = $params[i];
                        $k = $primaryKey[i];
                        
                    }
                } else {
                    return array(
                        'success' => false,
                        'msg' => '資料設定錯誤',
                    );
                }
            } elseif (is_array($table) || is_array($params) || is_array($primaryKey)) {
                //資料設定錯誤
                return array(
                    'success' => false,
                    'msg' => '資料設定錯誤',
                );
            } else {
                //單寫入
                $newID = $this->common->getNewGUID();
                $params[$primaryKey] = $newID;
                $t = $this->getTable($table);
                $t->insert($params);
            }
            */
            $newID = $this->common->getNewGUID();
            $params[$primaryKey] = $newID;
            $t = $this->getTable($table);
            $t->insert($params);
            DB::commit();
            return array(
                'success' => true,
                'msg' => '新增成功',
            );
        } catch (\PDOException $e) {
            DB::rollback();
            return array(
                'success' => false,
                'msg' => $e['errorInfo'][2],
            );
        }
    }
    public function updateData($table, $params, $id, $primaryKey = 'ID')
    {
        try {
            DB::beginTransaction();
            $t = $this->getTable($table);
            $t->where($primaryKey, $id)->update($params);
            DB::commit();
            return array(
                'success' => true,
                'msg' => '編輯成功',
            );
        } catch (\PDOException $e) {
            DB::rollback();
            return array(
                'success' => false,
                'msg' => $e['errorInfo'][2],
            );
        }
    }
    public function deleteData($table, $id, $primaryKey = 'ID')
    {
        try {
            DB::beginTransaction();
            $t = $this->getTable($table);
            $t->where($primaryKey, $id)->delete();
            DB::commit();
            return array(
                'success' => true,
                'msg' => '刪除成功',
            );
        } catch (\PDOException $e) {
            DB::rollback();
            return array(
                'success' => false,
                'msg' => $e['errorInfo'][2],
            );
        }
    }
    public function setProductExecute($productID)
    {
        $executeStatus = $this->getProductByID($productID)->execute;
        if ($executeStatus == '0') {
            $type = '執行產品開發';
            $params = array('execute' => '1');
            $result = $this->updateData('projectContent', $params, $productID);
        } elseif ($executeStatus == '1') {
            $type = '取消執行產品開發';
            $params = array('execute' => '0');
            $result = $this->updateData('projectContent', $params, $productID);
        } else {
            return array(
                'success' => true,
                'msg' => '資料異常',
            );
        }
        if (!$result['success']) return array('success' => false, 'msg' => $type . '失敗');
        return array('success' => true, 'msg' => $type . '成功');
    }
    public function getMaxSeqIndex($productID)
    {
        return $this->projectProcess->where('projectContentID', $productID)->max('sequentialIndex');
    }
    public function getParaList($paracode)
    {
        return $this->para->where('paracode', $paracode)->get();
    }
    private function getTable($table)
    {
        switch ($table) {
            case 'project' :
                return $this->project;
                break;
            case 'projectContent' :
                return $this->projectContent;
                break;
            case 'projectProcess' :
                return $this->projectProcess;
                break;
            case 'processTree' :
                return $this->processTree;
                break;
        }
    }
    public function updateProcess($processID, $params)
    {
        try {
            DB::beginTransaction();
            $process = $this->projectProcess->where('ID', $processID);
            $process->update($params);

            $this->updateChildsDate($processID);

            DB::commit();
            
            $jo = array(
                'success' => true,
                'msg' => '更新程序成功!',
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
    public function setPreparation($productID, $processID, $select)
    {
        try {
            $data = json_decode($select);
            DB::beginTransaction();
            $processTree = $this->processTree
                ->where('projectContentID', $productID)
                ->where('projectProcessID', $processID)->forceDelete();
            if (count($data) == 0) {
                array_push($data, '00000000-0000-0000-0000-000000000000');
            }
            foreach($data as $list) {
                $insProcessTree = new ProcessTree();
                $params = array(
                    'projectContentID' => $productID,
                    'projectProcessID' => $processID,
                    'parentProcessID' => $list,
                );
                $this->processTree->insert($params);
            }
            //調整流程開始時間
            $this->updateStartDate($processID);
            DB::commit();
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
            DB::beginTransaction();
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
                    foreach($loop as $parList) {
                        $search = $this->projectProcess->where('ID', $parList->parentProcessID)->first();
                        array_push($maxDate, strtotime($search->processStartDate . '+' . ($search->timeCost) . ' day')); 
                        if ($search->complete === '1') {
                            array_push($maxComplete, strtotime(date('Y-m-d', strtotime($search->completeTime))));
                        }
                    }
                    
                    if (count($maxComplete) === 0 && count($maxDate) > 0) {
                        $setDate = max($maxDate);
                    } elseif (count($maxComplete) > 0 && count($maxDate) === 0) {
                        $setDate = max($maxComplete);
                    } elseif (count($maxComplete) > 0 && count($maxDate) > 0) {
                        $setDate = max($maxComplete);
                        if ((count($maxComplete) < count($maxDate)) && (max($maxComplete) < max($maxDate))) {
                            $setDate = max($maxDate);
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
            DB::commit();
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
    public function getParentList($processID)
    {
        return $this->processTree
            ->where('projectProcessID', $processID)
            ->where('parentProcessID', '<>', '00000000-0000-0000-0000-000000000000')
            ->get();
    }
    public function deleteProcess($processID)
    {
        try {
            DB::beginTransaction();
            $process = $this->projectProcess->where('ID', $processID);
            $productID = $process->first()->projectContentID;
            $process->delete();
            $this->processTree->where('projectProcessID', $processID)->delete();

            $sort = $this->processTree
                ->where('projectContentID', $ProductID)
                ->where('projectProcessID', '<>', $ProcessID)
                ->orderBy('seq')
                ->get();
            //reset sort
            for ($i = 0; $i < $sort->count(); $i++) {
                $newTree = $this->processTree->where('projectProcessID', $sort[$i]->projectProcessID);
                $params = array('seq' => $i+1);
                $newTree->update($params);
            }

            DB::commit();
            $jo = array(
                'success' => true,
                'msg' => '刪除程序成功!',
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
            foreach($loop as $list) {
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
}