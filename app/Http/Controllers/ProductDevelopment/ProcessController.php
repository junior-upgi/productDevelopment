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

    public function AddProcess()
    {
        
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
            $NewID = Common::GetNewGUID();
            $oProjectProcess = new projectProcess();
            $oProjectProcess->ID = $NewID;
            $oProjectProcess->projectContentID = $ProjectContentID;
            $oProjectProcess->referenceName = $ProcessName;
            $oProjectProcess->referenceNumber = $ProcessNumber;
            $oProjectProcess->projectProcessPhaseID = (int)$PhaseID;
            $oProjectProcess->TimeCost = (int)$TimeCost;
            $oProjectProcess->StaffID = (int)$StaffID;
            $oProjectProcess->save();
            
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
                'ProcessID' => $NewID,
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

    public function EditProcess()
    {

    }

    public function UpdateProcess()
    {

    }
}