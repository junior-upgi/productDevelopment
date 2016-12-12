<?php
namespace App\Http\Controllers\ProductDevelopment;
//use Class
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Carbon\Carbon;
//use Custom Class
use App\Http\Controllers\Common;
use App\Http\Controllers\ServerData;
//use Service
use App\Service\NotificationService;
//use Repositories
use App\Repositories\ProductDevelopment\ProjectRepository;
use App\Repositories\upgiSystem\UpgiSystemRepository;
class ProductController extends Controller
{
    public $common;
    public $serverData;
    public $notification;
    public $projectRepository;
    public $upgi;

    public function __construct(
        Common $common,
        ServerData $serverData,
        NotificationService $notification,
        ProjectRepository $projectRepository,
        UpgiSystemRepository $upgi
    ) {
        $this->common = $common;
        $this->serverData = $serverData;
        $this->notification = $notification;
        $this->projectRepository = $projectRepository;
        $this->upgi = $upgi;
    }
    //
    public function productList($projectID)
    {
        return view('Product.ProductList')
            ->with('ProductList', $this->projectRepository->getProductList($projectID, 15))
            ->with('ProjectData', $this->projectRepository->getProjectByID($projectID));
    }
    //
    public function addProduct($projectID)
    {
        $priority = $this->projectRepository->getParaList('priorityLevel');
        $projectNumber = $this->projectRepository->getProjectByID($projectID)->referenceNumber;
        return view('Product.AddProduct')
            ->with('ProjectID', $projectID)
            ->with('ProjectNumber', $projectNumber)
            ->with('PriorityLevelList', $priority);
    }
    //
    public function insertProduct(Request $request)
    {
        $id = $this->common->getNewGUID();
        $params = array(
            'ID' => $id,
            'projectID' => $request->input('ProjectID'),
            'referenceNumber' => $request->input('ProductNumber'),
            'referenceName' => $request->input('ProductName'),
            'requiredQuantity' => $request->input('RequiredQuantity'),
            'deliveredQuantity' => $request->input('DeliveredQuantity'),
            'deadline' => $request->input('Deadline'),
            'priorityLevel' => $request->input('PriorityLevel'),
            //'contentImg' => $pic,
            'created_at' => Carbon::now(),
        );

        $file = $request->file('img');
        if (isset($file)) {
            $pic = $this->common->saveFile($file);
            if (!isset($pic)) {
                return array(
                    'success' => false,
                    'msg' => '檔案上傳失敗',
                );
            }
            $params['contentImg'] = $pic;
        }

        $attach = $request->file('attach');
        if (isset($attach)) {
            $att = $this->common->saveFile($attach);
            if (!isset($att)) {
                return array(
                    'success' => false,
                    'msg' => '檔案上傳失敗',
                );
            }
            $params['contentAttach'] = $att;
        }

        $result =  $this->projectRepository->insertData($this->projectRepository->projectContent, $params);
        $group = $request->input('Group');
        if ($result['success'] && $group != '') {
            // 發送通知給所有開發案團隊
            $this->notification->sendNewProduct($id, $group);
        }
        return $result;
    }
    //
    public function editProduct($productID)
    {
        return view('Product.EditProduct')
            ->with('ProductData', $this->projectRepository->getProductByID($productID))
            ->with('PriorityLevelList', $this->projectRepository->getParaList('priorityLevel'));
    }
    //
    public function updateProduct(Request $request)
    {
        $productID = $request->input('ProductID');

        $attach = $request->file('attach');
        if (isset($attach)) {
            $att = $this->common->saveFile($attach);
            if (!isset($att)) {
                return array(
                    'success' => false,
                    'msg' => '檔案上傳失敗',
                );
            }
            $upload = true;
        } else {
            if ($request->input('fileSet') == 'true') {
                $upload = true;
                $pic = null;
            }
        }
        
        $params = array(
            'referenceNumber' => $request->input('ProductNumber'),
            'referenceName' => $request->input('ProductName'),
            'requiredQuantity' => $request->input('RequiredQuantity'),
            'deliveredQuantity' => $request->input('DeliveredQuantity'),
            'deadline' => $request->input('Deadline'),
            'priorityLevel' => $request->input('PriorityLevel'),
            //'contentImg' => $pic,
        );
        
        if (isset($upload)) $params['contentAttach'] = $att;
        return $this->projectRepository
            ->updateData($this->projectRepository->projectContent, $params, $productID);
    }
    //
    public function productExecute($productID)
    {
        $exe = $this->projectRepository->setProductExecute($productID);
        if ($exe['toNotify']) {
            $this->notification->productExecute($productID);
        }
        return $exe;
    }
    //
    public function deleteProduct($productID)
    {
        return $this->projectRepository->deleteData($this->projectRepository->projectContent, $productID);
    }

    public function getAttach($id)
    {
        $file = $this->common->getFileInfo($id);
        $filename = date('Ymdhis', strtotime(Carbon::now())) . "_$file->name";
        $base64 = base64_decode($file->code);

        return response($base64)
            //->header('Cache-Control', 'no-cache private')
            //->header('Content-Description', 'File Transfer')
            ->header('Content-Type', $file->type)
            ->header('Content-length', strlen($base64))
            ->header('Content-Disposition', 'attachment; filename=' . $filename)
            ->header('Content-Transfer-Encoding', 'binary');
    }
}