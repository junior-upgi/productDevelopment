<?php
/**
 * 報表相關功能
 *
 * @version 1.0.0
 * @author spark it@upgi.com.tw
 * @date 16/10/17
 * @since 1.0.0 spark: 於此版本開始編寫註解
 */
namespace App\Http\Controllers\ProductDevelopment;

//use Class
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

//use Custom Class
use App\Http\Controllers\Common;
use App\Http\Controllers\ServerData;

//use Repository
use App\Repositories\ProductDevelopment\ProjectRepository;

/**
 * Class ReportController
 *
 * @package App\Http\Controllers\ProductDevelopment
 */
class ReportController extends Controller
{
    /** @var Common 注入Common */
    private $common;
    /** @var ServerData 注入ServerData */
    private $serverData;
    /** @var ProjectRepository 注入ProjectRepository */
    private $projectRepository;

    /**
     * Common 建構式
     *
     * @param Common $common
     * @param ServerData $serverData
     * @param ProjectRepository $projectRepository
     * @return void
     */
    public function __construct(
        Common $common,
        ServerData $serverData,
        ProjectRepository $projectRepository
    ) {
        $this->common = $common;
        $this->serverData = $serverData;
        $this->projectRepository = $projectRepository;
    }

    /**
     * 輸出專案開發執行進度
     *
     * @return vProjectReport 回傳vProjectReport Model
     */
    public function projectExecuteRate()
    {
        $project = $this->projectRepository->getProjectReport();
        return view('Report.ProjectReport')
            ->with('Project', $project);
    }

    /**
     * 輸出產品開發執行進度
     * 
     * @return vProductReport 回傳vProductReport Model
     */
    public function productExecuteRate()
    {
        $project = $this->projectRepository->getProjectExecuteList();
        $detail = $this->projectRepository->getProductReport();
        return view('Report.ProductReport')
            ->with('Project', $project)
            ->with('Detail', $detail);
    }

    /**
     * 輸出每週開會用專案進度清單
     * 
     * @return 
     */
     public function meetingReport()
     {
         $product = $this->projectRepository->getProductExecuteList();
         $process = $this->projectRepository->getStartProcess();
         return view('Report.MeetingReport')
            ->with('product', $product)
            ->with('process', $process);
     }
}