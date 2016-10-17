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

//use Repositories
use App\Repositories\ProductDevelopment\ProjectRepositories;

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
    /** @var ProjectRepositories 注入ProjectRepositories */
    private $projectRepositories;

    /**
     * Common 建構式
     *
     * @param Common $common
     * @param ServerData $serverData
     * @param ProjectRepositories $projectRepositories
     * @return void
     */
    public function __construct(
        Common $common,
        ServerData $serverData,
        ProjectRepositories $projectRepositories
    ) {
        $this->common = $common;
        $this->serverData = $serverData;
        $this->projectRepositories = $projectRepositories;
    }

    /**
     * 輸出專案開發執行進度
     *
     * @return vProjectReport 回傳vProjectReport Model
     */
    public function projectExecuteRate()
    {
        $project = $this->projectRepositories->getProjectReport();
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
        $project = $this->projectRepositories->getProjectExecuteList();
        $detail = $this->projectRepositories->getProductReport();
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
         //$meeting = $this->projectRepositories->currentExecuteList();
         $meeting = "";
         return view('Report.MeetingReport')
            ->with('meeting', $meeting);
     }
}