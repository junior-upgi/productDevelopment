<?php

namespace App\Http\Controllers\ProductDevelopment;

//use Class
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

//use Custom Class
use App\Http\Controllers\Common;
use App\Http\Controllers\ServerData;

//use Service
use App\Service\NotificationService;

//use Repositories
use App\Repositories\ProductDevelopment\ProjectRepositories;

class ProductController extends Controller
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
            ->with('ProjectID', $projectID)
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
            ->insertData($this->projectRepositories->projectContent, $params);
    }
    //
    public function editProduct($productID)
    {
        return view('Product.EditProduct')
            ->with('ProductData', $this->projectRepositories->getProductByID($productID))
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
            ->updateData($this->projectRepositories->projectContent, $params, $productID);
    }
    //
    public function productExecute($productID)
    {
        $exe = $this->projectRepositories->setProductExecute($productID);
        if ($exe['toNotify']) {
            $this->notification->productExecute($productID);
        }
        return $exe;
    }
    //
    public function deleteProduct($productID)
    {
        return $this->projectRepositories->deleteData($this->projectRepositories->projectContent, $productID);
    }
}