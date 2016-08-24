<?php

namespace App\Http\Controllers\ProductDevelopment;

//use Class
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Controllers\Common;
use App\Http\Controllers\ServerData;

//use DB
use DB;
use App\Models;
use App\Models\productDevelopment\Project;
use App\Models\productDevelopment\VProjectList;
use App\Models\productDevelopment\ProjectContent;
use App\Models\productDevelopment\Para;
use App\Models\companyStructure\VStaff;
use App\Models\companyStructure\Staff;
use App\Models\companyStructure\Node;
use App\Models\sales\Client;

class ProductController extends Controller
{
    public function productList($ProjectID)
    {
        $Project = new VProjectList();
        $ProjectData = $Project
            ->where('ID', $ProjectID)
            ->first();

        $ProjectContent = new ProjectContent();
        $ProductList = $ProjectContent
            ->where('projectID',$ProjectID)
            ->orderBy('priorityLevel')->orderBy('startDate','desc')
            ->paginate(15);

        return view('Product.ProductList')
            ->with('ProductList', $ProductList)
            ->with('ProjectData', $ProjectData);
    }
    
    public function addProduct($ProjectID)
    {
        $priorityLevelList = ServerData::getPriorityLevel();

        return view('Product.AddProduct')
            ->with('ProjectID', $ProjectID)
            ->with('PriorityLevelList', $priorityLevelList);
    }

    public function insertProduct(Request $request)
    {
        $NewID = Common::getNewGUID();
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
        $Parmas['created_at'] = Carbon::now();

        try {
            DB::beginTransaction();

            $oProjectContent = new ProjectContent();
            $oProjectContent->insert($params);
            
            DB::commit();
            $jo = array(
                'success' => true,
                'msg' => '新增開發產品成功!',
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

    public function editProduct($ProductID)
    {
        $ProjectContent = new ProjectContent();
        $ProductData = $ProjectContent
            ->where('ID', $ProductID)
            ->first();

        $priorityLevelList = ServerData::getPriorityLevel();

        return view('Product.EditProduct')
            ->with('ProductData', $ProductData)
            ->with('PriorityLevelList', $priorityLevelList);
    }

    public function updateProduct(Request $request)
    {
        $params = array();
        $ProductID = $request->input('ProductID');
        $params['referenceNumber'] = $request->input('ProductNumber');
        $params['referenceName'] = $request->input('ProductName');
        $params['requiredQuantity'] = $request->input('RequiredQuantity');
        $params['deliveredQuantity'] = $request->input('DeliveredQuantity');
        $params['deadline'] = $request->input('Deadline');
        $params['startDate'] = $request->input('StartDate');
        $params['priorityLevel'] = $request->input('PriorityLevel');

        try {
            DB::beginTransaction();

            $ProjectContent = new ProjectContent();
            $ProjectContent = $ProjectContent
                ->where('ID',$ProductID);
            
            if ($ProjectContent->count() < 1) {
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
        } catch (\PDOException $e) {
            DB::rollback();
            $jo = array(
                'success' => false,
                'msg' => $e,
            );
        }

        return $jo;
    }

    public function productExecute($ProductID)
    {
        
        $Product = new ProjectContent();
        $Product = $Product
            ->where('ID', $ProductID);
        $jo = array();

        try {
            if ($Product->count() > 0) {
                DB::beginTransaction();
                $Params = array(
                    'execute' => '1',
                );
                $Product->update($Params);
                DB::commit();
                $jo = array(
                    'success' => true,
                    'msg' => '開始執行產品開發!',
                );
            } else {
                $jo = array(
                    'success' => false,
                    'msg' => '找不到產品資料!',
                );
            }
        } catch (\PDOException $e) {
            DB::rollback();
            $jo = array(
                'success' => false,
                'msg' => $e,
            );
        }
        
        return $jo;
    }

    public function deleteProduct($ProductID)
    {
        $Product = new ProjectContent();
        try {
            DB::beginTransaction();

            $Product->where('ID', $ProductID)->delete();
            
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
}