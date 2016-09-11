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
use App\Models\productDevelopment\VProductList;
use App\Models\productDevelopment\ProjectContent;
use App\Models\productDevelopment\Para;
use App\Models\companyStructure\VStaff;
use App\Models\companyStructure\Staff;
use App\Models\companyStructure\Node;
use App\Models\sales\Client;

//use Repositories
use App\Repositories\ProductDevelopment\ProjectRepositories;

class ProductController extends Controller
{
    protected $projectRepositories;

    public function __construct(
        ProjectRepositories $projectRepositories
    ) {
        $this->projectRepositories = $projectReopsitories;
    }
    //
    public function productList($projectID)
    {
        return view('Product.ProductList')
            ->with('ProductList', $this->projectRepositories->getProductList($projectID, 15))
            ->with('ProjectData', $this->projectRepositories->getProjectByID($projectID));
    }
    //
    public function addProduct($projectID)
    {
        return view('Product.AddProduct')
            ->with('ProjectID', $ProjectID)
            ->with('PriorityLevelList', $this->projectRepositories->getParaList('priorityLevel'));
    }
    //
    public function insertProduct(Request $request)
    {
        $params = array(
            'projectID' => $request->input('ProjectID'),
            'referenceNumber' => $request->input('ProductNumber'),
            'referenceName' => $request->input('ProductName'),
            'requiredQuantity' => $request->input('RequiredQuantity'),
            'deliveredQuantity' => $request->input('DeliveredQuantity'),
            'deadline' => $request->input('Deadline'),
            'priorityLevel' => $request->input('PriorityLevel'),
            'created_at' => Carbon::now(),
        );
        return $this->projectRepositories
            ->insertData('projectContent', $params);
    }
    //
    public function editProduct($ProductID)
    {
        return view('Product.EditProduct')
            ->with('ProductData', $this->projectRepositories->getProjectByID($projectID))
            ->with('PriorityLevelList', $this->projectRepositories->getParaList('priorityLevel'));
    }
    //
    public function updateProduct(Request $request)
    {
        $productID = $request->input('ProductID');
        $params = array(
            'referenceNumber' => $request->input('ProductNumber'),
            'referenceName' => $request->input('ProductName'),
            'requiredQuantity' => $request->input('RequiredQuantity'),
            'deliveredQuantity' => $request->input('DeliveredQuantity'),
            'deadline' => $request->input('Deadline'),
            'priorityLevel' => $request->input('PriorityLevel'),
        );
        return $this->projectRepositories
            ->updateData('projectContent', $params, $productID);
    }
    //
    public function productExecute($productID)
    {
        return $this->projectRepositories->setProductExecute($productID);
    }
    //
    public function deleteProduct($productID)
    {
        return $this->projectRepositories->deleteData('projectContent', $productID);
    }
}