<?php
namespace App\Http\Controllers\ProductDevelopment;
//use Class
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use Auth;
use App\Models\productDevelopment\ProjectProcess;
use App\Models\productDevelopment\ProcessTree;
//use Custom Class
use App\Http\Controllers\Common;
use App\Http\Controllers\ServerData;
//use Service
use App\Service\NotificationService;
//use Repositories
use App\Repositories\ProductDevelopment\ProjectRepository;
class ProcessController extends Controller
{
    public $common;
    public $serverData;
    public $notification;
    public $projectRepository;
    public function __construct(
        Common $common,
        ServerData $serverData,
        NotificationService $notification,
        ProjectRepository $projectRepository
    ) {
        $this->common = $common;
        $this->serverData = $serverData;
        $this->notification = $notification;
        $this->projectRepository = $projectRepository;
    }
    //
    public function processList($productID)
    {
        $Preparation = $this->projectRepository->getPreparationInfo($productID)->get();
        return view('Process.ProcessList')
            ->with('ProductData', $this->projectRepository->getProductByID($productID))
            ->with('ProcessList', $this->projectRepository->getProcessList($productID))
            ->with('NodeList', $this->serverData->getAllNode())
            ->with('PhaseList', $this->projectRepository->getParaList('ProcessPhaseID'))
            ->with('Preparation', $Preparation);
    }
    //暫時無解!!
    public function insertProcess(Request $request)
    { 
        try {
            $processID = $this->common->getNewGUID();
            $file = $request->file('img');
            if (isset($file)) {
                $pic = $this->common->saveFile($file);
                if (!isset($pic)) {
                    return array(
                        'success' => false,
                        'msg' => '圖片上傳失敗',
                    );
                }
                $upload = true;
            }
            
            DB::beginTransaction();
            $params = array(
                'ID' => $processID,
                'projectContentID' => $request->input('ProductID'),
                'referenceNumber' => $request->input('ProcessNumber'),
                'referenceName' => $request->input('ProcessName'),
                'projectProcessPhaseID' => $request->input('PhaseID'),
                'timeCost' => $request->input('TimeCost'),
                'staffID' => iconv("UTF-8", "BIG-5", $request->input('StaffID')),
                'sequentialIndex' => $this->projectRepository->getMaxSeqIndex($request->input('ProductID')) + 1,
                'processStartDate' => $request->input('ProcessStartDate'),
                'note' => $request->input('note'),
            );
            if (isset($upload)) $params['processImg'] = $pic;
            $ProjectProcess = new ProjectProcess();
            $params = $this->common->convBig5($params);
            $ProjectProcess->insert($params);
            
            $params = array(
                'projectContentID' => $request->input('ProductID'),
                'projectProcessID' => $processID,
                'treeLevel' => 0,
                //'seq' => $MaxIndex,
            );
            
            $ProcessTree = new ProcessTree();
            $params = $this->common->convBig5($params);
            $ProcessTree->insert($params);
            
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
        $processData = $this->projectRepository->getProcessByID($processID);
        $jo = array();
        if ($processData) {
            $pic = $this->common->getFile($processData->processImg);
            $jo = array(
                'success' => true,
                'ID' => $processData->ID,
                'ProcessNumber' => $processData->referenceNumber,
                'ProcessName' => $processData->referenceName,
                'PhaseID' => $processData->projectProcessPhaseID,
                'TimeCost' => $processData->timeCost,
                'StaffID' => $processData->staffID,
                'StaffName' => $processData->name,
                'processImg' => $pic,
                'note' => $processData->note,
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
        return $this->projectRepository->saveProcessSort($sort);
    }
    //
    public function updateProcess(Request $request)
    {
        $processID = $request->input('ProcessID');
        $processName = $request->input('StaffID');
        $file = $request->file('img');
        if (isset($file)) {
            $pic = $this->common->saveFile($file);
            if (!isset($pic)) {
                return array(
                    'success' => false,
                    'msg' => '圖片上傳失敗',
                );
            }
            $upload = true;
        } else {
            if ($request->input('fileSet') == 'true') {
                $upload = true;
                $pic = null;
            }
        }
        $processNumber = $request->input('ProcessNumber'); 
        $processName = $request->input('ProcessName');
        $phaseID = $request->input('PhaseID');
        $timeCost = $request->input('TimeCost');
        $staffID = $request->input('StaffID');
        $processStartDate = $request->input('ProcessStartDate');
        $note = $request->input('note');
        if (isset($processNumber)) $params['referenceNumber'] = $processNumber;
        if (isset($processName)) $params['referenceName'] = $processName;
        if (isset($phaseID)) $params['projectProcessPhaseID'] = $phaseID;
        if (isset($timeCost)) $params['timeCost'] = $timeCost;
        if (isset($staffID)) $params['staffID'] = $staffID;
        if (isset($processStartDate)) $params['processStartDate'] = $processStartDate;
        if (isset($upload)) $params['processImg'] = $pic;
        if (isset($note)) $params['note'] = $note;
        return $this->projectRepository->updateProcess($processID, $params);
    }
    //
    public function processComplete($processID)
    {
        $completeStatus = $this->projectRepository->getProcessByID($processID)->complete;
        if ($completeStatus === '1') {
            $params = array('complete' => '0');
            $result = $this->projectRepository->updateData($this->projectRepository->projectProcess, $params, $processID);
        } elseif ($completeStatus === '0') {
            $now = date('Y-m-d H:i:s', strtotime(carbon::now()));
            $params = array(
                'complete' => '1',
                'completeTime' => $now,
            );
            $result = $this->projectRepository->setProcessComplete($processID, $params);
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
        return $this->projectRepository->deleteProcess($processID);
    }
    //
    public function getPreparationList($productID, $processID)
    {
        $preparationList = $this->projectRepository->getPreparationList($productID, $processID);
        $selectList = $this->projectRepository->getPreparationSelectList($processID);
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
        $set = $this->projectRepository->setPreparation($productID, $processID, $select);
        return $set;
    }
    public function myProcess()
    {
        $user = Auth::user();
        $role = $user->authorization;
        $userID = $user->erpID;
        $process = $this->projectRepository->getPersonalProcess($userID);
        return view('Process.MyProcess')
            ->with('process', $process)
            ->with('NodeList', $this->serverData->getAllNode())
            ->with('PhaseList', $this->projectRepository->getParaList('ProcessPhaseID'));
    }

    public function resetSort()
    {
        $test = $this->projectRepository->resetSort('76B22E3A-BC16-C55B-E708-1C0F55AF090F');
        return $test;
    }
}