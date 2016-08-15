<?php

namespace App\Http\Controllers\ProductDevelopment;

//use Class
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Controllers\Common;

//use DB
use DB;
use App\Models\productDevelopment\project;
use App\Models\productDevelopment\vProjectList;
use App\Models\productDevelopment\projectContent;
use App\Models\productDevelopment\para;
use App\Models\companyStructure\vStaff;
use App\Models\companyStructure\staff;
use App\Models\companyStructure\node;
use App\Models\sales\client;

class ProductController extends Controller
{
    public function ProductList($ProjectID)
    {
        $Project = new vProjectList();
        $ProjectData = $Project
            ->where('ID', $ProjectID)
            ->get()
            ->first();

        $ProjectContent = new projectContent();
        $ProductList = $ProjectContent
            ->where('projectID',$ProjectID)
            ->get();

        return view('Product.ProductList')
            ->with('ProductList', $ProductList)
            ->with('ProjectData', $ProjectData);
    }
    
    public function AddProduct($ProjectID)
    {
        $para = new para();
        $priorityLevelList = $para
            ->where('paracode','priorityLevel')
            ->get();

        return view('Product.AddProduct')
            ->with('ProjectID', $ProjectID)
            ->with('PriorityLevelList', $priorityLevelList);
    }

    public function InsertProduct(Request $request)
    {
        $NewID = Common::GetNewGUID();
        $params = array();
        $params['ID'] = $NewID;
        $params['projectID'] = $request->input('ProjectID');
        $params['referenceNumber'] = $request->input('ProductNumber');
        $params['referenceName'] = $request->input('ProductName');
        $params['requiredQuantity'] = $request->input('RequiredQuantity');
        $params['deliveredQuantity'] = $request->input('DeliveredQuantity');
        $params['deadline'] = $request->input('Deadline');
        $params['startDate'] = $request->input('StartDate');
        $params['priorityLevel'] = $request->input('PriorityLevel');

        try {
            DB::beginTransaction();

            $oProjectContent = new projectContent();
            $oProjectContent->insert($params);
            
            DB::commit();
            $jo = array(
                'success' => true,
                'msg' => '新增開發產品成功!',
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

    public function EditProduct($ProductID)
    {
        $ProjectContent = new projectContent();
        $ProductData = $ProjectContent
            ->where('ID', $ProductID)
            ->get()
            ->first();

        $para = new para();
        $priorityLevelList = $para
            ->where('paracode','priorityLevel')
            ->get();

        return view('Product.EditProduct')
            ->with('ProductData', $ProductData)
            ->with('PriorityLevelList', $priorityLevelList);
    }

    public function UpdateProduct(Request $request, $ProductID)
    {
        $params = array();
        $params['referenceNumber'] = $request->input('ProductNumber');
        $params['referenceName'] = $request->input('ProductName');
        $params['requiredQuantity'] = $request->input('RequiredQuantity');
        $params['deliveredQuantity'] = $request->input('DeliveredQuantity');
        $params['deadline'] = $request->input('Deadline');
        $params['startDate'] = $request->input('StartDate');
        $params['priorityLevel'] = $request->input('PriorityLevel');

        try {
            DB::beginTransaction();

            $ProjectContent = new projectContent();
            $ProjectContent = $ProjectContent
                ->where('ID',$ProductID);
            
            if($ProjectContent->count() < 1)
            {
                $jo = array(
                    'success' => false,
                    'msg' => '找不到該開發產品資訊!',
                );
                return $jo;
            }
            $ProjectContent->update($params);
            
            DB::commit();
            $jo = array(
                'success' => true,
                'msg' => '更新開發產品成功!',
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
}