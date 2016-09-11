<?php
namespace App\Repositories\ProductDevelopment;

use DB;
use App\Http\Controllers\Common;

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
    protected $common;
    protected $para;
    protected $project;
    protected $projectContent;
    protected $projectProcess;
    protected $processTree;
    protected $vPreparation;
    protected $vProjectList;
    protected $vProcessList;
    protected $vProductList;
    protected $vProjectReport;
    protected $vProductReport;
    protected $vShowProject;
    protected $vShowProduct;
    protected $vShowProcess;

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
        $this->vProcessList = $vProjectList;
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
    public function getProductList($proejctID, $padding = 0)
    {
        $list = $this->vProductList
            ->where()
            ->where('projectID',$ProjectID)
            //->orderBy('productStatus')
            //->orderBy('priorityLevel')
            ->orderBy('execute', 'desc')
            ->orderBy('deadline')
            ->orderBy('referenceNumber');
            if ($padding > 0) return $list->paginate($padding);

            return $list->get();
    }
    public function getProjectByID($id)
    {
        return $this->vProjectList->where('ID', $id)->first();
    }

    public function getProjectContent($projectID)
    {
        return $this->projectContent->where('projectID', $projectID)->get();
    }

    public function showProjectExecute()
    {
        return $this->vShowProject
            ->where('productExecute', '>', 0)
            ->orderBy('completeStatus')
            ->orderBy('startDate')
            ->get();
    }

    public function insertData($table, $params, $primaryKey = 'ID')
    {
        try {
            DB::beginTransaction();
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
                'msg' => $e,
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
                'msg' => $e,
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
                'msg' => $e,
            );
        }
    }
    public function setProductExecute($productID)
    {
        $product = $this->projectContent->where($productID);
        $executeStatus = $product->first()->execute;
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
        if (!$result['success']) return array('success' => false, 'msg' => $type + '失敗');
        return array('success' => true, 'msg' => $type + '成功');
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
}